<?php

namespace App\Http\Controllers;

use App\Jobs\SendPaymentConfirmationJob;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Services\Payments\ShurjoPayService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private ShurjoPayService $shurjoPay;

    public function __construct(ShurjoPayService $shurjoPay)
    {
        $this->shurjoPay = $shurjoPay;
    }

    public function checkout(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $latestPayment = $booking->payments()->latest()->first();

        return view('payments.checkout', compact('booking', 'latestPayment'));
    }

    public function initiatePayment(Request $request)
    {
        $data = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'payment_method' => ['required', 'string'],
        ]);

        $booking = Booking::findOrFail($data['booking_id']);
        $this->authorizeBooking($booking);

        $amount = max($booking->amount - $booking->discount_amount, 0);
        $transactionId = 'TN' . now()->format('YmdHis') . Str::upper(Str::random(6));

        $payment = Payment::create([
            'user_id' => $request->user()->id,
            'booking_id' => $booking->id,
            'amount' => $amount,
            'currency' => $booking->currency ?? 'BDT',
            'payment_method' => $data['payment_method'],
            'transaction_id' => $transactionId,
            'status' => 'pending',
        ]);

        if ($data['payment_method'] === 'demo' && !app()->environment('production')) {
            $this->logGateway($payment, 'demo', 'request', ['note' => 'Local demo payment']);
            $payment->update([
                'status' => 'success',
                'gateway_response' => ['demo' => true],
            ]);
            $booking->update(['status' => 'confirmed']);
            $this->sendConfirmationEmail($payment);

            return redirect()->route('payment.checkout', $booking->id)
                ->with('success', 'Payment completed successfully (demo).');
        }

        $tokenData = $this->shurjoPay->getToken();
        $this->logGateway(null, 'token', 'response', $tokenData);
        if (!$tokenData) {
            $payment->update(['status' => 'failed']);
            return back()->withErrors(['payment' => 'Unable to initiate payment. Please try again.']);
        }

        $this->logGateway($payment, 'initiate', 'request', [
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'method' => $payment->payment_method,
        ]);
        $gatewayResponse = $this->createShurjoPayPayment($tokenData, $request, $booking, $payment);
        $this->logGateway($payment, 'initiate', 'response', $gatewayResponse);
        $payment->update(['gateway_response' => $gatewayResponse]);

        Log::info('Payment initiation response', [
            'transaction_id' => $transactionId,
            'response' => $gatewayResponse,
        ]);

        $redirectUrl = $gatewayResponse['checkout_url'] ?? $gatewayResponse['redirect_url'] ?? null;
        if (!$redirectUrl) {
            $payment->update(['status' => 'failed']);
            return back()->withErrors(['payment' => 'Unable to initiate payment. Please try again.']);
        }

        return redirect()->away($redirectUrl);
    }

    public function success(Request $request)
    {
        return $this->handleGatewayCallback($request, 'success');
    }

    public function fail(Request $request)
    {
        return $this->handleGatewayCallback($request, 'failed');
    }

    public function cancel(Request $request)
    {
        return $this->handleGatewayCallback($request, 'cancelled');
    }

    public function ipn(Request $request)
    {
        return $this->handleGatewayCallback($request, 'ipn');
    }

    public function invoice(Payment $payment)
    {
        $payment->loadMissing(['booking', 'user']);
        $this->authorizeBooking($payment->booking);

        $pdf = Pdf::loadView('payments.invoice', [
            'payment' => $payment,
            'booking' => $payment->booking,
            'user' => $payment->user,
        ]);

        return $pdf->download("invoice-{$payment->transaction_id}.pdf");
    }

    private function handleGatewayCallback(Request $request, string $type)
    {
        $transactionId = $request->input('order_id')
            ?? $request->input('sp_order_id')
            ?? $request->input('order')
            ?? $request->input('orderID');
        $payment = Payment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            Log::warning('Payment callback without matching transaction', [
                'type' => $type,
                'payload' => $request->all(),
            ]);
            return redirect('/')->withErrors(['payment' => 'Payment record not found.']);
        }

        if ($payment->status === 'success') {
            return redirect()->route('payment.checkout', $payment->booking_id)
                ->with('success', 'Payment already confirmed.');
        }

        $payment->update([
            'gateway_response' => array_merge($payment->gateway_response ?? [], [
                'callback_type' => $type,
                'payload' => $request->all(),
            ]),
        ]);
        $this->logGateway($payment, 'callback', 'request', [
            'type' => $type,
            'payload' => $request->all(),
        ]);

        if ($type === 'failed' || $type === 'cancelled') {
            $payment->update(['status' => 'failed']);
            return redirect()->route('payment.checkout', $payment->booking_id)
                ->withErrors(['payment' => 'Payment was not completed. Please try again.']);
        }

        $validation = $this->verifyShurjoPayPayment($payment->transaction_id);
        $this->logGateway($payment, 'verify', 'response', $validation);

        $statusCode = (string) ($validation['sp_code'] ?? '');
        $statusText = strtolower((string) ($validation['transaction_status'] ?? $validation['status'] ?? ''));
        $isValid = in_array($statusCode, ['000', '00'], true) || in_array($statusText, ['success', 'successful'], true);
        $amountMatches = isset($validation['amount'])
            ? (float) $validation['amount'] === (float) $payment->amount
            : true;
        $currencyMatches = isset($validation['currency'])
            ? strtoupper($validation['currency']) === strtoupper($payment->currency)
            : true;

        if (!$isValid || !$amountMatches || !$currencyMatches) {
            $payment->update(['status' => 'failed']);
            if ($type === 'ipn') {
                return response('INVALID', 400);
            }
            return redirect()->route('payment.checkout', $payment->booking_id)
                ->withErrors(['payment' => 'Payment verification failed.']);
        }

        $payment->update([
            'status' => 'success',
            'gateway_response' => array_merge($payment->gateway_response ?? [], ['validation' => $validation]),
        ]);

        $payment->booking()->update(['status' => 'confirmed']);

        $this->sendConfirmationEmail($payment);

        if ($type === 'ipn') {
            return response('OK');
        }

        return redirect()->route('payment.checkout', $payment->booking_id)
            ->with('success', 'Payment completed successfully.');
    }

    private function createShurjoPayPayment(array $tokenData, Request $request, Booking $booking, Payment $payment): array
    {
        $payload = [
            'token' => $tokenData['token'] ?? null,
            'store_id' => $tokenData['store_id'] ?? config('services.shurjopay.store_id'),
            'prefix' => config('services.shurjopay.prefix', 'TN'),
            'currency' => $payment->currency,
            'amount' => $payment->amount,
            'order_id' => $payment->transaction_id,
            'return_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
            'client_ip' => $request->ip(),
            'customer_name' => $request->user()->name,
            'customer_phone' => $request->user()->phone ?? '01700000000',
            'customer_email' => $request->user()->email,
            'customer_address' => 'Dhaka',
            'customer_city' => 'Dhaka',
            'customer_country' => 'Bangladesh',
            'shipping_method' => 'NO',
            'product_name' => $booking->package_name,
            'product_category' => 'Tour Package',
            'product_profile' => 'general',
            'value1' => $payment->payment_method,
        ];

        $this->logGateway($payment, 'initiate', 'request', $payload);

        return $this->shurjoPay->createPayment($payload) ?? [];
    }

    private function verifyShurjoPayPayment(string $orderId): ?array
    {
        $tokenData = $this->shurjoPay->getToken();
        if (!$tokenData) {
            return null;
        }

        $this->logGateway(null, 'verify', 'request', ['order_id' => $orderId]);
        return $this->shurjoPay->verifyPayment([
            'token' => $tokenData['token'] ?? null,
            'store_id' => $tokenData['store_id'] ?? config('services.shurjopay.store_id'),
            'order_id' => $orderId,
        ]);
    }

    private function authorizeBooking(Booking $booking): void
    {
        $user = auth()->user();
        $isAdmin = $user && ($user->hasRole('Admin') || ($user->role ?? null) === 'admin' || ($user->is_admin ?? false));

        if (!$isAdmin && $booking->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function sendConfirmationEmail(Payment $payment): void
    {
        SendPaymentConfirmationJob::dispatch($payment->id);
    }

    private function logGateway(?Payment $payment, string $type, string $direction, ?array $payload = null): void
    {
        try {
            PaymentLog::create([
                'payment_id' => $payment?->id,
                'type' => $type,
                'direction' => $direction,
                'payload' => $payload,
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Payment log failed', [
                'type' => $type,
                'direction' => $direction,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
