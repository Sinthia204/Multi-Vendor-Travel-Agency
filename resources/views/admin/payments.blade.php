{{-- Use the shared admin layout for consistent sidebar/header. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Payments Management')
{{-- Admin header title. --}}
@section('page-title', 'Payments Management')

@section('content')
    {{-- Filter payments by status using query parameters. --}}
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.payments') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <select class="tn-form-control" name="status" style="width:180px;">
                <option value="">All Status</option>
                @foreach (['success' => 'Success', 'pending' => 'Pending', 'failed' => 'Failed'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            {{-- Submit the filter form. --}}
            <button class="btn-outline-tn" type="submit">Filter</button>
            <a class="btn-outline-tn" href="{{ route('admin.payments') }}">Reset</a>
        </div>
    </form>

    <div class="tn-card-static">
        <div style="overflow-x:auto;">
            <table class="tn-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Booking</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop through payment records provided by the controller. --}}
                    @forelse ($payments as $payment)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <strong>{{ $payment->user->name }}</strong>
                                    <span class="text-muted-tn" style="font-size:12px;">{{ $payment->user->email }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $payment->booking->package_name }}</span>
                                    <span class="text-muted-tn"
                                        style="font-size:12px;">{{ $payment->booking->booking_reference }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                            <td>{{ strtoupper($payment->payment_method) }}</td>
                            <td>
                                <span
                                    class="tn-badge tn-badge-{{ $payment->status === 'success' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->transaction_id }}</td>
                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    {{-- View gateway response and logs in a modal. --}}
                                    <button class="btn-outline-tn btn-sm-tn" type="button" data-bs-toggle="modal"
                                        data-bs-target="#paymentDetailModal" data-payment='@json($payment->gateway_response)'
                                        data-logs='@json($payment->logs)'
                                        data-transaction="{{ $payment->transaction_id }}">
                                        View Details
                                    </button>
                                    @if ($payment->status !== 'success')
                                        {{-- Retry sends admin to the public checkout for this booking. --}}
                                        <a class="btn-primary-tn btn-sm-tn"
                                            href="{{ route('payment.checkout', $payment->booking_id) }}">
                                            Retry
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($payments->hasPages())
        <nav class="mt-4">
            <ul class="pagination">
                <li class="page-item {{ $payments->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $payments->previousPageUrl() ?? '#' }}">Previous</a>
                </li>
                <li class="page-item {{ $payments->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $payments->nextPageUrl() ?? '#' }}">Next</a>
                </li>
            </ul>
        </nav>
    @endif

    {{-- Modal that renders the raw gateway response and log history. --}}
    <div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Transaction ID:</strong> <span id="paymentTransaction"></span>
                    </div>
                    <pre class="bg-light p-3 rounded" id="paymentGatewayResponse" style="max-height:300px;overflow:auto;"></pre>
                    <div class="mt-3">
                        <strong>Gateway Logs</strong>
                        <pre class="bg-light p-3 rounded" id="paymentGatewayLogs" style="max-height:300px;overflow:auto;"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const paymentModal = document.getElementById('paymentDetailModal');
        if (paymentModal) {
            paymentModal.addEventListener('show.bs.modal', (event) => {
                const button = event.relatedTarget;
                const transaction = button.getAttribute('data-transaction');
                const response = button.getAttribute('data-payment');
                const logs = button.getAttribute('data-logs');

                // Populate modal fields with gateway data for the selected payment.
                paymentModal.querySelector('#paymentTransaction').textContent = transaction;
                paymentModal.querySelector('#paymentGatewayResponse').textContent = response || '{}';
                const parsedLogs = logs ? JSON.parse(logs) : [];
                paymentModal.querySelector('#paymentGatewayLogs').textContent = JSON.stringify(parsedLogs, null, 2);
            });
        }
    </script>
@endsection
