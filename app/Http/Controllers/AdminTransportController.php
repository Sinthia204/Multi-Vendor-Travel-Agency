<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTransportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();
        $type = $request->string('type')->trim()->toString();

        $transports = Transport::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('provider', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.transport.index', compact('transports'));
    }

    public function create()
    {
        return view('admin.transport.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateTransport($request);
        unset($data['image']);

        $imagePath = $this->storeImage($request, 'transport');
        if ($imagePath) {
            $data['image_url'] = $imagePath;
        }

        Transport::create($data);

        return redirect()->route('admin.transport.index')
            ->with('success', 'Transport option created successfully.');
    }

    public function edit(Transport $transport)
    {
        return view('admin.transport.edit', compact('transport'));
    }

    public function update(Request $request, Transport $transport)
    {
        $data = $this->validateTransport($request);
        unset($data['image']);

        $imagePath = $this->storeImage($request, 'transport');
        if ($imagePath) {
            $this->deleteImageIfLocal($transport->image_url);
            $data['image_url'] = $imagePath;
        }

        $transport->update($data);

        return redirect()->route('admin.transport.index')
            ->with('success', 'Transport option updated successfully.');
    }

    public function destroy(Transport $transport)
    {
        $this->deleteImageIfLocal($transport->image_url);
        $transport->delete();

        return redirect()->route('admin.transport.index')
            ->with('success', 'Transport option deleted successfully.');
    }

    private function validateTransport(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:120'],
            'provider' => ['nullable', 'string', 'max:255'],
            'price_per_trip' => ['required', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', 'max:30'],
            'image' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function storeImage(Request $request, string $folder): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store("{$folder}", 'public');
    }

    private function deleteImageIfLocal(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
