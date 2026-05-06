@extends('layouts.app')

@section('title', 'TravelNest - Agency Login')

@section('content')
    <div class="modal-backdrop open">
        <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="agencyLoginTitle">
            <a class="modal-close" href="{{ route('home') }}" aria-label="Close login form">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <div class="modal-header">
                <span class="modal-tag">Agency partners</span>
                <h2 id="agencyLoginTitle">Agency login</h2>
                <p>Manage your listings and bookings in one place.</p>
            </div>

            @if ($errors->getBag('agency_login')->has('login'))
                <div class="modal-alert">
                    {{ $errors->getBag('agency_login')->first('login') }}
                </div>
            @endif

            <form method="POST" action="{{ route('agency.login.submit') }}" class="modal-form">
                @csrf
                <div class="form-field">
                    <label for="agencyLoginEmail">Email</label>
                    <input id="agencyLoginEmail" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="username" placeholder="you@example.com">
                </div>

                <div class="form-field">
                    <label for="agencyLoginPassword">Password</label>
                    <input id="agencyLoginPassword" type="password" name="password" required
                        autocomplete="current-password" placeholder="Enter your password">
                </div>

                <div class="form-row">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <button class="btn-primary modal-submit" type="submit">Login</button>
            </form>

            <div class="form-row" style="margin-top:1rem;">
                <span>Need an agency account?</span>
                <a class="btn-login" href="{{ route('agency.register') }}">Register your agency</a>
            </div>
        </div>
    </div>
@endsection
