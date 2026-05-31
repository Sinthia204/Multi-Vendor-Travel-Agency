@extends('layouts.app')

@section('title', 'TravelNest - Stories')

@section('content')
    <section class="page-hero">
        <div class="page-hero-bg">
            @php
                $heroImage = $pageHero?->background_image_url ?: 'images/dest_santorini_1775112332350.png';
                if ($heroImage && !\Illuminate\Support\Str::startsWith($heroImage, ['http://', 'https://', '/'])) {
                    $heroImage = \Illuminate\Support\Str::startsWith($heroImage, 'images/')
                        ? asset($heroImage)
                        : Storage::url($heroImage);
                }
            @endphp
            <img src="{{ $heroImage }}" alt="Travelers overlooking the sea in Santorini">
        </div>
<div class="container">
            <div class="page-hero-content">
                <span class="section-tag">{{ $pageHero?->badge ?? 'Stories' }}</span>
                <h1 class="page-hero-title">{{ $pageHero?->title ?? 'Real journeys, memorable details.' }}</h1>
                <p class="page-hero-subtitle">{{ $pageHero?->subtitle ?? 'TravelNest guests share the moments, people, and surprises that made their trips unforgettable.' }}</p>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Traveler diaries</span>
                <h2 class="section-title">Fresh inspiration from our community</h2>
                <p class="section-intro">Discover how travelers used TravelNest to plan romantic escapes, adventure-packed getaways, and slow travel itineraries.</p>
            </div>
            <div class="card-grid">
                @forelse ($stories as $story)
                    @php
                        $imagePath = $story->image_url ?: 'images/dest_tokyo_1775112740002.png';
                        if ($imagePath && !\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://', '/'])) {
                            $imagePath = \Illuminate\Support\Str::startsWith($imagePath, 'images/')
                                ? asset($imagePath)
                                : Storage::url($imagePath);
                        }
                        $metaParts = array_filter([$story->category, $story->read_time]);
                        $meta = $metaParts ? implode(' · ', $metaParts) : 'Traveler story';
                        $linkUrl = $story->link_url ?: '#';
                    @endphp
                    <article class="story-card">
                        <img src="{{ $imagePath }}" alt="{{ $story->title }}">
                        <div class="story-content">
                            <p class="story-meta">{{ $meta }}</p>
                            <h3>{{ $story->title }}</h3>
                            <p>{{ $story->excerpt }}</p>
                            <a class="btn-primary" href="{{ $linkUrl }}">Read more</a>
                        </div>
                    </article>
                @empty
                    <article class="story-card">
                        <div class="story-content">
                            <p class="story-meta">No stories yet</p>
                            <h3>More stories are coming</h3>
                            <p>We are collecting fresh TravelNest journeys for this page.</p>
                        </div>
                    </article>
                @endforelse
            </div>
        </div>
    </section>
@endsection
