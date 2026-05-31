{{-- Use the shared admin layout for consistent UI. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Bookings Management')
{{-- Admin header title. --}}
@section('page-title', 'Bookings Management')

@section('content')
    <!-- Toolbar with search, date, and filter controls. -->
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.bookings') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="tn-search-input" style="width:280px;">
                <i class="fas fa-search"></i>
                <input type="text" class="tn-form-control" name="search" value="{{ $search }}"
                    placeholder="Search bookings...">
            </div>
            <input type="date" class="tn-form-control" name="date" value="{{ $dateFilter }}" style="width:160px;">
            <select class="tn-form-control" name="status" style="width:150px;">
                <option value="all" @selected($statusFilter === 'all')>All Status</option>
                <option value="confirmed" @selected($statusFilter === 'confirmed')>Confirmed</option>
                <option value="pending" @selected($statusFilter === 'pending')>Pending</option>
                <option value="cancelled" @selected($statusFilter === 'cancelled')>Cancelled</option>
            </select>
            <select class="tn-form-control" name="agency" style="width:160px;">
                <option value="all" @selected($agencyFilter === 'all')>All Agencies</option>
                @foreach ($agencies as $agency)
                    <option value="{{ $agency->id }}" @selected((string) $agencyFilter === (string) $agency->id)>
                        {{ $agency->name }}</option>
                @endforeach
            </select>
            <button class="btn-outline-tn" type="submit">Filter</button>
        </div>
        <a class="btn-outline-tn"
            href="{{ route('admin.bookings.export', ['search' => $search, 'date' => $dateFilter, 'status' => $statusFilter, 'agency' => $agencyFilter]) }}">
            <i class="fas fa-file-csv me-1"></i> Export CSV</a>
    </form>

    <!-- Summary cards. -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted-tn mb-1" style="font-size:13px;">Total Bookings</p>
                        <div style="font-family:'Space Grotesk';font-weight:700;font-size:20px;" id="totalBookings">
                            {{ number_format($totalCount) }}</div>
                    </div>
                    <div class="stat-icon stat-icon-primary"><i class="fas fa-calendar-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted-tn mb-1" style="font-size:13px;">Confirmed</p>
                        <div style="font-family:'Space Grotesk';font-weight:700;font-size:20px;" id="confirmedBookings">
                            {{ number_format($statusCounts->get('confirmed', 0)) }}</div>
                    </div>
                    <div class="stat-icon stat-icon-success"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted-tn mb-1" style="font-size:13px;">Pending</p>
                        <div style="font-family:'Space Grotesk';font-weight:700;font-size:20px;" id="pendingBookings">
                            {{ number_format($statusCounts->get('pending', 0)) }}</div>
                    </div>
                    <div class="stat-icon stat-icon-warning"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted-tn mb-1" style="font-size:13px;">Cancelled</p>
                        <div style="font-family:'Space Grotesk';font-weight:700;font-size:20px;" id="cancelledBookings">
                            {{ number_format($statusCounts->get('cancelled', 0)) }}</div>
                    </div>
                    <div class="stat-icon stat-icon-danger"><i class="fas fa-ban"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings table. -->
    <div class="card-tn">
        <div class="card-body p-0">
            <div class="tn-table-wrap">
                <table class="tn-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Package</th>
                            <th>Agency</th>
                            <th>Travel Date</th>
                            <th>Amount</th>
                            <th>Booking Status</th>
                            <th>Payment Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            @php
                                // Get latest payment for this booking
                                $payment = $booking->payments()->latest()->first();
                                $paymentStatus = $payment?->status ?? 'unpaid';
                            @endphp
                            <tr data-booking-id="{{ $booking->booking_reference }}"
                                data-customer="{{ $booking->user?->name }}" data-email="{{ $booking->user?->email }}"
                                data-package="{{ $booking->package_name }}"
                                data-agency="{{ $booking->agency?->name }}"
                                data-travel-date="{{ optional($booking->travel_date)->format('Y-m-d') }}"
                                data-amount="{{ $booking->amount }}" data-status="{{ $booking->status }}"
                                data-payment-status="{{ $paymentStatus }}"
                                data-update-url="{{ route('admin.bookings.status', $booking) }}">
                                <td><span class="booking-id">{{ $booking->booking_reference }}</span></td>
                                <td>{{ $booking->user?->name }}</td>
                                <td>{{ $booking->package_name }}</td>
                                <td>{{ $booking->agency?->name ?? '-' }}</td>
                                <td>{{ optional($booking->travel_date)->format('M d, Y') }}</td>
                                <td style="font-weight:700;">${{ number_format($booking->amount, 0) }}</td>
                                <td><span
                                        class="tn-badge tn-badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($booking->status) }}</span>
                                </td>
                                <td>
                                    @if ($paymentStatus === 'paid')
                                        <span class="tn-badge tn-badge-success">
                                            <i class="fas fa-credit-card me-1"></i>Paid
                                        </span>
                                    @else
                                        <span class="tn-badge tn-badge-warning">
                                            <i class="fas fa-hourglass-half me-1"></i>Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="tn-action-btn" data-bs-toggle="dropdown"><i
                                                class="fas fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" data-action="view"><i
                                                        class="fas fa-eye me-2"></i>View Details</a></li>
                                            <li><a class="dropdown-item" href="#" data-action="status"><i
                                                        class="fas fa-edit me-2"></i>Update Status</a></li>
                                            <li><a class="dropdown-item" href="mailto:{{ $booking->user?->email }}"><i
                                                        class="fas fa-envelope me-2"></i>Send Email</a></li>
                                            @if ($booking->status !== 'cancelled')
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form method="POST"
                                                        action="{{ route('admin.bookings.status', $booking) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button class="dropdown-item text-danger" type="submit"><i
                                                                class="fas fa-ban me-2"></i>Cancel</button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-muted-tn" style="padding:1.5rem;">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center p-3">
                <span class="text-muted-tn" style="font-size:14px;">Showing {{ $bookings->firstItem() ?? 0 }} to
                    {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} bookings</span>
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingDetailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-family:'Space Grotesk';font-weight:600;">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Booking ID</span>
                            <span id="detailBookingId">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Customer</span>
                            <span id="detailCustomerName">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Email</span>
                            <span id="detailCustomerEmail">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Package</span>
                            <span id="detailPackage">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Agency</span>
                            <span id="detailAgency">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Travel Date</span>
                            <span id="detailTravelDate">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Amount</span>
                            <span id="detailAmount">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Booking Status</span>
                            <span id="detailStatus" class="tn-badge tn-badge-success">Confirmed</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Payment Status</span>
                            <span id="detailPaymentStatus" class="tn-badge tn-badge-warning">Unpaid</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline-tn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="bookingStatusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-family:'Space Grotesk';font-weight:600;">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="bookingStatusForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <label class="tn-form-label" for="bookingStatusSelect">Status</label>
                        <select class="tn-form-control" id="bookingStatusSelect" name="status">
                            <option value="confirmed">Confirmed</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-outline-tn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-tn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const detailModalElement = document.getElementById('bookingDetailModal');
            const detailModal = detailModalElement ? new bootstrap.Modal(detailModalElement) : null;
            const statusModalElement = document.getElementById('bookingStatusModal');
            const statusModal = statusModalElement ? new bootstrap.Modal(statusModalElement) : null;
            const statusSelect = document.getElementById('bookingStatusSelect');
            const statusForm = document.getElementById('bookingStatusForm');

            const setDetailModal = (row) => {
                if (!detailModalElement) return;
                detailModalElement.querySelector('#detailBookingId').textContent = row.dataset.bookingId || '-';
                detailModalElement.querySelector('#detailCustomerName').textContent = row.dataset.customer || '-';
                detailModalElement.querySelector('#detailCustomerEmail').textContent = row.dataset.email || '-';
                detailModalElement.querySelector('#detailPackage').textContent = row.dataset.package || '-';
                detailModalElement.querySelector('#detailAgency').textContent = row.dataset.agency || '-';
                detailModalElement.querySelector('#detailTravelDate').textContent = row.dataset.travelDate || '-';
                detailModalElement.querySelector('#detailAmount').textContent = row.dataset.amount
                    ? `$${Number(row.dataset.amount).toFixed(0)}`
                    : '-';

                // Update booking status badge
                const statusBadge = detailModalElement.querySelector('#detailStatus');
                const status = row.dataset.status || 'pending';
                statusBadge.className = status === 'confirmed'
                    ? 'tn-badge tn-badge-success'
                    : status === 'pending'
                        ? 'tn-badge tn-badge-warning'
                        : 'tn-badge tn-badge-danger';
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

                // Update payment status badge
                const paymentStatusBadge = detailModalElement.querySelector('#detailPaymentStatus');
                const paymentStatus = row.dataset.paymentStatus || 'unpaid';
                paymentStatusBadge.className = paymentStatus === 'paid'
                    ? 'tn-badge tn-badge-success'
                    : 'tn-badge tn-badge-warning';
                paymentStatusBadge.innerHTML = paymentStatus === 'paid'
                    ? '<i class="fas fa-credit-card me-1"></i>Paid'
                    : '<i class="fas fa-hourglass-half me-1"></i>Unpaid';
            };

            document.querySelectorAll('[data-action="view"]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const row = button.closest('tr');
                    if (!row) return;
                    setDetailModal(row);
                    detailModal?.show();
                });
            });

            document.querySelectorAll('[data-action="status"]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const row = button.closest('tr');
                    if (!row || !statusForm) return;
                    statusForm.action = row.dataset.updateUrl;
                    if (statusSelect) statusSelect.value = row.dataset.status || 'pending';
                    statusModal?.show();
                });
            });
        });
    </script>
@endsection
