@extends('layouts.admin')

@section('title', 'Payment')
@section('page-title', 'Payment Details')

@section('content')
<div class="tn-card">
    <div class="tn-card-header">
        <h3>Payment #{{ $payment->id }}</h3>
    </div>
    <div class="p-3">
        <dl class="row">
            <dt class="col-sm-3">Transaction ID</dt>
            <dd class="col-sm-9">{{ $payment->transaction_id }}</dd>

            <dt class="col-sm-3">Booking</dt>
            <dd class="col-sm-9">{{ $payment->booking?->booking_reference ?? '-' }}</dd>

            <dt class="col-sm-3">Customer</dt>
            <dd class="col-sm-9">{{ $payment->booking?->user?->name ?? $payment->user?->name ?? 'Guest' }}</dd>

            <dt class="col-sm-3">Agency / Package</dt>
            <dd class="col-sm-9">{{ $payment->booking?->package?->agency?->name ?? '-' }} / {{ $payment->booking?->package?->name ?? $payment->booking?->package_name ?? '-' }}</dd>

            <dt class="col-sm-3">Method</dt>
            <dd class="col-sm-9">{{ $payment->payment_method }}</dd>

            <dt class="col-sm-3">Amount</dt>
            <dd class="col-sm-9">{{ number_format($payment->amount,2) }} {{ $payment->currency }}</dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">{{ ucfirst($payment->status) }}</dd>

            <dt class="col-sm-3">Created At</dt>
            <dd class="col-sm-9">{{ $payment->created_at?->format('M d, Y H:i') }}</dd>
        </dl>

        <a href="{{ route('admin.payments') }}" class="btn-outline-tn">Back to payments</a>
    </div>
</div>
@endsection
