<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateHomeContentRequest;
use App\Models\HomeContent;
use Illuminate\Support\Facades\Storage;

class AdminHomeContentController extends Controller
{
    public function edit()
    {
        $homeContent = HomeContent::query()->first();

        return view('admin.home-content.edit', compact('homeContent'));
    }

    public function update(UpdateHomeContentRequest $request)
    {
        $homeContent = HomeContent::query()->firstOrCreate([]);
        $data = $request->validated();

        $data['hero_image_url'] = $this->resolveImageUrl(
            $request,
            $data['hero_image_url'] ?? null,
            $homeContent->hero_image_url
        );

        $homeContent->update($data);

        return redirect()
            ->route('admin.home-content.edit')
            ->with('success', 'Home content updated successfully.');
    }

    private function resolveImageUrl(UpdateHomeContentRequest $request, ?string $imageUrl, ?string $existing = null): ?string
    {
        if ($request->hasFile('hero_image')) {
            $this->deleteImageIfLocal($existing);
            return $request->file('hero_image')->store('home', 'public');
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
