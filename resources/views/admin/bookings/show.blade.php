@extends('layouts.admin')

@section('title', 'Booking')
@section('page-title', 'Booking Details')

@section('content')
<div class="tn-card">
    <div class="tn-card-header">
        <h3>Booking {{ $booking->booking_reference }}</h3>
    </div>
    <div class="p-3">
        <dl class="row">
            <dt class="col-sm-3">Customer</dt>
            <dd class="col-sm-9">{{ $booking->user?->name ?? 'Guest' }}</dd>

            <dt class="col-sm-3">Agency</dt>
            <dd class="col-sm-9">{{ $booking->agency?->name ?? $booking->package?->agency?->name ?? '-' }}</dd>

            <dt class="col-sm-3">Package</dt>
            <dd class="col-sm-9">{{ $booking->package?->name ?? $booking->package_name ?? '-' }}</dd>

            <dt class="col-sm-3">Travel Date</dt>
            <dd class="col-sm-9">{{ $booking->travel_date?->format('M d, Y') ?? '-' }}</dd>

            <dt class="col-sm-3">Amount</dt>
            <dd class="col-sm-9">{{ number_format($booking->amount,2) }} {{ $booking->currency }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">{{ ucfirst($booking->status) }}</dd>

            <dt class="col-sm-3">Created At</dt>
            <dd class="col-sm-9">{{ $booking->created_at?->format('M d, Y H:i') }}</dd>
        </dl>

        <a href="{{ route('admin.bookings') }}" class="btn-outline-tn">Back to bookings</a>
    </div>
</div>
@endsection
