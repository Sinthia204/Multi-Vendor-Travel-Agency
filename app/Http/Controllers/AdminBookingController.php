<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();
        $agencyId = $request->string('agency')->trim()->toString();
        $date = $request->date('date');

        $bookings = Booking::with(['user', 'agency', 'package'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('booking_reference', 'like', "%{$search}%")
                        ->orWhere('package_name', 'like', "%{$search}%");
                })->orWhereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status && $status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($agencyId && $agencyId !== 'all', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->when($date, function ($query) use ($date) {
                $query->whereDate('travel_date', $date);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $agencies = Agency::orderBy('name')->get();

        $statusCounts = Booking::query()
            ->selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalCount = Booking::query()->count();

        return view('admin.bookings', [
            'bookings' => $bookings,
            'agencies' => $agencies,
            'search' => $search,
            'statusFilter' => $status ?: 'all',
            'agencyFilter' => $agencyId ?: 'all',
            'dateFilter' => $date?->format('Y-m-d'),
            'statusCounts' => $statusCounts,
            'totalCount' => $totalCount,
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['required', 'in:confirmed,pending,cancelled'],
        ]);

        $booking->update(['status' => $data['status']]);

        return redirect()->route('admin.bookings')->with('success', 'Booking updated.');
    }

    public function export(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();
        $agencyId = $request->string('agency')->trim()->toString();
        $date = $request->date('date');

        $bookings = Booking::with(['user', 'agency', 'package'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('booking_reference', 'like', "%{$search}%")
                        ->orWhere('package_name', 'like', "%{$search}%");
                })->orWhereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status && $status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($agencyId && $agencyId !== 'all', function ($query) use ($agencyId) {
                $query->where('agency_id', $agencyId);
            })
            ->when($date, function ($query) use ($date) {
                $query->whereDate('travel_date', $date);
            })
            ->orderByDesc('created_at')
            ->get();

        $lines = [
            ['Booking ID', 'Customer', 'Email', 'Package', 'Agency', 'Travel Date', 'Amount', 'Status'],
        ];

        foreach ($bookings as $booking) {
            $lines[] = [
                $booking->booking_reference,
                $booking->user?->name,
                $booking->user?->email,
                $booking->package_name,
                $booking->agency?->name,
                optional($booking->travel_date)->format('Y-m-d'),
                $booking->amount,
                $booking->status,
            ];
        }

        $output = collect($lines)->map(function ($row) {
            return collect($row)->map(function ($value) {
                $escaped = str_replace('"', '""', (string) $value);
                return '"' . $escaped . '"';
            })->implode(',');
        })->implode("\n");

        return response($output)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="bookings.csv"');
    }
}
