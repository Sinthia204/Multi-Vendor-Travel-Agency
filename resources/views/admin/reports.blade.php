{{-- Use the shared admin layout for consistent navigation and styles. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Reports')
{{-- Admin header title. --}}
@section('page-title', 'Reports')

@section('content')
    {{-- Date range filter for report metrics and charts. --}}
    <form class="reports-filters mb-4" method="GET" action="{{ route('admin.reports') }}">
        <div class="reports-filter-item">
            <select class="tn-form-control" name="range">
                <option value="today" @selected($range === 'today')>Today</option>
                <option value="week" @selected($range === 'week')>This week</option>
                <option value="7d" @selected($range === '7d')>Last 7 days</option>
                <option value="30d" @selected($range === '30d')>Last 30 days</option>
                <option value="month" @selected($range === 'month')>This month</option>
                <option value="year" @selected($range === 'year')>This year</option>
                <option value="all" @selected($range === 'all')>All time</option>
                <option value="custom" @selected($range === 'custom')>Custom range</option>
            </select>
        <div class="reports-filter-item">
            <input type="date" name="from" value="{{ request('from') }}" class="tn-form-control" />
        </div>
        <div class="reports-filter-item">
            <input type="date" name="to" value="{{ request('to') }}" class="tn-form-control" />
        </div>
        <div class="reports-filter-item reports-search">
            <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search (booking, txn, customer, agency, package)" class="tn-form-control" />
        </div>
        <div class="reports-filter-item">
            <button class="btn-outline-tn" type="submit">Apply</button>
            <a class="btn-outline-tn" href="{{ route('admin.reports') }}">Reset</a>
            <a class="btn-outline-tn" href="{{ route('admin.reports', array_merge(request()->all(), ['export' => 'csv'])) }}">Export CSV</a>
        </div>
        <div class="reports-filter-item text-muted-tn" style="font-size:13px;margin-left:auto;align-self:center;">Updated {{ now()->format('M d, Y H:i') }}</div>
    </form>

    {{-- Summary cards --}}
    <div class="summary-grid g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($totalBookings) }}</div>
                <div class="stat-label">Total bookings</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($pendingBookings) }}</div>
                <div class="stat-label">Pending bookings</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($confirmedBookings) }}</div>
                <div class="stat-label">Confirmed bookings</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($cancelledBookings) }}</div>
                <div class="stat-label">Cancelled bookings</div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($totalPayments) }}</div>
                <div class="stat-label">Total payments</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($successfulPayments) }}</div>
                <div class="stat-label">Successful payments</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($failedPayments) }}</div>
                <div class="stat-label">Failed payments</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($totalRevenue, 2) }} BDT</div>
                <div class="stat-label">Total revenue</div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($monthlyRevenue, 2) }} BDT</div>
                <div class="stat-label">Monthly revenue</div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($totalAgencies) }}</div>
                <div class="stat-label">Total agencies</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($totalPackages) }}</div>
                <div class="stat-label">Total packages</div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="tn-card stat-card">
                <div class="stat-value">{{ number_format($totalCustomers) }}</div>
                <div class="stat-label">Total customers</div>
            </div>
        </div>
    </div>

    {{-- Charts for trend and distribution snapshots. --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="tn-card-static">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">Revenue trend</h3>
                    <p class="chart-card-subtitle">Successful payments</p>
                </div>
                <div class="chart-area">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="tn-card-static">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">Booking trend</h3>
                    <p class="chart-card-subtitle">All bookings created</p>
                </div>
                <div class="chart-area">
                    <canvas id="bookingTrendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="tn-card-static">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">Payment methods</h3>
                    <p class="chart-card-subtitle">Successful payments by method</p>
                </div>
                <div class="chart-area">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="tn-card-static">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">Booking status</h3>
                    <p class="chart-card-subtitle">Status distribution</p>
                </div>
                <div class="chart-area">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent activity tables for payments and bookings. --}}
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="tn-card-static">
                <div class="tn-card-header">
                    <h3 class="tn-card-header-title">Recent payments</h3>
                    <a href="{{ route('admin.payments') }}" class="text-primary-tn" style="font-size:14px; font-weight:500;">View All -></a>
                </div>
                <div class="tn-table-wrap">
                    <table class="tn-table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Booking ID</th>
                                <th>Customer Name</th>
                                <th>Agency Name</th>
                                <th>Package Name</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->transaction_id }}</td>
                                    <td>{{ $payment->booking?->booking_reference ?? '-' }}</td>
                                    <td>
                                        @php
                                            $bookingUser = $payment->booking?->user ?? null;
                                            $paymentUser = $payment->user ?? null;
                                            $showCustomer = null;

                                            if ($bookingUser) {
                                                // booking has an associated user (actual customer)
                                                $showCustomer = $bookingUser;
                                            } elseif ($paymentUser && !$paymentUser->is_admin) {
                                                // show payment user only when not admin
                                                $showCustomer = $paymentUser;
                                            } elseif ($paymentUser && $paymentUser->is_admin && $payment->booking && $paymentUser->id === $payment->booking->user_id) {
                                                // admin actually made the booking, show them
                                                $showCustomer = $paymentUser;
                                            }
                                        @endphp
                                        {{ $showCustomer?->name ?? 'Guest' }}
                                    </td>
                                    <td>{{ $payment->booking?->package?->agency?->name ?? '-' }}</td>
                                    <td>{{ $payment->booking?->package?->name ?? $payment->booking?->package_name ?? '-' }}</td>
                                    <td>{{ $payment->payment_method ?? '-' }}</td>
                                    <td>{{ number_format($payment->amount, 2) }} BDT</td>
                                    <td>
                                        @php
                                            $status = strtolower($payment->status ?? 'pending');
                                            $badge = match ($status) {
                                                'pending' => 'warning',
                                                'success', 'paid' => 'success',
                                                'failed' => 'danger',
                                                'refunded' => 'info',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="tn-badge tn-badge-{{ $badge }}">{{ ucfirst($status) }}</span>
                                    </td>
                                    <td>{{ $payment->created_at?->format('M d, Y H:i') }}</td>
                                    <td><a href="{{ route('admin.payments.show', $payment) }}" class="text-primary-tn">View</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">No payments found for this range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $recentPayments->links() }}</div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="tn-card-static">
                <div class="tn-card-header">
                    <h3 class="tn-card-header-title">Recent bookings</h3>
                    <a href="{{ url('/admin/bookings') }}" class="text-primary-tn" style="font-size:14px; font-weight:500;">View All -></a>
                </div>
                <div class="tn-table-wrap">
                    <table class="tn-table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Agency</th>
                                <th>Package</th>
                                <th>Travel Date</th>
                                <th>Total Amount</th>
                                <th>Booking Status</th>
                                <th>Payment Status</th>
                                <th>Booking Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_reference }}</td>
                                    <td>{{ $booking->user?->name ?? 'Guest' }}</td>
                                    <td>{{ $booking->package?->agency?->name ?? $booking->agency?->name ?? '-' }}</td>
                                    <td>{{ $booking->package?->name ?? $booking->package_name ?? '-' }}</td>
                                    <td>{{ $booking->travel_date?->format('M d, Y') ?? '-' }}</td>
                                    <td>{{ number_format($booking->amount, 2) }} BDT</td>
                                    <td>
                                        @php
                                            $bstatus = strtolower($booking->status ?? 'pending');
                                            $bbadge = match ($bstatus) {
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="tn-badge tn-badge-{{ $bbadge }}">{{ ucfirst($bstatus) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $latestPayment = $booking->payments->sortByDesc('created_at')->first();
                                            $pstatus = strtolower($latestPayment?->status ?? 'pending');
                                            $pbadge = match ($pstatus) {
                                                'pending' => 'warning',
                                                'success', 'paid' => 'success',
                                                'failed' => 'danger',
                                                'refunded' => 'info',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        @if($latestPayment)
                                            <span class="tn-badge tn-badge-{{ $pbadge }}">{{ ucfirst($pstatus) }}</span>
                                        @else
                                            <span class="tn-badge tn-badge-warning">No payment</span>
                                        @endif
                                    </td>
                                    <td>{{ $booking->created_at?->format('M d, Y H:i') }}</td>
                                    <td><a href="{{ route('admin.bookings.show', $booking) }}" class="text-primary-tn">View</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">No bookings found for this range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $recentBookings->links() }}</div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Server-provided datasets for the charts.
        const trendLabels = @json($trendLabels);
        const revenueTrend = @json($revenueTrend);
        const bookingTrend = @json($bookingTrend);
        const paymentMethodCounts = @json($paymentMethodCounts);
        const bookingStatusCounts = @json($bookingStatusCounts);

        // Revenue trend line chart.
        const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
        const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 200);
        revenueGradient.addColorStop(0, 'rgba(45, 138, 122, 0.18)');
        revenueGradient.addColorStop(1, 'rgba(45, 138, 122, 0)');

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueTrend,
                    borderColor: '#2d8a7a',
                    backgroundColor: revenueGradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#2d8a7a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    y: {
                        ticks: {
                            callback: (value) => value.toLocaleString()
                        }
                    }
                }
            }
        });

        // Booking trend bar chart.
        const bookingCtx = document.getElementById('bookingTrendChart').getContext('2d');
        new Chart(bookingCtx, {
            type: 'bar',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Bookings',
                    data: bookingTrend,
                    backgroundColor: 'rgba(212, 160, 48, 0.5)',
                    borderColor: '#d4a030',
                    borderWidth: 1.5,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                }
            }
        });

        // Payment method doughnut chart.
        const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
        new Chart(paymentMethodCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(paymentMethodCounts),
                datasets: [{
                    data: Object.values(paymentMethodCounts),
                    backgroundColor: ['#2d8a7a', '#3da88f', '#d4a030', '#1a6b5a', '#f59e0b'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Booking status pie chart.
        const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
        new Chart(bookingStatusCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(bookingStatusCounts),
                datasets: [{
                    data: Object.values(bookingStatusCounts),
                    backgroundColor: ['#2d8a7a', '#f59e0b', '#e54545', '#3da88f'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection
