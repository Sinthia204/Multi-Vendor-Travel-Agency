<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Agency;
use App\Models\Package;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\Transport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingAgencies = Agency::query()
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'pendingAgencies' => $pendingAgencies,
        ]);
    }

    public function agencies()
    {
        return view('admin.agencies');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function bookings()
    {
        return view('admin.bookings');
    }

    public function packages()
    {
        return view('admin.packages');
    }

    public function reports(Request $request)
    {
        // Date range filter: today, week, 7d, 30d, month, year, all, custom
        $range = $request->string('range')->value() ?: '7d';
        $now = Carbon::now();

        $startDate = null;
        $endDate = null;

        if ($range === 'today') {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
        } elseif ($range === 'week') {
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfWeek();
        } elseif ($range === '30d') {
            $startDate = $now->copy()->subDays(29)->startOfDay();
            $endDate = $now->copy()->endOfDay();
        } elseif ($range === 'month') {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfDay();
        } elseif ($range === 'year') {
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfDay();
        } elseif ($range === 'all') {
            $startDate = null;
            $endDate = null;
        } elseif ($range === 'custom') {
            $from = $request->input('from');
            $to = $request->input('to');
            if ($from) {
                $startDate = Carbon::parse($from)->startOfDay();
            }
            if ($to) {
                $endDate = Carbon::parse($to)->endOfDay();
            }
        } else {
            // default last 7 days
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $endDate = $now->copy()->endOfDay();
        }

        // Base queries
        $paymentsBase = Payment::query();
        $bookingsBase = Booking::query();
        $usersBase = User::query();

        // Apply date range if provided
        if ($startDate && $endDate) {
            $paymentsBase->whereBetween('created_at', [$startDate, $endDate]);
            $bookingsBase->whereBetween('created_at', [$startDate, $endDate]);
            $usersBase->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Search filter
        $q = $request->input('q');
        if ($q) {
            $like = "%{$q}%";
            $paymentsBase->where(function ($builder) use ($like) {
                $builder->where('transaction_id', 'like', $like)
                    ->orWhereHas('booking', function ($b) use ($like) {
                        $b->where('booking_reference', 'like', $like)
                          ->orWhere('package_name', 'like', $like)
                          ->orWhereHas('agency', function ($a) use ($like) {
                              $a->where('name', 'like', $like);
                          })
                          ->orWhereHas('user', function ($u) use ($like) {
                              $u->where('name', 'like', $like);
                          });
                    });
            });

            $bookingsBase->where(function ($builder) use ($like) {
                $builder->where('booking_reference', 'like', $like)
                    ->orWhere('package_name', 'like', $like)
                    ->orWhereHas('user', function ($u) use ($like) {
                        $u->where('name', 'like', $like);
                    })
                    ->orWhereHas('package', function ($p) use ($like) {
                        $p->where('name', 'like', $like)
                          ->orWhereHas('agency', function ($a) use ($like) {
                              $a->where('name', 'like', $like);
                          });
                    });
            });
        }

        // Aggregations for summary cards
        $totalBookings = (int) (clone $bookingsBase)->count();
        $pendingBookings = (int) (clone $bookingsBase)->where('status', 'pending')->count();
        $confirmedBookings = (int) (clone $bookingsBase)->where('status', 'confirmed')->count();
        $cancelledBookings = (int) (clone $bookingsBase)->where('status', 'cancelled')->count();

        $totalPayments = (int) (clone $paymentsBase)->count();
        $successfulPayments = (int) (clone $paymentsBase)->where('status', 'success')->count();
        $failedPayments = (int) (clone $paymentsBase)->where('status', 'failed')->count();

        $totalRevenue = (float) (clone $paymentsBase)->where('status', 'success')->sum('amount');
        $monthlyRevenue = (float) Payment::query()
            ->where('status', 'success')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        $totalAgencies = Agency::query()->count();
        $totalPackages = Package::query()->count();
        $totalCustomers = User::query()->where('is_admin', false)->count();

        $paymentMethodCounts = (clone $paymentsBase)
            ->where('status', 'success')
            ->select('payment_method', DB::raw('count(*) as total'))
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->pluck('total', 'payment_method');

        $bookingStatusCounts = (clone $bookingsBase)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->pluck('total', 'status');

        [$trendLabels, $revenueTrend, $bookingTrend] = $this->buildTrends($range, $now);

        // Recent lists with eager loading to avoid N+1
        $recentPayments = (clone $paymentsBase)
            ->with(['booking.package.agency', 'booking.user', 'user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $recentBookings = (clone $bookingsBase)
            ->with(['user', 'package.agency', 'payments'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Export CSV if requested
        if ($request->input('export') === 'csv') {
            $rows = [];
            foreach ($recentPayments->items() as $p) {
                $rows[] = [
                    'payment_id' => $p->id,
                    'transaction_id' => $p->transaction_id,
                    'booking_reference' => $p->booking?->booking_reference,
                    'customer' => $p->booking?->user?->name ?? $p->user?->name ?? 'Guest',
                    'agency' => $p->booking?->package?->agency?->name,
                    'package' => $p->booking?->package?->name,
                    'method' => $p->payment_method,
                    'amount' => $p->amount,
                    'status' => $p->status,
                    'date' => $p->created_at?->toDateTimeString(),
                ];
            }

            $filename = 'payments_report_' . now()->format('Ymd_His') . '.csv';
            $handle = fopen('php://memory', 'r+');
            fputcsv($handle, array_keys($rows[0] ?? ['empty' => '']));
            foreach ($rows as $line) {
                fputcsv($handle, $line);
            }
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
        }

        return view('admin.reports', [
            'range' => $range,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'confirmedBookings' => $confirmedBookings,
            'cancelledBookings' => $cancelledBookings,
            'totalPayments' => $totalPayments,
            'successfulPayments' => $successfulPayments,
            'failedPayments' => $failedPayments,
            'totalRevenue' => $totalRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'totalAgencies' => $totalAgencies,
            'totalPackages' => $totalPackages,
            'totalCustomers' => $totalCustomers,
            'paymentMethodCounts' => $paymentMethodCounts,
            'bookingStatusCounts' => $bookingStatusCounts,
            'trendLabels' => $trendLabels,
            'revenueTrend' => $revenueTrend,
            'bookingTrend' => $bookingTrend,
            'recentPayments' => $recentPayments,
            'recentBookings' => $recentBookings,
            'q' => $q,
        ]);
    }

    private function buildTrends(string $range, Carbon $now): array
    {
        if ($range === 'all') {
            $labels = [];
            $revenue = [];
            $bookings = [];

            for ($i = 11; $i >= 0; $i--) {
                $start = $now->copy()->subMonths($i)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                $labels[] = $start->format('M Y');

                $revenue[] = (float) Payment::query()
                    ->whereBetween('created_at', [$start, $end])
                    ->where('status', 'success')
                    ->sum('amount');

                $bookings[] = Booking::query()
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            }

            return [$labels, $revenue, $bookings];
        }

        if ($range === 'year') {
            // last 12 months
            $labels = [];
            $revenue = [];
            $bookings = [];
            for ($i = 11; $i >= 0; $i--) {
                $start = $now->copy()->subMonths($i)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                $labels[] = $start->format('M Y');

                $revenue[] = (float) Payment::query()
                    ->whereBetween('created_at', [$start, $end])
                    ->where('status', 'success')
                    ->sum('amount');

                $bookings[] = Booking::query()
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            }

            return [$labels, $revenue, $bookings];
        }

        $days = match ($range) {
            'today' => 1,
            '30d' => 30,
            'month' => $now->daysInMonth,
            'week' => 7,
            default => 7,
        };

        $labels = [];
        $revenue = [];
        $bookings = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $start = $day->copy()->startOfDay();
            $end = $day->copy()->endOfDay();

            $labels[] = $day->format('M d');
            $revenue[] = (float) Payment::query()
                ->whereBetween('created_at', [$start, $end])
                ->where('status', 'success')
                ->sum('amount');
            $bookings[] = Booking::query()
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        return [$labels, $revenue, $bookings];
    }

    public function placeholder($page)
    {
        $allowed = ['coupons'];

        if (!in_array($page, $allowed)) {
            abort(404);
        }

        return view('admin.placeholder', compact('page'));
    }
}
