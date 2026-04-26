@extends('layouts.app')

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

    <section class="destinations" id="packages">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Featured packages</span>
                <h2 class="section-title">Book a curated trip in minutes</h2>
            </div>
            <div class="destinations-grid">
                @php
                    $packages = [
                        [
                            'name' => "Cox's Bazar Premium Beach Tour",
                            'price' => 450,
                            'duration' => '5 Days',
                            'location' => "Cox's Bazar",
                            'image' => asset('images/dest_maldives_1775112608148.png'),
                        ],
                        [
                            'name' => 'Sundarbans Mangrove Explorer',
                            'price' => 320,
                            'duration' => '3 Days',
                            'location' => 'Sundarbans',
                            'image' => asset('images/dest_machu_picchu_1775112348652.png'),
                        ],
                        [
                            'name' => 'Sajek Valley Deluxe',
                            'price' => 520,
                            'duration' => '5 Days',
                            'location' => 'Sajek Valley',
                            'image' => asset('images/dest_swiss_1775112801276.png'),
                        ],
                    ];
                @endphp

                @foreach ($packages as $package)
                    <article class="dest-card">
                        <div class="dest-img-wrap">
                            <img src="{{ $package['image'] }}" alt="{{ $package['name'] }}">
                            <div class="dest-badge"><i class="fa-solid fa-sparkles"></i> Popular</div>
                        </div>
                        <div class="dest-info">
                            <div class="dest-location"><i class="fa-solid fa-location-dot"></i> {{ $package['location'] }}
                            </div>
                            <h3 class="dest-title">{{ $package['name'] }}</h3>
                            <div class="dest-meta" style="flex-direction:column;align-items:flex-start;gap:0.75rem;">
                                <div class="dest-price">From <strong>${{ $package['price'] }}</strong> / person</div>
                                <div class="dest-price">Duration: <strong>{{ $package['duration'] }}</strong></div>
                            </div>
                            <form method="POST" action="{{ route('bookings.from-package') }}" class="package-booking-form">
                                @csrf
                                <input type="hidden" name="package_name" value="{{ $package['name'] }}">
                                <input type="hidden" name="amount" value="{{ $package['price'] }}">
                                <input type="date" name="travel_date" class="package-input">
                                <input type="text" name="coupon_code" class="package-input" placeholder="Coupon code">
                                <button type="submit" class="btn-primary">Book Now</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="destinations" id="destinations">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Curated escapes</span>
                <h2 class="section-title">Top destinations this season</h2>
            </div>
            <div class="destinations-grid">
                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_maldives_1775112608148.png') }}"
                            alt="Overwater villas in the Maldives">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.9</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Maldives</div>
                        <h3 class="dest-title">Lagoon Serenity Retreat</h3>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$420</strong> / night</div>
                            <a class="dest-btn" href="#"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_santorini_1775112332350.png') }}"
                            alt="Whitewashed cliffside homes in Santorini">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.8</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Santorini, Greece</div>
                        <h3 class="dest-title">Caldera Sunset Getaway</h3>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$310</strong> / night</div>
                            <a class="dest-btn" href="#"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="dest-card" id="experiences">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_tokyo_1775112740002.png') }}" alt="Tokyo skyline at night">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.9</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Tokyo, Japan</div>
                        <h3 class="dest-title">Neon Nights & Hidden Eats</h3>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$185</strong> / night</div>
                            <a class="dest-btn" href="#"><i class="fa-solid fa-arrow-right"></i></a>
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
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$260</strong> / night</div>
                            <a class="dest-btn" href="#"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>

                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_machu_picchu_1775112348652.png') }}"
                            alt="Machu Picchu ruins at dawn">
                        <div class="dest-badge"><i class="fa-solid fa-star"></i> 4.8</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Peru</div>
                        <h3 class="dest-title">Inca Trail Immersion</h3>
                        <div class="dest-meta">
                            <div class="dest-price">From <strong>$210</strong> / night</div>
                            <a class="dest-btn" href="#"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="destinations" id="stories">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Travel stories</span>
                <h2 class="section-title">Travel that feels personal</h2>
            </div>
            <div class="destinations-grid">
                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_santorini_1775112332350.png') }}" alt="Santorini villa terrace">
                        <div class="dest-badge"><i class="fa-solid fa-heart"></i> 126 saved</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Couple retreats</div>
                        <h3 class="dest-title">Slow mornings by the caldera</h3>
                        <div class="dest-meta">
                            <div class="dest-price">6-day itinerary</div>
                            <a class="dest-btn" href="#"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_tokyo_1775112740002.png') }}" alt="Tokyo street food market">
                        <div class="dest-badge"><i class="fa-solid fa-heart"></i> 88 saved</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Food trails</div>
                        <h3 class="dest-title">Tokyo after dark, one bite at a time</h3>
                        <div class="dest-meta">
                            <div class="dest-price">4-night foodie map</div>
                            <a class="dest-btn" href="#"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
                <article class="dest-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset('images/dest_swiss_1775112801276.png') }}" alt="Hiking in the Swiss Alps">
                        <div class="dest-badge"><i class="fa-solid fa-heart"></i> 102 saved</div>
                    </div>
                    <div class="dest-info">
                        <div class="dest-location"><i class="fa-solid fa-location-dot"></i> Outdoor escapes</div>
                        <h3 class="dest-title">Alpine adventures with a soft landing</h3>
                        <div class="dest-meta">
                            <div class="dest-price">5-day trail guide</div>
                            <a class="dest-btn" href="#"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a class="footer-logo" href="/"><i class="fa-solid fa-compass"></i> TravelNest</a>
                    <p class="footer-text">Designing journeys that feel elevated, effortless, and unforgettable.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="footer-heading">Company</h4>
                    <ul class="footer-links">
                        <li><a href="#">About TravelNest</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-heading">Explore</h4>
                    <ul class="footer-links">
                        <li><a href="#destinations">Destinations</a></li>
                        <li><a href="#experiences">Experiences</a></li>
                        <li><a href="#stories">Stories</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-heading">Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help center</a></li>
                        <li><a href="#">Cancellation options</a></li>
                        <li><a href="#">Contact support</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">(c) 2026 TravelNest. All rights reserved.</div>
        </div>
    </footer>
@endsection
