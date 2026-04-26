@extends('layouts.app')

@section('title', 'TravelNest - Plan journeys that feel effortless')

@section('content')
    <section class="hero">
        <div class="hero-bg">
            <img src="{{ asset('images/hero_bali_1775112308644.png') }}" alt="Cliffside ocean view at sunrise">
        </div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <span class="section-tag">Designed for explorers</span>
                <h1 class="hero-title">Where your next <span>extraordinary</span> trip begins.</h1>
                <p class="hero-subtitle">Browse handpicked escapes, build flexible itineraries, and book stays that feel
                    like they were made for you.</p>
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
            </div>
        </div>
    </section>

    <section class="destinations">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Featured escapes</span>
                <h2 class="section-title">Destinations our travelers love</h2>
            </div>
            <div class="destinations-grid">
                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_maldives_1775112608148.png') }}" alt="Overwater villas in the Maldives">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.9</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Maldives</div>
                        <h3 class="dest-title">Lagoon Serenity Retreat</h3>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$420</strong> / night</div>
                            <a class="dest-btn" href="{{ route('destinations') }}"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_santorini_1775112332350.png') }}" alt="Whitewashed cliffside homes in Santorini">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.8</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Santorini, Greece</div>
                        <h3 class="dest-title">Caldera Sunset Getaway</h3>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$310</strong> / night</div>
                            <a class="dest-btn" href="{{ route('destinations') }}"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_tokyo_1775112740002.png') }}" alt="Tokyo skyline at night">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.9</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Tokyo, Japan</div>
                        <h3 class="dest-title">Neon Nights & Hidden Eats</h3>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$185</strong> / night</div>
                            <a class="dest-btn" href="{{ route('destinations') }}"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
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
                <span class="section-tag">Signature packages</span>
                <h2 class="section-title">Trips designed for effortless planning</h2>
            </div>
            <div class="destinations-grid">
                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_machu_picchu_1775112348652.png') }}" alt="Machu Picchu ruins at dawn">
                        <div class="dest-badge"><i class="fa-solid fa-sparkles"></i> Popular</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Peru</div>
                        <h3 class="dest-title">Inca Trail Immersion</h3>
                        <div class="dest-meta" style="flex-direction:column; align-items:flex-start; gap:0.5rem;">
                            <div class="dest-price">6 Days · <strong>$1,240</strong></div>
                            <a class="btn-primary" href="{{ route('packages') }}">View package</a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_swiss_1775112801276.png') }}" alt="Swiss Alps with alpine lake">
                        <div class="dest-badge"><i class="fa-solid fa-sparkles"></i> Trending</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Swiss Alps</div>
                        <h3 class="dest-title">Alpine Wellness Lodge</h3>
                        <div class="dest-meta" style="flex-direction:column; align-items:flex-start; gap:0.5rem;">
                            <div class="dest-price">5 Days · <strong>$980</strong></div>
                            <a class="btn-primary" href="{{ route('packages') }}">View package</a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_santorini_1775112332350.png') }}" alt="Santorini villa terrace">
                        <div class="dest-badge"><i class="fa-solid fa-sparkles"></i> New</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Greece</div>
                        <h3 class="dest-title">Aegean Retreat Collection</h3>
                        <div class="dest-meta" style="flex-direction:column; align-items:flex-start; gap:0.5rem;">
                            <div class="dest-price">4 Days · <strong>$760</strong></div>
                            <a class="btn-primary" href="{{ route('packages') }}">View package</a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Curated experiences</span>
                <h2 class="section-title">Travel moments designed around you</h2>
                <p class="section-intro">Choose immersive cultural walks, private wellness resets, or high-energy adventures. Every experience is vetted by our local travel curators.</p>
            </div>
            <div class="card-grid">
                <div class="info-card">
                    <span class="pill-tag"><i class="fa-solid fa-utensils"></i> Culinary</span>
                    <h3>Market-to-table tasting journeys</h3>
                    <p>Meet chefs, explore hidden street markets, and savor dishes crafted just for TravelNest guests.</p>
                </div>
                <div class="info-card">
                    <span class="pill-tag"><i class="fa-solid fa-person-hiking"></i> Adventure</span>
                    <h3>Signature treks and scenic escapes</h3>
                    <p>From sunrise hikes to coastal bike tours, find the right mix of movement and calm.</p>
                </div>
                <div class="info-card">
                    <span class="pill-tag"><i class="fa-solid fa-spa"></i> Wellness</span>
                    <h3>Restorative stays with private guides</h3>
                    <p>Balance your itinerary with spa rituals, yoga retreats, and serene hotel sanctuaries.</p>
                </div>
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
                <span class="section-tag">Traveler stories</span>
                <h2 class="section-title">Journeys that feel personal</h2>
            </div>
            <div class="card-grid">
                <article class="story-card">
                    <img src="{{ asset('images/dest_tokyo_1775112740002.png') }}" alt="Tokyo street food market">
                    <div class="story-content">
                        <p class="story-meta">City escapes · 5 min read</p>
                        <h3>Tokyo after dark, one bite at a time</h3>
                        <p>Private tastings, neon alleyways, and a guide who knew every hidden ramen bar.</p>
                        <a class="btn-primary" href="{{ route('stories') }}">Read story</a>
                    </div>
                </article>
                <article class="story-card">
                    <img src="{{ asset('images/dest_santorini_1775112332350.png') }}" alt="Santorini cliffside balconies">
                    <div class="story-content">
                        <p class="story-meta">Romance · 4 min read</p>
                        <h3>Slow mornings above the Aegean</h3>
                        <p>A honeymoon built around blue-domed sunsets and private sailings.</p>
                        <a class="btn-primary" href="{{ route('stories') }}">Read story</a>
                    </div>
                </article>
                <article class="story-card">
                    <img src="{{ asset('images/dest_swiss_1775112801276.png') }}" alt="Hiking in the Swiss Alps">
                    <div class="story-content">
                        <p class="story-meta">Adventure · 6 min read</p>
                        <h3>Alpine trails with a wellness twist</h3>
                        <p>Guided hikes by day, alpine spa rituals by night, and postcard-worthy stays.</p>
                        <a class="btn-primary" href="{{ route('stories') }}">Read story</a>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection
