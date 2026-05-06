@extends('layouts.app')

@section('title', 'TravelNest - Plan journeys that feel effortless')

@section('content')
    @php
        $heroImagePath = $homeContent?->hero_image_url ?? 'images/hero_bali_1775112308644.png';
        if ($heroImagePath && !\Illuminate\Support\Str::startsWith($heroImagePath, ['http://', 'https://', '/'])) {
            $heroImagePath = \Illuminate\Support\Str::startsWith($heroImagePath, 'images/')
                ? asset($heroImagePath)
                : Storage::url($heroImagePath);
        }
    @endphp
    <section class="hero">
        <div class="hero-bg">
            <img src="{{ $heroImagePath }}" alt="Cliffside ocean view at sunrise">
        </div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <span class="section-tag">{{ $homeContent?->hero_badge ?? 'Designed for explorers' }}</span>
                <h1 class="hero-title">{!! $homeContent?->hero_title ?? 'Where your next <span>extraordinary</span> trip begins.' !!}</h1>
                <p class="hero-subtitle">{{ $homeContent?->hero_subtitle ?? 'Browse handpicked escapes, build flexible itineraries, and book stays that feel like they were made for you.' }}</p>
                <form class="search-wrapper" method="GET" action="{{ route('packages') }}">
                    <div class="search-field">
                        <i class="fa-solid fa-location-dot"></i>
                        <div class="search-input">
                            <label for="destination">Destination</label>
                            <input id="destination" name="destination" type="text" placeholder="Bali, Santorini, Kyoto">
                        </div>
                    </div>
                    <div class="search-field search-field-dates">
                        <i class="fa-regular fa-calendar"></i>
                        <div class="search-input">
                            <label for="dates">Dates</label>
                            <input id="dates" name="dates" type="text" placeholder="Aug 12 - Aug 20">
                        </div>
                    </div>
                    <div class="search-field">
                        <i class="fa-solid fa-user-group"></i>
                        <div class="search-input">
                            <label for="travelers">Travelers</label>
                            <input id="travelers" name="travelers" type="text" placeholder="2 adults">
                        </div>
                    </div>
                    <button class="btn-search" type="submit" aria-label="Search destinations">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
                @if (!empty($homeContent?->hero_cta_text) && !empty($homeContent?->hero_cta_url))
                    <a class="btn-primary" href="{{ $homeContent->hero_cta_url }}" style="margin-top:1.5rem; display:inline-flex;">{{ $homeContent->hero_cta_text }}</a>
                @endif
            </div>
        </div>
    </section>

    <section class="destinations">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">{{ $homeContent?->destinations_badge ?? 'Featured escapes' }}</span>
                <h2 class="section-title">{{ $homeContent?->destinations_title ?? 'Destinations our travelers love' }}</h2>
                @if (!empty($homeContent?->destinations_subtitle))
                    <p class="section-intro">{{ $homeContent->destinations_subtitle }}</p>
                @endif
            </div>
            <div class="destinations-grid">
                @foreach ($featuredHotels as $hotel)
                    @php
                        $imagePath = $hotel->image_url ?: 'images/dest_maldives_1775112608148.png';
                        if ($imagePath && !\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/'])) {
                            $imagePath = \Illuminate\Support\Str::startsWith($imagePath, 'images/')
                                ? asset($imagePath)
                                : Storage::url($imagePath);
                        }
                    @endphp
                    <article class="dest-card">
                        <div class="dest-img-wrap">
                            <img src="{{ $imagePath }}" alt="{{ $hotel->name }}">
                            @if ($hotel->rating)
                                <div class="dest-badge"><i class="fa-solid fa-star"></i> {{ number_format($hotel->rating, 1) }}</div>
                            @endif
                        </div>
                        <div class="dest-info">
                            <div class="dest-location"><i class="fa-solid fa-location-dot"></i> {{ $hotel->city }}, {{ $hotel->country }}</div>
                            <h3 class="dest-title">{{ $hotel->name }}</h3>
                            <div class="dest-meta">
                                <div class="dest-price">From <strong>${{ number_format($hotel->price_per_night, 0) }}</strong> / night</div>
                                <a class="dest-btn" href="{{ route('destinations') }}"><i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="cta-row">
                <div>
                    <h3>Ready for a deeper dive?</h3>
                    <p class="section-intro" style="margin:0; text-align:left;">Explore every destination, vibe, and travel style we offer.</p>
                </div>
                <a class="btn-primary" href="{{ route('destinations') }}">Explore destinations</a>
            </div>
        </div>
    </section>

    <section class="destinations" style="padding-top:0;">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">{{ $homeContent?->packages_badge ?? 'Signature packages' }}</span>
                <h2 class="section-title">{{ $homeContent?->packages_title ?? 'Trips designed for effortless planning' }}</h2>
                @if (!empty($homeContent?->packages_subtitle))
                    <p class="section-intro">{{ $homeContent->packages_subtitle }}</p>
                @endif
            </div>
            <div class="destinations-grid">
                @foreach ($featuredPackages as $package)
                    @php
                        $imagePath = $package->image_url ?: 'images/dest_maldives_1775112608148.png';
                        if ($imagePath && !\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/'])) {
                            $imagePath = \Illuminate\Support\Str::startsWith($imagePath, 'images/')
                                ? asset($imagePath)
                                : Storage::url($imagePath);
                        }
                    @endphp
                    <article class="dest-card">
                        <div class="dest-img-wrap">
                            <img src="{{ $imagePath }}" alt="{{ $package->name }}">
                            <div class="dest-badge"><i class="fa-solid fa-sparkles"></i> {{ ucfirst($package->category) }}</div>
                        </div>
                        <div class="dest-info">
                            <div class="dest-location"><i class="fa-solid fa-location-dot"></i> {{ $package->location }}</div>
                            <h3 class="dest-title">{{ $package->name }}</h3>
                            <div class="dest-meta" style="flex-direction:column; align-items:flex-start; gap:0.5rem;">
                                <div class="dest-price">{{ $package->duration }} · <strong>${{ number_format($package->price, 0) }}</strong></div>
                                <a class="btn-primary" href="{{ route('packages') }}">View package</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">{{ $homeContent?->experiences_badge ?? 'Curated experiences' }}</span>
                <h2 class="section-title">{{ $homeContent?->experiences_title ?? 'Travel moments designed around you' }}</h2>
                <p class="section-intro">{{ $homeContent?->experiences_subtitle ?? 'Choose immersive cultural walks, private wellness resets, or high-energy adventures. Every experience is vetted by our local travel curators.' }}</p>
            </div>
            <div class="card-grid">
                @forelse ($experiences as $experience)
                    <div class="info-card">
                        <span class="pill-tag"><i class="{{ $experience->icon ?: 'fa-solid fa-compass' }}"></i> {{ $experience->title }}</span>
                        <h3>{{ $experience->title }}</h3>
                        <p>{{ $experience->description }}</p>
                    </div>
                @empty
                    <div class="info-card">
                        <h3>More experiences coming soon</h3>
                        <p>We are curating new travel moments. Check back shortly.</p>
                    </div>
                @endforelse
            </div>
            <div class="cta-row">
                <div>
                    <h3>See every TravelNest experience</h3>
                    <p class="section-intro" style="margin:0; text-align:left;">Browse categories built for culture seekers, adventurers, and slow travelers.</p>
                </div>
                <a class="btn-primary" href="{{ route('experiences') }}">Explore experiences</a>
            </div>
        </div>
    </section>

    <section class="page-section" style="padding-top:0;">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">{{ $homeContent?->stories_badge ?? 'Traveler stories' }}</span>
                <h2 class="section-title">{{ $homeContent?->stories_title ?? 'Journeys that feel personal' }}</h2>
                @if (!empty($homeContent?->stories_subtitle))
                    <p class="section-intro">{{ $homeContent->stories_subtitle }}</p>
                @endif
            </div>
            <div class="card-grid">
                @forelse ($stories as $story)
                    @php
                        $imagePath = $story->image_url ?: 'images/dest_swiss_1775112801276.png';
                        if ($imagePath && !\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/'])) {
                            $imagePath = \Illuminate\Support\Str::startsWith($imagePath, 'images/')
                                ? asset($imagePath)
                                : Storage::url($imagePath);
                        }
                        $metaParts = array_filter([$story->category, $story->read_time]);
                        $meta = $metaParts ? implode(' · ', $metaParts) : 'Traveler story';
                        $linkUrl = $story->link_url ?: route('stories');
                    @endphp
                    <article class="story-card">
                        <img src="{{ $imagePath }}" alt="{{ $story->title }}">
                        <div class="story-content">
                            <p class="story-meta">{{ $meta }}</p>
                            <h3>{{ $story->title }}</h3>
                            <p>{{ $story->excerpt }}</p>
                            <a class="btn-primary" href="{{ $linkUrl }}">Read story</a>
                        </div>
                    </article>
                @empty
                    <article class="story-card">
                        <div class="story-content">
                            <p class="story-meta">No stories yet</p>
                            <h3>More TravelNest stories are coming</h3>
                            <p>We are collecting new guest stories and itineraries.</p>
                            <a class="btn-primary" href="{{ route('stories') }}">Visit stories</a>
                        </div>
                    </article>
                @endforelse
            </div>
        </div>
    </section>
@endsection
