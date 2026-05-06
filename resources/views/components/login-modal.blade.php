@php
    // Open the login modal if the session flag is set or login validation failed.
    $openOnLoad = session('show_login') || $errors->getBag('login')->any();
@endphp

{{-- Backdrop wrapper used to toggle the login modal. --}}
<div class="modal-backdrop" data-login-modal data-open-on-load="{{ $openOnLoad ? 'true' : 'false' }}">
    <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
        <button class="modal-close" type="button" data-login-close aria-label="Close login form">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="modal-header">
            <span class="modal-tag">Welcome back</span>
            <h2 id="loginModalTitle">Login to TravelNest</h2>
            <p>Pick up where you left off and plan your next escape.</p>
        </div>

        {{-- Show login-specific error message when authentication fails. --}}
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

        {{-- Login form posts to the auth controller route. --}}
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

            {{-- Remember me and forgot password links. --}}
            <div class="form-row">
                <label class="checkbox-wrap">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                @else
                    <a href="#" class="forgot-link">Forgot Password?</a>
                @endif
            </div>

            <button class="btn-primary modal-submit" type="submit">Login</button>
        </form>

        <div class="form-row" style="margin-top:1rem;">
            <span>New to TravelNest?</span>
            <button class="btn-login" type="button" data-register-open>Sign up</button>
        </div>
        <div class="form-row" style="margin-top:0.5rem;">
            <span>Are you an agency?</span>
            <a class="btn-login" href="{{ route('agency.login') }}">Agency login</a>
        </div>
    </div>
</div>
