<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Package;
use Illuminate\Http\Request;

class AdminPackageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $category = $request->string('category')->trim()->toString();

        $packages = Package::with('agency')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($category && $category !== 'all', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $agencies = Agency::orderBy('name')->get();

        return view('admin.packages', [
            'packages' => $packages,
            'agencies' => $agencies,
            'search' => $search,
            'categoryFilter' => $category ?: 'all',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'agency_id' => ['required', 'exists:agencies,id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,draft,sold-out'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'gradient' => ['nullable', 'string', 'max:255'],
        ]);

        $data['booked'] = 0;
        Package::create($data);

        return redirect()->route('admin.packages')->with('success', 'Package created.');
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'agency_id' => ['required', 'exists:agencies,id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,draft,sold-out'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'gradient' => ['nullable', 'string', 'max:255'],
        ]);

        $package->update($data);

        return redirect()->route('admin.packages')->with('success', 'Package updated.');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages')->with('success', 'Package deleted.');
    }
}
