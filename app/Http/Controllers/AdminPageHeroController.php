<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePageHeroRequest;
use App\Models\PageHero;
use Illuminate\Support\Facades\Storage;

class AdminPageHeroController extends Controller
{
    public function index()
    {
        $pageHeroes = PageHero::query()->orderBy('slug')->get();

        return view('admin.page-heroes.index', compact('pageHeroes'));
    }

    public function edit(PageHero $pageHero)
    {
        return view('admin.page-heroes.edit', compact('pageHero'));
    }

    public function update(UpdatePageHeroRequest $request, PageHero $pageHero)
    {
        $data = $request->validated();
        $data['background_image_url'] = $this->resolveImageUrl(
            $request,
            $data['background_image_url'] ?? null,
            $pageHero->background_image_url
        );

        $pageHero->update($data);

        return redirect()
            ->route('admin.page-heroes.index')
            ->with('success', 'Page hero updated successfully.');
    }

    private function resolveImageUrl(UpdatePageHeroRequest $request, ?string $imageUrl, ?string $existing = null): ?string
    {
        if ($request->hasFile('background_image')) {
            $this->deleteImageIfLocal($existing);
            return $request->file('background_image')->store('page-heroes', 'public');
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
