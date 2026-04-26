{{-- Use the shared admin layout for consistent framing. --}}
@extends('layouts.admin')

{{-- Page title is based on the placeholder name. --}}
@section('title', ucfirst($page))
{{-- Header title matches the module name. --}}
@section('page-title', ucfirst($page))

@section('content')
    <div class="placeholder-page">
        <div>
            {{-- Map placeholder pages to matching icons. --}}
            @php
                $icons = [
                    'hotels' => 'fa-hotel',
                    'transport' => 'fa-bus',
                    'coupons' => 'fa-tags',
                    'reports' => 'fa-chart-bar',
                    'settings' => 'fa-cog',
                ];
                $icon = $icons[$page] ?? 'fa-puzzle-piece';
            @endphp
            <div class="placeholder-icon">
                <i class="fas {{ $icon }}"></i>
            </div>
            <h2 class="placeholder-title">{{ ucfirst($page) }}</h2>
            <p class="placeholder-subtitle">Coming Soon</p>
            <p class="placeholder-text">This module is under development</p>
            {{-- Return to the admin dashboard. --}}
            <a href="{{ url('/admin') }}" class="btn-outline-tn">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
@endsection
