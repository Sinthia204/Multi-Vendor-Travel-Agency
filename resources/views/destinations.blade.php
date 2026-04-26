@extends('layouts.app')

@section('title', 'TravelNest - Destinations')

@section('content')
    <section class="page-hero">
        <div class="page-hero-bg">
            <img src="{{ asset('images/dest_maldives_1775112608148.png') }}" alt="Turquoise lagoon in the Maldives">
        </div>
        <div class="container">
            <div class="page-hero-content">
                <span class="section-tag">Destinations</span>
                <h1 class="page-hero-title">Discover places that match your pace.</h1>
                <p class="page-hero-subtitle">From island hideaways to alpine wellness stays, TravelNest curates destinations that feel tailored to your travel style.</p>
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
                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_santorini_1775112332350.png') }}" alt="Santorini cliffside homes">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.9</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Santorini, Greece</div>
                        <h3 class="dest-title">Cycladic Sunset Escape</h3>
                        <p class="dest-price">Whitewashed villas, private yacht dinners, and seaside tasting menus.</p>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$320</strong> / night</div>
                            <a class="dest-btn" href="{{ route('packages') }}"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_tokyo_1775112740002.png') }}" alt="Tokyo skyline at night">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.8</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Tokyo, Japan</div>
                        <h3 class="dest-title">Neon District Discovery</h3>
                        <p class="dest-price">Local guides, rooftop stays, and curated food trails.</p>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$210</strong> / night</div>
                            <a class="dest-btn" href="{{ route('packages') }}"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_swiss_1775112801276.png') }}" alt="Swiss Alps with alpine lake">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.7</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Swiss Alps</div>
                        <h3 class="dest-title">Alpine Wellness Lodge</h3>
                        <p class="dest-price">Panoramic trails, spa rituals, and scenic rail journeys.</p>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$260</strong> / night</div>
                            <a class="dest-btn" href="{{ route('packages') }}"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_machu_picchu_1775112348652.png') }}" alt="Machu Picchu ruins at dawn">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.8</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Peru</div>
                        <h3 class="dest-title">Sacred Valley Immersion</h3>
                        <p class="dest-price">Guided treks, boutique lodges, and private history walks.</p>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$190</strong> / night</div>
                            <a class="dest-btn" href="{{ route('packages') }}"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
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
