<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\HomeContent;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\PageHero;
use App\Models\Story;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $homeContent = HomeContent::query()->first();

        $featuredHotels = Hotel::query()
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderByRaw('featured_order is null, featured_order asc')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        if ($featuredHotels->isEmpty()) {
            $featuredHotels = Hotel::query()
                ->where('status', 'active')
                ->orderByDesc('created_at')
                ->take(3)
                ->get();
        }

        $featuredPackages = Package::with('agency')
            ->where('status', '!=', 'draft')
            ->where('is_featured', true)
            ->orderByRaw('featured_order is null, featured_order asc')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        if ($featuredPackages->isEmpty()) {
            $featuredPackages = Package::with('agency')
                ->where('status', '!=', 'draft')
                ->orderByDesc('created_at')
                ->take(3)
                ->get();
        }

        $experiences = Experience::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $stories = Story::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('home', compact('homeContent', 'featuredHotels', 'featuredPackages', 'experiences', 'stories'));
    }

    public function destinations()
    {
        $hotels = Hotel::where('status', 'active')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('destinations', [
            'hotels' => $hotels,
            'pageHero' => $this->getPageHero('destinations'),
        ]);
    }

    public function packages(Request $request)
    {
        $destination = $request->string('destination')->trim()->toString();

        $packages = Package::with('agency')
            ->when($destination, function ($query) use ($destination) {
                $query->where(function ($sub) use ($destination) {
                    $sub->where('name', 'like', "%{$destination}%")
                        ->orWhere('location', 'like', "%{$destination}%")
                        ->orWhere('category', 'like', "%{$destination}%");
                });
            })
            ->where('status', '!=', 'draft')
            ->orderByDesc('created_at')
            ->get();

        return view('packages', [
            'packages' => $packages,
            'destination' => $destination,
            'dates' => $request->string('dates')->trim()->toString(),
            'travelers' => $request->string('travelers')->trim()->toString(),
            'pageHero' => $this->getPageHero('packages'),
        ]);
    }

    public function experiences()
    {
        $experiences = Experience::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('experiences', [
            'experiences' => $experiences,
            'pageHero' => $this->getPageHero('experiences'),
        ]);
    }

    public function stories()
    {
        $stories = Story::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('stories', [
            'stories' => $stories,
            'pageHero' => $this->getPageHero('stories'),
        ]);
    }

    public function contact()
    {
        return view('contact', [
            'pageHero' => $this->getPageHero('contact'),
        ]);
    }

    private function getPageHero(string $slug): ?PageHero
    {
        return PageHero::query()->where('slug', $slug)->first();
    }
}
