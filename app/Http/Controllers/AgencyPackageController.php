<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class AgencyPackageController extends Controller
{
    public function index(Request $request)
    {
        $agency = $request->user('agency');

        // Limit packages to the authenticated agency.
        $packages = Package::query()
            ->where('agency_id', $agency->id)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('agency.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('agency.packages.create');
    }

    public function store(Request $request)
    {
        $agency = $request->user('agency');

        $data = $this->validatePackage($request);
        $data['agency_id'] = $agency->id;
        $data['booked'] = 0;

        Package::create($data);

        return redirect()->route('agency.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function edit(Request $request, int $package)
    {
        $agency = $request->user('agency');
        $package = Package::query()
            ->where('agency_id', $agency->id)
            ->findOrFail($package);

        return view('agency.packages.edit', compact('package'));
    }

    public function update(Request $request, int $package)
    {
        $agency = $request->user('agency');
        $package = Package::query()
            ->where('agency_id', $agency->id)
            ->findOrFail($package);

        $data = $this->validatePackage($request);
        $package->update($data);

        return redirect()->route('agency.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Request $request, int $package)
    {
        $agency = $request->user('agency');
        $package = Package::query()
            ->where('agency_id', $agency->id)
            ->findOrFail($package);

        $package->delete();

        return redirect()->route('agency.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    private function validatePackage(Request $request): array
    {
        // Agencies can only manage their own package details.
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:active,draft'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'gradient' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
