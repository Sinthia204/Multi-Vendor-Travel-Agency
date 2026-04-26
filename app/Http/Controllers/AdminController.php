<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Agency;
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
        $range = $request->string('range')->value() ?: '7d';
        $now = Carbon::now();

        $startDate = match ($range) {
            '30d' => $now->copy()->subDays(29)->startOfDay(),
            'month' => $now->copy()->startOfMonth(),
            'all' => null,
            default => $now->copy()->subDays(6)->startOfDay(),
        };

        $paymentsQuery = Payment::query();
        $bookingsQuery = Booking::query();
        $usersQuery = User::query();

        if ($startDate) {
            $paymentsQuery->where('created_at', '>=', $startDate);
            $bookingsQuery->where('created_at', '>=', $startDate);
            $usersQuery->where('created_at', '>=', $startDate);
        }

        $revenue = (float) $paymentsQuery->clone()->where('status', 'success')->sum('amount');
        $paymentsCount = $paymentsQuery->clone()->count();
        $successfulPayments = $paymentsQuery->clone()->where('status', 'success')->count();
        $bookingsTotal = $bookingsQuery->clone()->count();
        $bookingsPending = $bookingsQuery->clone()->where('status', 'pending')->count();
        $newUsers = $usersQuery->clone()->count();
        $couponUsage = $bookingsQuery->clone()->whereNotNull('coupon_code')->count();
        $discountTotal = (float) $bookingsQuery->clone()->sum('discount_amount');

        $activeCoupons = Coupon::query()->where('active', true)->count();
        $hotelsCount = Hotel::query()->count();
        $transportCount = Transport::query()->count();

        $paymentMethodCounts = $paymentsQuery->clone()
            ->where('status', 'success')
            ->select('payment_method', DB::raw('count(*) as total'))
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->pluck('total', 'payment_method');

        $bookingStatusCounts = $bookingsQuery->clone()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->pluck('total', 'status');

        [$trendLabels, $revenueTrend, $bookingTrend] = $this->buildTrends($range, $now);

        $recentPayments = Payment::query()
            ->with(['user', 'booking'])
            ->latest()
            ->take(8)
            ->get();

        $recentBookings = Booking::query()
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.reports', [
            'range' => $range,
            'revenue' => $revenue,
            'paymentsCount' => $paymentsCount,
            'successfulPayments' => $successfulPayments,
            'bookingsTotal' => $bookingsTotal,
            'bookingsPending' => $bookingsPending,
            'newUsers' => $newUsers,
            'couponUsage' => $couponUsage,
            'discountTotal' => $discountTotal,
            'activeCoupons' => $activeCoupons,
            'hotelsCount' => $hotelsCount,
            'transportCount' => $transportCount,
            'paymentMethodCounts' => $paymentMethodCounts,
            'bookingStatusCounts' => $bookingStatusCounts,
            'trendLabels' => $trendLabels,
            'revenueTrend' => $revenueTrend,
            'bookingTrend' => $bookingTrend,
            'recentPayments' => $recentPayments,
            'recentBookings' => $recentBookings,
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

        $days = $range === '30d' ? 30 : 7;
        if ($range === 'month') {
            $days = $now->daysInMonth;
        }

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
