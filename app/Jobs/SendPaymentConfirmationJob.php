<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPaymentConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $paymentId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payment = Payment::query()
            ->with(['booking', 'user'])
            ->find($this->paymentId);

        if (!$payment || !$payment->user) {
            Log::warning('Payment confirmation job missing payment or user.', [
                'payment_id' => $this->paymentId,
            ]);
            return;
        }

        try {
            Mail::raw(
                "Your payment {$payment->transaction_id} has been confirmed for {$payment->booking->package_name}.",
                function ($message) use ($payment) {
                    $message->to($payment->user->email)
                        ->subject('TravelNest Payment Confirmation');
                }
            );
        } catch (\Throwable $exception) {
            Log::warning('Payment confirmation email failed', [
                'payment_id' => $payment->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
