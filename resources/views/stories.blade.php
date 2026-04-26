@extends('layouts.app')

@section('title', 'TravelNest - Stories')

@section('content')
    <section class="page-hero">
        <div class="page-hero-bg">
            <img src="{{ asset('images/dest_santorini_1775112332350.png') }}" alt="Travelers overlooking the sea in Santorini">
        </div>
        <div class="container">
            <div class="page-hero-content">
                <span class="section-tag">Stories</span>
                <h1 class="page-hero-title">Real journeys, memorable details.</h1>
                <p class="page-hero-subtitle">TravelNest guests share the moments, people, and surprises that made their trips unforgettable.</p>
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
                <article class="story-card">
                    <img src="{{ asset('images/dest_tokyo_1775112740002.png') }}" alt="Tokyo street food market">
                    <div class="story-content">
                        <p class="story-meta">City escapes · 5 min read</p>
                        <h3>Tokyo after dark, one bite at a time</h3>
                        <p>Late-night food tours, hidden jazz bars, and a guide who knew every neon alleyway.</p>
                        <a class="btn-primary" href="#">Read more</a>
                    </div>
                </article>
                <article class="story-card">
                    <img src="{{ asset('images/dest_swiss_1775112801276.png') }}" alt="Hiking in the Swiss Alps">
                    <div class="story-content">
                        <p class="story-meta">Adventure · 6 min read</p>
                        <h3>Alpine trails with a wellness twist</h3>
                        <p>Guided hikes by day, spa rituals by night, and alpine lodges that felt like home.</p>
                        <a class="btn-primary" href="#">Read more</a>
                    </div>
                </article>
                <article class="story-card">
                    <img src="{{ asset('images/dest_maldives_1775112608148.png') }}" alt="Overwater villas in the Maldives">
                    <div class="story-content">
                        <p class="story-meta">Slow travel · 4 min read</p>
                        <h3>Lagoon mornings and private dinners</h3>
                        <p>Sunrise paddleboarding, reef snorkeling, and a villa host who planned every surprise.</p>
                        <a class="btn-primary" href="#">Read more</a>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection
