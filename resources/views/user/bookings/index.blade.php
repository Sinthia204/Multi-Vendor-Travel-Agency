@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
    <section class="my-bookings-page">
        <div class="container my-5">
            {{-- Page Header --}}
            <div class="mb-5">
                <h1 class="mb-2">
                    <i class="fas fa-book me-2 text-primary"></i>My Bookings
                </h1>
                <p class="text-muted">View and manage all your travel bookings and payments</p>
            </div>

            {{-- No Bookings State --}}
            @if ($bookings->isEmpty())
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                    <h5>No bookings yet</h5>
                    <p class="mb-0">Start exploring our packages and make your first booking today!</p>
                </div>
            @else
                {{-- Bookings List --}}
                <div class="row">
                    @foreach ($bookings as $booking)
                        <div class="col-lg-6 col-xxl-4 mb-4">
                            <div class="card shadow-sm h-100 booking-card">
                                {{-- Card Header with Status Badges --}}
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title mb-1">{{ $booking->package_name }}</h6>
                                            <small class="text-muted">Ref: {{ $booking->booking_reference }}</small>
                                        </div>
                                        <div class="text-end">
                                            {{-- Booking Status Badge --}}
                                            @if ($booking->status === 'confirmed')
                                                <span class="badge bg-success d-block mb-1">
                                                    <i class="fas fa-check-circle me-1"></i>Confirmed
                                                </span>
                                            @elseif ($booking->status === 'pending')
                                                <span class="badge bg-warning d-block mb-1">
                                                    <i class="fas fa-hourglass-half me-1"></i>Pending
                                                </span>
                                            @else
                                                <span class="badge bg-danger d-block mb-1">
                                                    <i class="fas fa-times-circle me-1"></i>{{ ucfirst($booking->status) }}
                                                </span>
                                            @endif

                                            {{-- Payment Status Badge --}}
                                            @php
                                                $payment = $booking->payments()->latest()->first();
                                                $paymentStatus = $payment?->status ?? 'unpaid';
                                            @endphp
                                            @if ($paymentStatus === 'paid')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-credit-card me-1"></i>Paid
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Unpaid</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="card-body">
                                    {{-- Agency Information --}}
                                    <div class="mb-3 pb-3 border-bottom">
                                        <small class="text-muted">Agency</small>
                                        <p class="mb-0">{{ $booking->agency?->name ?? 'N/A' }}</p>
                                    </div>

                                    {{-- Booking Details --}}
                                    <div class="mb-3">
                                        <div class="row small">
                                            <div class="col-6 mb-2">
                                                <span class="text-muted">Travel Date</span>
                                                <p class="mb-0">{{ $booking->travel_date?->format('d M Y') ?? 'Flexible' }}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <span class="text-muted">Booking Date</span>
                                                <p class="mb-0">{{ $booking->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Amount Information --}}
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Total Amount</small>
                                            <strong class="text-primary">
                                                {{ number_format(max($booking->amount - $booking->discount_amount, 0), 2) }}
                                                {{ $booking->currency ?? 'BDT' }}
                                            </strong>
                                        </div>
                                        @if ($booking->discount_amount > 0)
                                            <small class="text-success">
                                                ({{ number_format($booking->discount_amount, 2) }} discount applied)
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                {{-- Card Footer with Actions --}}
                                <div class="card-footer bg-light">
                                    <div class="d-grid gap-2">
                                        @if ($payment && $payment->status === 'paid')
                                            {{-- If payment is completed, show payment details link --}}
                                            <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-receipt me-1"></i>View Payment Details
                                            </a>
                                        @else
                                            {{-- If no payment or unpaid, show process payment link --}}
                                            <a href="{{ route('payment.checkout', $booking->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-credit-card me-1"></i>Process Payment
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

<style>
    .my-bookings-page {
        min-height: 80vh;
        background-color: #f8f9fa;
    }

    .booking-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }

    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .booking-card .card-header {
        border-bottom: 1px solid #dee2e6;
    }

    .booking-card .card-footer {
        border-top: 1px solid #dee2e6;
    }
</style>
