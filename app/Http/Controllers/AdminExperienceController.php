<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminExperienceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        $experiences = Experience::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.experiences.index', compact('experiences', 'search'));
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(StoreExperienceRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $data['image_url'] = $this->resolveImageUrl($request, $data['image_url'] ?? null);

        Experience::create($data);

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Experience created successfully.');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(UpdateExperienceRequest $request, Experience $experience)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $data['image_url'] = $this->resolveImageUrl($request, $data['image_url'] ?? null, $experience->image_url);

        $experience->update($data);

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Experience updated successfully.');
    }

    public function destroy(Experience $experience)
    {
        $this->deleteImageIfLocal($experience->image_url);
        $experience->delete();

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Experience deleted successfully.');
    }

    private function resolveImageUrl(Request $request, ?string $imageUrl, ?string $existing = null): ?string
    {
        if ($request->hasFile('image')) {
            $this->deleteImageIfLocal($existing);
            return $request->file('image')->store('experiences', 'public');
        }

        if ($imageUrl !== null && $imageUrl !== '') {
            return $imageUrl;
        }

        return $existing;
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
