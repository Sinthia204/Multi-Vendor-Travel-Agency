@extends('layouts.app')

@section('title', 'TravelNest - Packages')

@section('content')
    <section class="page-hero">
        <div class="page-hero-bg">
            <img src="{{ asset('images/dest_swiss_1775112801276.png') }}" alt="Mountain lake in the Swiss Alps">
        </div>
        <div class="container">
            <div class="page-hero-content">
                <span class="section-tag">Packages</span>
                <h1 class="page-hero-title">Curated itineraries, zero planning stress.</h1>
                <p class="page-hero-subtitle">Choose a ready-to-book package or let TravelNest personalize every detail for your travelers and budget.</p>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Signature packages</span>
                <h2 class="section-title">Flexible trips built around how you travel</h2>
                <p class="section-intro">Each package includes curated stays, private transfers, and concierge support from booking to arrival.</p>
            </div>
            @if (!empty($destination) || !empty($dates) || !empty($travelers))
                <div class="mini-stat" style="margin-bottom:1.5rem;">
                    <div class="mini-stat-label" style="text-transform:none;">
                        Showing results for
                        <strong>{{ $destination ?: 'All destinations' }}</strong>
                        @if ($dates)
                            · Dates: {{ $dates }}
                        @endif
                        @if ($travelers)
                            · Travelers: {{ $travelers }}
                        @endif
                    </div>
                </div>
            @endif

            <div class="destinations-grid">
                @forelse ($packages as $package)
                    @php
                        $image = $package->image_url ?: 'images/dest_maldives_1775112608148.png';
                        $image = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://', '/']) ? $image : asset($image);
                    @endphp
                    <article class="dest-card">
                        <div class="dest-img-wrap">
                            <img src="{{ $image }}" alt="{{ $package->name }}">
                            <div class="dest-badge"><i class="fa-solid fa-sparkles"></i> {{ ucfirst($package->category) }}</div>
                        </div>
                        <div class="dest-info">
                            <div class="dest-location"><i class="fa-solid fa-location-dot"></i> {{ $package->location }}</div>
                            <h3 class="dest-title">{{ $package->name }}</h3>
                            <div class="dest-meta" style="flex-direction:column; align-items:flex-start; gap:0.6rem;">
                                <div class="dest-price">{{ $package->duration }} · <strong>${{ number_format($package->price, 0) }}</strong></div>
                                <div class="dest-price" style="font-size:0.85rem;">Agency: {{ $package->agency?->name ?? 'TravelNest' }}</div>
                            </div>
                            @auth
                                <form method="POST" action="{{ route('bookings.from-package') }}" class="package-booking-form">
                                    @csrf
                                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                                    <input type="hidden" name="package_name" value="{{ $package->name }}">
                                    <input type="hidden" name="amount" value="{{ $package->price }}">
                                    <input type="date" name="travel_date" class="package-input">
                                    <input type="text" name="coupon_code" class="package-input" placeholder="Coupon code">
                                    <button type="submit" class="btn-primary">Book Now</button>
                                </form>
                            @else
                                <div style="margin-top:1rem; display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap;">
                                    <button class="btn-primary" type="button" data-login-open>Login to book</button>
                                    <a class="btn-outline-tn" href="{{ route('register') }}">Create account</a>
                                </div>
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="info-card" style="grid-column:1 / -1; text-align:center;">
                        <h3>No packages found</h3>
                        <p>Try a different destination or clear the search.</p>
                        <a class="btn-primary" href="{{ route('packages') }}">View all packages</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
