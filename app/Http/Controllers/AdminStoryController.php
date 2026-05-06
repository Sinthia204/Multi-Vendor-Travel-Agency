<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminStoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        $stories = Story::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.stories.index', compact('stories', 'search'));
    }

    public function create()
    {
        return view('admin.stories.create');
    }

    public function store(StoreStoryRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $data['image_url'] = $this->resolveImageUrl($request, $data['image_url'] ?? null);

        Story::create($data);

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Story created successfully.');
    }

    public function edit(Story $story)
    {
        return view('admin.stories.edit', compact('story'));
    }

    public function update(UpdateStoryRequest $request, Story $story)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $data['image_url'] = $this->resolveImageUrl($request, $data['image_url'] ?? null, $story->image_url);

        $story->update($data);

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Story updated successfully.');
    }

    public function destroy(Story $story)
    {
        $this->deleteImageIfLocal($story->image_url);
        $story->delete();

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Story deleted successfully.');
    }

    private function resolveImageUrl(Request $request, ?string $imageUrl, ?string $existing = null): ?string
    {
        if ($request->hasFile('image')) {
            $this->deleteImageIfLocal($existing);
            return $request->file('image')->store('stories', 'public');
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
