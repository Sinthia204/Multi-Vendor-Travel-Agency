@extends('layouts.app')

@section('title', 'TravelNest - Register')

@section('content')
    <div class="modal-backdrop open">
        <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="registerPageTitle">
            <a class="modal-close" href="{{ route('home') }}" aria-label="Close registration form">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <div class="modal-header">
                <span class="modal-tag">Start your journey</span>
                <h2 id="registerPageTitle">Create your TravelNest account</h2>
                <p>Join now to save trips, manage bookings, and get exclusive deals.</p>
            </div>

            <form method="POST" action="{{ route('register.submit') }}" class="modal-form">
                @csrf
                <div class="form-field">
                    <label for="registerName">Full Name</label>
                    <input id="registerName" type="text" name="name" value="{{ old('name') }}" required
                        autocomplete="name" placeholder="Your name">
                    @error('name', 'register')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="registerEmail">Email</label>
                    <input id="registerEmail" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email" placeholder="you@example.com">
                    @error('email', 'register')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="registerPassword">Password</label>
                    <input id="registerPassword" type="password" name="password" required autocomplete="new-password"
                        placeholder="Create a password">
                    @error('password', 'register')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="registerPasswordConfirm">Confirm Password</label>
                    <input id="registerPasswordConfirm" type="password" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Confirm your password">
                </div>

                <button class="btn-primary modal-submit" type="submit">Create Account</button>
            </form>

            <div class="form-row" style="margin-top:1rem;">
                <span>Already have an account?</span>
                <a class="btn-login" href="{{ route('login') }}">Log in</a>
            </div>
            <div class="form-row" style="margin-top:0.5rem;">
                <span>Are you an agency?</span>
                <a class="btn-login" href="{{ route('agency.register') }}">Partner with us</a>
            </div>
        </div>
    </div>
@endsection
