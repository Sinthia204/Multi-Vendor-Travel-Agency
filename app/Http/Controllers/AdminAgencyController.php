<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

class AdminAgencyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $agencies = Agency::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($status && $status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $statusCounts = Agency::query()
            ->selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalCount = Agency::query()->count();

        return view('admin.agencies', [
            'agencies' => $agencies,
            'search' => $search,
            'statusFilter' => $status ?: 'all',
            'statusCounts' => $statusCounts,
            'totalCount' => $totalCount,
        ]);
    }

    public function export(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $agencies = Agency::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($status && $status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('name')
            ->get();

        $lines = [
            ['Agency Name', 'Contact Person', 'Email', 'Phone', 'Status', 'Registered'],
        ];

        foreach ($agencies as $agency) {
            $lines[] = [
                $agency->name,
                $agency->contact_person,
                $agency->email,
                $agency->phone,
                $agency->status,
                optional($agency->registered_at)->format('M d, Y'),
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
            ->header('Content-Disposition', 'attachment; filename="agencies.csv"');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:agencies,email'],
            'phone' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:pending,approved,rejected,suspended'],
            'registered_at' => ['nullable', 'date'],
        ]);

        Agency::create($data);

        return redirect()->route('admin.agencies')->with('success', 'Agency created.');
    }

    public function update(Request $request, Agency $agency)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:agencies,email,' . $agency->id],
            'phone' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:pending,approved,rejected,suspended'],
            'registered_at' => ['nullable', 'date'],
        ]);

        $agency->update($data);

        return redirect()->route('admin.agencies')->with('success', 'Agency updated.');
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();

        return redirect()->route('admin.agencies')->with('success', 'Agency deleted.');
    }

    public function approve(Agency $agency)
    {
        // Approve the agency so they can log in and manage packages.
        $agency->update([
            'status' => 'approved',
            'approved_at' => now(),
            'rejected_at' => null,
        ]);

        return redirect()->route('admin.agencies')->with('success', 'Agency approved.');
    }

    public function reject(Agency $agency)
    {
        // Reject the agency registration request.
        $agency->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        return redirect()->route('admin.agencies')->with('success', 'Agency rejected.');
    }
}
