@extends('layouts.app')

@section('title', 'TravelNest - Login')

@section('content')
    <div class="modal-backdrop open">
        <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="loginPageTitle">
            <a class="modal-close" href="{{ route('home') }}" aria-label="Close login form">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <div class="modal-header">
                <span class="modal-tag">Welcome back</span>
                <h2 id="loginPageTitle">Login to TravelNest</h2>
                <p>Pick up where you left off and plan your next escape.</p>
            </div>

            @if ($errors->getBag('login')->has('login'))
                <div class="modal-alert">
                    {{ $errors->getBag('login')->first('login') }}
                </div>
            @endif
            @if (session('register_success'))
                <div class="modal-alert" style="background: rgba(45, 138, 122, 0.15); color: #0f766e;">
                    {{ session('register_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="modal-form">
                @csrf
                <div class="form-field">
                    <label for="loginEmail">Email</label>
                    <input id="loginEmail" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="username" placeholder="you@example.com">
                    @error('email', 'login')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="loginPassword">Password</label>
                    <input id="loginPassword" type="password" name="password" required autocomplete="current-password"
                        placeholder="Enter your password">
                    @error('password', 'login')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>

                <button class="btn-primary modal-submit" type="submit">Login</button>
            </form>

            <div class="form-row" style="margin-top:1rem;">
                <span>New to TravelNest?</span>
                <a class="btn-login" href="{{ route('register') }}">Sign up</a>
            </div>
        </div>
    </div>
@endsection
