@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
    <section class="payment-details-page">
        <div class="container my-5">
            {{-- Success Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Info Alert --}}
            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                {{-- Payment Details Card --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Payment Details
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Payment Status Badge --}}
                            <div class="mb-4">
                                <label class="text-muted small">Status</label>
                                <div>
                                    @if ($payment->status === 'paid')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Paid
                                        </span>
                                    @elseif ($payment->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-hourglass-half me-1"></i>Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>{{ ucfirst($payment->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            {{-- Payment Reference Information --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Transaction ID</label>
                                    <p class="font-monospace">{{ $payment->transaction_id }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Reference No</label>
                                    <p class="font-monospace">{{ $payment->reference_no ?? 'N/A' }}</p>
                                </div>
                            </div>

                            {{-- Payment Method and Amount --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Payment Method</label>
                                    <p>
                                        @switch($payment->payment_method)
                                            @case('card')
                                                <i class="fas fa-credit-card me-1 text-primary"></i>Credit/Debit Card
                                            @break

                                            @case('bank')
                                                <i class="fas fa-university me-1 text-primary"></i>Bank Transfer
                                            @break

                                            @case('cash')
                                                <i class="fas fa-money-bill me-1 text-success"></i>Cash Payment
                                            @break

                                            @case('demo')
                                                <i class="fas fa-flask me-1 text-info"></i>Demo Payment
                                            @break

                                            @default
                                                {{ ucfirst($payment->payment_method) }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Amount Paid</label>
                                    <p>
                                        <strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency ?? 'BDT' }}</strong>
                                    </p>
                                </div>
                            </div>

                            {{-- Payment Date --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Payment Date</label>
                                    <p>{{ $payment->paid_at?->format('d M Y, h:i A') ?? 'Pending' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Booking Confirmation</label>
                                    <p>
                                        @if ($payment->booking->status === 'confirmed')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Confirmed
                                            </span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Booking Details Card --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-plane-departure me-2"></i>Booking Details
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Booking Reference --}}
                            <div class="mb-3">
                                <label class="text-muted small">Booking Reference</label>
                                <p class="font-monospace fs-5 fw-bold">{{ $payment->booking->booking_reference }}</p>
                            </div>

                            {{-- Package Information --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Package Name</label>
                                    <p>{{ $payment->booking->package_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Travel Date</label>
                                    <p>{{ $payment->booking->travel_date?->format('d M Y') ?? 'Flexible' }}</p>
                                </div>
                            </div>

                            {{-- Agency Information --}}
                            <div class="mb-3">
                                <label class="text-muted small">Travel Agency</label>
                                <p>{{ $payment->booking->agency?->name ?? 'N/A' }}</p>
                            </div>

                            {{-- Customer Information --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Customer Name</label>
                                    <p>{{ $payment->user->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Customer Email</label>
                                    <p>{{ $payment->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Summary Sidebar --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Payment Summary</h5>
                        </div>
                        <div class="card-body">
                            {{-- Amount Breakdown --}}
                            <div class="summary-item d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>{{ number_format($payment->booking->amount, 2) }} {{ $payment->currency ?? 'BDT' }}</span>
                            </div>

                            @if ($payment->booking->discount_amount > 0)
                                <div class="summary-item d-flex justify-content-between mb-2 text-success">
                                    <span>Discount:</span>
                                    <span>-{{ number_format($payment->booking->discount_amount, 2) }} {{ $payment->currency ?? 'BDT' }}</span>
                                </div>
                            @endif

                            @if ($payment->booking->coupon_code)
                                <div class="summary-item d-flex justify-content-between mb-3">
                                    <span class="badge bg-info">{{ $payment->booking->coupon_code }}</span>
                                </div>
                            @endif

                            <hr>

                            <div class="summary-total d-flex justify-content-between mb-3">
                                <strong>Total Amount:</strong>
                                <strong class="text-primary fs-5">
                                    {{ number_format($payment->amount, 2) }} {{ $payment->currency ?? 'BDT' }}
                                </strong>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-grid gap-2">
                                <a href="{{ route('user.bookings.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-book me-1"></i>View My Bookings
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Info Card --}}
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="fas fa-info-circle me-2 text-info"></i>What's Next?
                            </h6>
                            <ul class="small mb-0">
                                <li>Your booking is confirmed</li>
                                <li>A confirmation email has been sent</li>
                                <li>You can view all your bookings anytime</li>
                                <li>Contact support for any questions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    .payment-details-page {
        min-height: 80vh;
        padding-top: 6rem; /* offset fixed navbar so header isn't cut off */
    }

    .font-monospace {
        font-family: 'Courier New', monospace;
    }

    .summary-item {
        font-size: 0.95rem;
    }

    .summary-total {
        font-size: 1.1rem;
        padding: 0.5rem 0;
    }
</style>
