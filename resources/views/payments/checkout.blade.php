@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
    <section class="payment-page">
        <div class="container">
            <div class="payment-header">
                <div>
                    <span class="section-tag">Secure checkout</span>
                    <h1 class="payment-title">Confirm and pay</h1>
                    <p class="payment-subtitle">Complete your booking with a secure payment powered by ShurjoPay.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="payment-alert success">{{ session('success') }}</div>
            @endif

            @if ($errors->has('payment'))
                <div class="payment-alert error">{{ $errors->first('payment') }}</div>
            @endif

            <div class="payment-grid">
                @php
                    $bkashEnabled = getSetting('payment_bkash_enabled', '1') === '1';
                    $nagadEnabled = getSetting('payment_nagad_enabled', '1') === '1';
                    $cardEnabled = getSetting('payment_card_enabled', '1') === '1';
                    $availableMethods = [];

                    if ($bkashEnabled) {
                        $availableMethods[] = 'bkash';
                    }
                    if ($nagadEnabled) {
                        $availableMethods[] = 'nagad';
                    }
                    if ($cardEnabled) {
                        $availableMethods[] = 'card';
                    }

                    $availableMethods[] = 'rocket';
                    $defaultMethod = $availableMethods[0] ?? 'rocket';
                @endphp

                <div class="payment-card">
                    <h3>Booking Summary</h3>
                    <div class="payment-summary">
                        <div class="summary-row">
                            <span>Booking Ref</span>
                            <strong>{{ $booking->booking_reference }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Package</span>
                            <strong>{{ $booking->package_name }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Travel Date</span>
                            <strong>{{ $booking->travel_date?->format('M d, Y') ?? 'Flexible' }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Status</span>
                            <strong class="status-pill {{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                {{ ucfirst($booking->status) }}
                            </strong>
                        </div>
                    </div>

                    <div class="price-breakdown">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <strong>{{ number_format($booking->amount, 2) }} {{ $booking->currency }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Discount</span>
                            <strong>-{{ number_format($booking->discount_amount, 2) }} {{ $booking->currency }}</strong>
                        </div>
                        @if ($booking->coupon_code)
                            <div class="summary-row">
                                <span>Coupon</span>
                                <strong class="coupon-pill"><i class="fas fa-tag"></i>{{ $booking->coupon_code }}</strong>
                            </div>
                        @endif
                        <div class="summary-total">
                            <span>Total</span>
                            <strong>{{ number_format(max($booking->amount - $booking->discount_amount, 0), 2) }}
                                {{ $booking->currency }}</strong>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('bookings.coupon', $booking) }}" class="payment-coupon">
                        @csrf
                        @method('PUT')
                        <label class="coupon-label" for="coupon_code">Have a coupon?</label>
                        <div class="coupon-row">
                            <input id="coupon_code" type="text" name="coupon_code" class="coupon-input"
                                value="{{ old('coupon_code', $booking->coupon_code) }}" placeholder="Enter code">
                            <button class="btn-outline-tn" type="submit">Apply</button>
                            @if ($booking->coupon_code)
                                <button class="btn-outline-tn" type="submit" name="coupon_code"
                                    value="">Remove</button>
                            @endif
                        </div>
                        @if (session('coupon_success'))
                            <div class="payment-alert success" style="margin-top:0.75rem;">{{ session('coupon_success') }}
                            </div>
                        @endif
                        @error('coupon_code')
                            <div class="payment-alert error" style="margin-top:0.75rem;">{{ $message }}</div>
                        @enderror
                    </form>

                    @if ($latestPayment)
                        <div class="payment-history">
                            <div class="summary-row">
                                <span>Last Attempt</span>
                                <strong>{{ $latestPayment->created_at->format('M d, Y H:i') }}</strong>
                            </div>
                            <div class="summary-row">
                                <span>Status</span>
                                <strong
                                    class="status-pill {{ $latestPayment->status === 'success' ? 'success' : 'warning' }}">
                                    {{ ucfirst($latestPayment->status) }}
                                </strong>
                            </div>
                            @if ($latestPayment->status === 'failed')
                                <div class="payment-note" style="margin-top:0.75rem;">
                                    <i class="fas fa-rotate-right"></i>
                                    You can retry the payment with another method.
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="payment-card">
                    <h3>Select Payment Method</h3>
                    <form method="POST" action="{{ route('payment.initiate') }}" class="payment-form" data-payment-form>
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" name="payment_method" value="{{ $defaultMethod }}" data-payment-method>

                        <div class="payment-methods">
                            @if ($bkashEnabled)
                                <button type="button"
                                    class="method-button {{ $defaultMethod === 'bkash' ? 'active' : '' }}"
                                    data-method="bkash">
                                    <span class="method-label">bKash</span>
                                    <span class="method-tag bkash">Mobile Banking</span>
                                </button>
                            @endif
                            @if ($nagadEnabled)
                                <button type="button"
                                    class="method-button {{ $defaultMethod === 'nagad' ? 'active' : '' }}"
                                    data-method="nagad">
                                    <span class="method-label">Nagad</span>
                                    <span class="method-tag nagad">Mobile Banking</span>
                                </button>
                            @endif
                            <button type="button" class="method-button" data-method="upai">
                                <span class="method-label">Upai</span>
                                <span class="method-tag upai">Mobile Banking</span>
                            </button>
                            <button type="button" class="method-button" data-method="bkash">
                                <span class="method-label">Bkash</span>
                                <span class="method-tag bkash">Mobile Banking</span>
                            </button>
                            <button type="button" class="method-button" data-method="rocket">
                                <span class="method-label">Rocket</span>
                                <span class="method-tag rocket">Mobile Banking</span>
                            </button>

                            @if ($cardEnabled)
                                <button type="button"
                                    class="method-button {{ $defaultMethod === 'card' ? 'active' : '' }}"
                                    data-method="card">
                                    <span class="method-label">Visa / Mastercard</span>
                                    <span class="method-tag card">Card Payment</span>
                                </button>
                            @endif
                            @if (!app()->environment('production'))
                                <button type="button" class="method-button" data-method="demo">
                                    <span class="method-label">Demo Payment</span>
                                    <span class="method-tag card">Local</span>
                                </button>
                            @endif
                        </div>

                        <div class="payment-note">
                            <i class="fas fa-lock"></i>
                            Payments are processed securely via ShurjoPay sandbox.
                        </div>
                        @if (!app()->environment('production'))
                            <div class="payment-note">
                                <i class="fas fa-flask"></i>
                                Demo Payment completes instantly without contacting the gateway.
                            </div>
                        @endif

                        <button type="submit" class="btn-primary payment-submit">
                            Pay {{ number_format(max($booking->amount - $booking->discount_amount, 0), 2) }}
                            {{ $booking->currency }}
                        </button>
                    </form>

                    {{-- Simple Payment Method Alternative --}}
                    <div class="payment-divider mt-4 mb-4">
                        <span>Or pay using</span>
                    </div>

                    <div class="payment-methods-simple">
                        <form method="POST" action="{{ route('payment.process') }}" class="simple-payment-form w-100">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <input type="hidden" name="payment_method" value="direct">

                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Quick Payment:</strong> Click below to complete your payment instantly
                            </div>

                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-check-circle me-2"></i>
                                Complete Payment Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const methodButtons = document.querySelectorAll('[data-method]');
        const methodInput = document.querySelector('[data-payment-method]');
        const couponInput = document.getElementById('coupon_code');

        methodButtons.forEach((button) => {
            button.addEventListener('click', () => {
                methodButtons.forEach((btn) => btn.classList.remove('active'));
                button.classList.add('active');
                methodInput.value = button.dataset.method;
            });
        });

        if (couponInput) {
            couponInput.addEventListener('input', () => {
                couponInput.value = couponInput.value.toUpperCase();
            });
        }
    </script>
@endsection
