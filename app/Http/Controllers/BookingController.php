<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function storeFromPackage(Request $request)
    {
        $data = $request->validate([
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
            'package_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'travel_date' => ['nullable', 'date'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ]);

        $package = null;
        if (!empty($data['package_id'])) {
            $package = Package::with('agency')->find($data['package_id']);
        }

        $amount = $package?->price ?? $data['amount'];
        $couponData = $this->applyCoupon($amount, $data['coupon_code'] ?? null);
        if ($couponData['invalid']) {
            return back()->withErrors(['coupon_code' => 'Invalid or expired coupon code.'])->withInput();
        }

        $bookingReference = $this->generateReference();

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'agency_id' => $package?->agency_id,
            'package_id' => $package?->id,
            'booking_reference' => $bookingReference,
            'package_name' => $package?->name ?? $data['package_name'],
            'travel_date' => $data['travel_date'] ?? null,
            'amount' => $amount,
            'discount_amount' => $couponData['discount'],
            'coupon_code' => $couponData['code'],
            'currency' => 'BDT',
            'status' => 'pending',
        ]);

        return redirect()->route('payment.checkout', $booking);
    }

    public function updateCoupon(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $data = $request->validate([
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ]);

        $code = $data['coupon_code'] ?? null;
        $couponData = $this->applyCoupon($booking->amount, $code);
        if ($couponData['invalid']) {
            return back()->withErrors(['coupon_code' => 'Invalid or expired coupon code.'])->withInput();
        }

        $booking->update([
            'discount_amount' => $couponData['discount'],
            'coupon_code' => $couponData['code'],
        ]);

        $message = $couponData['code'] ? 'Coupon applied successfully.' : 'Coupon removed successfully.';
        return back()->with('coupon_success', $message);
    }

    private function generateReference(): string
    {
        do {
            $reference = 'BK-' . strtoupper(Str::random(6));
        } while (Booking::where('booking_reference', $reference)->exists());

        return $reference;
    }

    private function authorizeBooking(Booking $booking): void
    {
        $user = auth()->user();
        $isAdmin = $user && (($user->role ?? null) === 'admin' || ($user->is_admin ?? false));

        if (!$isAdmin && $booking->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function applyCoupon(float $amount, ?string $code): array
    {
        if (!$code) {
            return ['discount' => 0, 'code' => null, 'invalid' => false];
        }

        $coupon = Coupon::query()
            ->where('code', strtoupper($code))
            ->where('active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$coupon) {
            return ['discount' => 0, 'code' => null, 'invalid' => true];
        }

        $discount = 0;
        if ($coupon->type === 'fixed') {
            $discount = min($amount, (float) $coupon->value);
        } else {
            $discount = ($amount * (float) $coupon->value) / 100;
        }

        return [
            'discount' => round($discount, 2),
            'code' => $coupon->code,
            'invalid' => false,
        ];
    }
}
