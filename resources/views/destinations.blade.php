@extends('layouts.app')

@section('title', 'TravelNest - Destinations')

@section('content')
    <section class="page-hero">
        <div class="page-hero-bg">
            @php
                $heroImage = $pageHero?->background_image_url ?: 'images/dest_maldives_1775112608148.png';
                if ($heroImage && !\Illuminate\Support\Str::startsWith($heroImage, ['http://', 'https://', '/'])) {
                    $heroImage = \Illuminate\Support\Str::startsWith($heroImage, 'images/')
                        ? asset($heroImage)
                        : Storage::url($heroImage);
                }
            @endphp
            <img src="{{ $heroImage }}" alt="Turquoise lagoon in the Maldives">
        </div>
        <div class="container">
            <div class="page-hero-content">
                <span class="section-tag">{{ $pageHero?->badge ?? 'Destinations' }}</span>
                <h1 class="page-hero-title">{{ $pageHero?->title ?? 'Discover places that match your pace.' }}</h1>
                <p class="page-hero-subtitle">{{ $pageHero?->subtitle ?? 'From island hideaways to alpine wellness stays, TravelNest curates destinations that feel tailored to your travel style.' }}</p>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Popular right now</span>
                <h2 class="section-title">Top picks for 2026 travelers</h2>
                <p class="section-intro">Explore destinations chosen for their culture, scenery, and unforgettable hospitality. Every location includes handpicked stays and local hosts.</p>
            </div>
            <div class="destinations-grid">
                @forelse ($hotels as $hotel)
                    @php
                        $imagePath = $hotel->image_url ?: 'images/dest_santorini_1775112332350.png';
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
                            @if ($hotel->description)
                                <p class="dest-price">{{ \Illuminate\Support\Str::limit($hotel->description, 100) }}</p>
                            @endif
                            <div class="dest-meta">
                                <div class="dest-price">From <strong>${{ number_format($hotel->price_per_night, 0) }}</strong> / night</div>
                                <a class="dest-btn" href="{{ route('packages') }}"><i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="info-card" style="grid-column:1 / -1; text-align:center;">
                        <h3>No hotels found</h3>
                        <p>Check back soon for new stays.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-end mt-4">
                {{ $hotels->links('pagination::bootstrap-5') }}
            </div>

            <div class="cta-row">
                <div>
                    <h3>Need help choosing your next stop?</h3>
                    <p class="section-intro" style="margin:0; text-align:left;">Our concierge team can build the perfect itinerary for any travel style.</p>
                </div>
                <a class="btn-primary" href="{{ route('contact') }}">Talk to a travel designer</a>
            </div>
        </div>
    </section>
@endsection
