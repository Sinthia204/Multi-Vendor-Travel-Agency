@extends('layouts.app')

@section('title', 'TravelNest - Experiences')

@section('content')
    <section class="page-hero">
        <div class="page-hero-bg">
            <img src="{{ asset('images/dest_tokyo_1775112740002.png') }}" alt="Travelers exploring a vibrant city at night">
        </div>
        <div class="container">
            <div class="page-hero-content">
                <span class="section-tag">Experiences</span>
                <h1 class="page-hero-title">Moments curated by local insiders.</h1>
                <p class="page-hero-subtitle">Choose immersive activities, luxury touches, and cultural discoveries created exclusively for TravelNest guests.</p>
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
                <div class="info-card">
                    <span class="pill-tag"><i class="fa-solid fa-person-hiking"></i> Adventure</span>
                    <h3>Summit hikes and coastal trails</h3>
                    <p>Private guides, scenic routes, and curated picnic setups for every skill level.</p>
                </div>
                <div class="info-card">
                    <span class="pill-tag"><i class="fa-solid fa-spa"></i> Wellness</span>
                    <h3>Restorative retreats</h3>
                    <p>Daily yoga sessions, spa rituals, and mindfulness moments by the sea.</p>
                </div>
                <div class="info-card">
                    <span class="pill-tag"><i class="fa-solid fa-landmark"></i> Culture</span>
                    <h3>Art, history, and heritage walks</h3>
                    <p>Private museum access, artisan studios, and storytelling tours with locals.</p>
                </div>
                <div class="info-card">
                    <span class="pill-tag"><i class="fa-solid fa-wine-glass"></i> Culinary</span>
                    <h3>Chef-led tastings</h3>
                    <p>Market visits, chef tables, and vineyard stays crafted for food lovers.</p>
                </div>
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
