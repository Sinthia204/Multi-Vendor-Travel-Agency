{{-- Use the shared admin layout for navigation and styles. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Dashboard')
{{-- Admin header title. --}}
@section('page-title', 'Dashboard')

@section('content')
    <!-- Summary KPI cards for a quick admin overview. -->
    <div class="row g-4 mb-4">
        <!-- Revenue -->
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card animate-in animate-in-delay-1">
                <div class="stat-card-top">
                    <div class="stat-icon green">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <span class="stat-badge positive">+12.5%</span>
                </div>
                <div class="stat-value">$124,563</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <!-- Bookings -->
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card animate-in animate-in-delay-2">
                <div class="stat-card-top">
                    <div class="stat-icon primary">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span class="stat-badge positive">+8.2%</span>
                </div>
                <div class="stat-value">1,847</div>
                <div class="stat-label">Active Bookings</div>
            </div>
        </div>
        <!-- Agencies -->
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card animate-in animate-in-delay-3">
                <div class="stat-card-top">
                    <div class="stat-icon secondary">
                        <i class="fas fa-building"></i>
                    </div>
                    <span class="stat-badge positive">+3.1%</span>
                </div>
                <div class="stat-value">156</div>
                <div class="stat-label">Registered Agencies</div>
            </div>
        </div>
        <!-- Users -->
        <div class="col-md-6 col-xl-3">
            <div class="tn-card stat-card animate-in animate-in-delay-4">
                <div class="stat-card-top">
                    <div class="stat-icon purple">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="stat-badge negative">-2.4%</span>
                </div>
                <div class="stat-value">12,493</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
    </div>

    <!-- Charts section (demo data) for trends and distribution. -->
    <div class="row g-4 mb-4">
        <!-- Revenue Trend -->
        <div class="col-lg-4">
            <div class="tn-card-static">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">Revenue Trend</h3>
                    <p class="chart-card-subtitle">Monthly revenue overview</p>
                </div>
                <div class="chart-area">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Bookings by Category -->
        <div class="col-lg-4">
            <div class="tn-card-static">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">Bookings by Category</h3>
                    <p class="chart-card-subtitle">Distribution of booking types</p>
                </div>
                <div class="chart-area">
                    <canvas id="bookingsCategoryChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Weekly Trends -->
        <div class="col-lg-4">
            <div class="tn-card-static">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">Weekly Trends</h3>
                    <p class="chart-card-subtitle">Bookings per day this week</p>
                </div>
                <div class="chart-area">
                    <canvas id="weeklyTrendsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Trend — line chart with static demo data.
            const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
            const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 200);
            revenueGradient.addColorStop(0, 'rgba(45, 138, 122, 0.15)');
            revenueGradient.addColorStop(1, 'rgba(45, 138, 122, 0)');

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Revenue',
                        data: [18000, 22000, 19000, 35000, 28000, 38000, 45000],
                        borderColor: '#2d8a7a',
                        backgroundColor: revenueGradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2d8a7a',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1a2e35',
                            titleFont: {
                                family: 'Space Grotesk'
                            },
                            bodyFont: {
                                family: 'DM Sans'
                            },
                            cornerRadius: 8,
                            padding: 12,
                            callbacks: {
                                label: function(ctx) {
                                    return '$' + ctx.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7b80',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(232,226,214,0.5)'
                            },
                            ticks: {
                                color: '#6b7b80',
                                font: {
                                    size: 11
                                },
                                callback: function(val) {
                                    return '$' + (val / 1000) + 'K';
                                }
                            }
                        }
                    }
                }
            });

            // Bookings by Category — doughnut chart for category split.
            const doughnutCtx = document.getElementById('bookingsCategoryChart').getContext('2d');
            const doughnutChart = new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Tours', 'Hotels', 'Transport', 'Activities'],
                    datasets: [{
                        data: [40, 30, 20, 10],
                        backgroundColor: ['#2d8a7a', '#d4a030', '#8b5cf6', '#ec4899'],
                        borderWidth: 0,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 16,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    family: 'DM Sans',
                                    size: 12
                                },
                                color: '#6b7b80'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1a2e35',
                            cornerRadius: 8,
                            padding: 12,
                            bodyFont: {
                                family: 'DM Sans'
                            },
                        }
                    }
                },
                plugins: [{
                    id: 'centerText',
                    beforeDraw: function(chart) {
                        const {
                            width,
                            height,
                            ctx
                        } = chart;
                        ctx.save();
                        ctx.font = '700 20px Space Grotesk';
                        ctx.fillStyle = '#1a2e35';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText('2,456', width / 2, height / 2 - 8);
                        ctx.font = '400 12px DM Sans';
                        ctx.fillStyle = '#6b7b80';
                        ctx.fillText('Total', width / 2, height / 2 + 12);
                        ctx.restore();
                    }
                }]
            });

            // Weekly Trends — bar chart for week-to-date bookings.
            const barCtx = document.getElementById('weeklyTrendsChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Bookings',
                        data: [45, 62, 38, 72, 55, 80, 35],
                        backgroundColor: 'rgba(45, 138, 122, 0.8)',
                        hoverBackgroundColor: '#2d8a7a',
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 32,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1a2e35',
                            cornerRadius: 8,
                            padding: 12,
                            bodyFont: {
                                family: 'DM Sans'
                            },
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7b80',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(232,226,214,0.5)'
                            },
                            ticks: {
                                color: '#6b7b80',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
