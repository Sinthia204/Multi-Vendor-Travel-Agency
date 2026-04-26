@php
    // Open the register modal if the session flag is set or validation failed.
    $openOnLoad = session('show_register') || $errors->getBag('register')->any();
@endphp

{{-- Backdrop wrapper used to toggle the registration modal. --}}
<div class="modal-backdrop" data-register-modal data-open-on-load="{{ $openOnLoad ? 'true' : 'false' }}">
    <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="registerModalTitle">
        <button class="modal-close" type="button" data-register-close aria-label="Close registration form">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="modal-header">
            <span class="modal-tag">Start your journey</span>
            <h2 id="registerModalTitle">Create your TravelNest account</h2>
            <p>Join now to save trips, manage bookings, and get exclusive deals.</p>
        </div>

        {{-- Registration form posts to the registration controller route. --}}
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

            {{-- Submit button to create the account. --}}
            <button class="btn-primary modal-submit" type="submit">Create Account</button>
        </form>

        {{-- Switch back to login modal if the user already has an account. --}}
        <div class="form-row" style="margin-top:1rem;">
            <span>Already have an account?</span>
            <button class="btn-login" type="button" data-login-open>Log in</button>
        </div>
    </div>
</div>
