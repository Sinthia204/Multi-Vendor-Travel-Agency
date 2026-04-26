{{-- Use the shared admin layout for consistent navigation and styles. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Reports')
{{-- Admin header title. --}}
@section('page-title', 'Reports')

@section('content')
    {{-- Date range filter for report metrics and charts. --}}
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.reports') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <select class="tn-form-control" name="range" style="width:200px;">
                <option value="7d" @selected($range === '7d')>Last 7 days</option>
                <option value="30d" @selected($range === '30d')>Last 30 days</option>
                <option value="month" @selected($range === 'month')>This month</option>
                <option value="all" @selected($range === 'all')>All time</option>
            </select>
            {{-- Apply selected range. --}}
            <button class="btn-outline-tn" type="submit">Apply</button>
            <a class="btn-outline-tn" href="{{ route('admin.reports') }}">Reset</a>
        </div>
        <div class="text-muted-tn" style="font-size:13px;">
            Updated {{ now()->format('M d, Y H:i') }}
        </div>
    </form>

    {{-- KPI cards for revenue, bookings, users, coupons, and inventory. --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon green">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($revenue, 2) }} BDT</div>
                <div class="stat-label">Revenue (successful)</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon primary">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($successfulPayments) }}</div>
                <div class="stat-label">Successful payments</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon secondary">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($bookingsTotal) }}</div>
                <div class="stat-label">Bookings (total)</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon purple">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($bookingsPending) }}</div>
                <div class="stat-label">Bookings pending</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($newUsers) }}</div>
                <div class="stat-label">New users</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon secondary">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($couponUsage) }}</div>
                <div class="stat-label">Coupons used</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon green">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($discountTotal, 2) }} BDT</div>
                <div class="stat-label">Discount total</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon purple">
                        <i class="fas fa-warehouse"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($hotelsCount + $transportCount) }}</div>
                <div class="stat-label">Inventory items</div>
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
                    <a href="{{ route('admin.payments') }}" class="text-primary-tn"
                        style="font-size:14px; font-weight:500;">View All -></a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="tn-table">
                        <thead>
                            <tr>
                                <th>Transaction</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->transaction_id }}</td>
                                    <td>{{ $payment->user?->name ?? 'Unknown' }}</td>
                                    <td>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                    <td>
                                        <span
                                            class="tn-badge tn-badge-{{ $payment->status === 'success' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No payments yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="tn-card-static">
                <div class="tn-card-header">
                    <h3 class="tn-card-header-title">Recent bookings</h3>
                    <a href="{{ url('/admin/bookings') }}" class="text-primary-tn"
                        style="font-size:14px; font-weight:500;">View All -></a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="tn-table">
                        <thead>
                            <tr>
                                <th>Booking</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_reference }}</td>
                                    <td>{{ $booking->user?->name ?? 'Unknown' }}</td>
                                    <td>{{ number_format($booking->amount, 2) }} {{ $booking->currency }}</td>
                                    <td>
                                        <span
                                            class="tn-badge tn-badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No bookings yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
