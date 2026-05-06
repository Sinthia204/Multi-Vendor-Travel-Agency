@extends('layouts.app')

@section('title', 'TravelNest - Contact')

@section('content')
    <section class="page-hero">
        <div class="page-hero-bg">
            @php
                $heroImage = $pageHero?->background_image_url ?: 'images/dest_machu_picchu_1775112348652.png';
                if ($heroImage && !\Illuminate\Support\Str::startsWith($heroImage, ['http://', 'https://', '/'])) {
                    $heroImage = \Illuminate\Support\Str::startsWith($heroImage, 'images/')
                        ? asset($heroImage)
                        : Storage::url($heroImage);
                }
            @endphp
            <img src="{{ $heroImage }}" alt="Machu Picchu ruins in the morning light">
        </div>
        <div class="container">
            <div class="page-hero-content">
                <span class="section-tag">{{ $pageHero?->badge ?? 'Contact' }}</span>
                <h1 class="page-hero-title">{{ $pageHero?->title ?? 'Let us design your next journey.' }}</h1>
                <p class="page-hero-subtitle">{{ $pageHero?->subtitle ?? 'Share your travel dreams and our concierge team will craft a custom itinerary within 24 hours.' }}</p>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Reach out</span>
                <h2 class="section-title">Talk to a TravelNest travel designer</h2>
                <p class="section-intro">We are available by email, phone, or chat Monday through Saturday, 8am to 8pm.</p>
            </div>

            <div class="contact-grid">
                <div class="contact-card">
                    <h3>Contact information</h3>
                    <div class="contact-list">
                        <div><i class="fa-solid fa-phone"></i> {{ getSetting('contact_phone', '+1 (555) 210-8842') }}</div>
                        <div><i class="fa-solid fa-envelope"></i> {{ getSetting('contact_email', 'hello@travelnest.com') }}</div>
                        <div><i class="fa-solid fa-location-dot"></i> {{ getSetting('contact_address', '86 Harbor Street, Suite 420, Seattle, WA') }}</div>
                    </div>
                    @php
                        $mapEmbed = getSetting('contact_map_embed');
                    @endphp
                    <div class="map-placeholder" style="margin-top:1.5rem;">
                        @if ($mapEmbed)
                            {!! $mapEmbed !!}
                        @else
                            Map placeholder
                        @endif
                    </div>
                </div>
                <div class="contact-card">
                    <h3>Send a message</h3>
                    <form class="contact-form" onsubmit="return false;">
                        <input class="form-input" type="text" name="name" placeholder="Your name" required>
                        <input class="form-input" type="email" name="email" placeholder="Email address" required>
                        <input class="form-input" type="text" name="subject" placeholder="Trip details or subject" required>
                        <textarea class="form-textarea" name="message" placeholder="Tell us about your ideal trip" required></textarea>
                        <button class="btn-primary" type="submit">Send message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
