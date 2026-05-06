@extends('layouts.app')

@section('title', 'TravelNest - Experiences')

@section('content')
    <section class="page-hero">
        <div class="page-hero-bg">
            @php
                $heroImage = $pageHero?->background_image_url ?: 'images/dest_tokyo_1775112740002.png';
                if ($heroImage && !\Illuminate\Support\Str::startsWith($heroImage, ['http://', 'https://', '/'])) {
                    $heroImage = \Illuminate\Support\Str::startsWith($heroImage, 'images/')
                        ? asset($heroImage)
                        : Storage::url($heroImage);
                }
            @endphp
            <img src="{{ $heroImage }}" alt="Travelers exploring a vibrant city at night">
        </div>
        <div class="container">
            <div class="page-hero-content">
                <span class="section-tag">{{ $pageHero?->badge ?? 'Experiences' }}</span>
                <h1 class="page-hero-title">{{ $pageHero?->title ?? 'Moments curated by local insiders.' }}</h1>
                <p class="page-hero-subtitle">{{ $pageHero?->subtitle ?? 'Choose immersive activities, luxury touches, and cultural discoveries created exclusively for TravelNest guests.' }}</p>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Experience types</span>
                <h2 class="section-title">Adventure, wellness, culture, and beyond</h2>
                <p class="section-intro">Every experience includes a personal host, flexible scheduling, and concierge support.</p>
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
                        <h3>New experiences are on the way</h3>
                        <p>We are curating the next set of TravelNest adventures.</p>
                    </div>
                @endforelse
            </div>

            <div class="cta-row">
                <div>
                    <h3>Want a custom mix of experiences?</h3>
                    <p class="section-intro" style="margin:0; text-align:left;">Tell us your pace and passions and we will design a one-of-a-kind itinerary.</p>
                </div>
                <a class="btn-primary" href="{{ route('contact') }}">Build my itinerary</a>
            </div>
        </div>
    </section>
@endsection
