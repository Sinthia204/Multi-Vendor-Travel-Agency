@extends('layouts.app')

@section('title', 'TravelNest - Agency Registration')

@section('content')
    <div class="modal-backdrop open">
        <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="agencyRegisterTitle">
            <a class="modal-close" href="{{ route('home') }}" aria-label="Close registration form">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <div class="modal-header">
                <span class="modal-tag">Agency partners</span>
                <h2 id="agencyRegisterTitle">Register your agency</h2>
                <p>Join TravelNest to publish packages and manage bookings.</p>
            </div>

            @if (session('agency_pending'))
                <div class="modal-alert" style="background: rgba(45, 138, 122, 0.15); color: #0f766e;">
                    {{ session('agency_pending') }}
                </div>
            @endif

            <form method="POST" action="{{ route('agency.register.submit') }}" class="modal-form" enctype="multipart/form-data">
                @csrf
                <div class="form-field">
                    <label for="agencyName">Agency Name</label>
                    <input id="agencyName" type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Agency name">
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="agencyContact">Contact Person</label>
                    <input id="agencyContact" type="text" name="contact_person" value="{{ old('contact_person') }}" required
                        placeholder="Contact person">
                    @error('contact_person')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="agencyEmail">Email</label>
                    <input id="agencyEmail" type="email" name="email" value="{{ old('email') }}" required
                        placeholder="you@example.com">
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="agencyPhone">Phone</label>
                    <input id="agencyPhone" type="text" name="phone" value="{{ old('phone') }}" required
                        placeholder="+1 555 123 4567">
                    @error('phone')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="agencyPassword">Password</label>
                    <input id="agencyPassword" type="password" name="password" required placeholder="Create a password">
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="agencyPasswordConfirm">Confirm Password</label>
                    <input id="agencyPasswordConfirm" type="password" name="password_confirmation" required
                        placeholder="Confirm your password">
                </div>

                <div class="form-field">
                    <label for="agencyLogo">Logo (optional)</label>
                    <input id="agencyLogo" type="file" name="logo" accept="image/*">
                    @error('logo')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <button class="btn-primary modal-submit" type="submit">Submit for approval</button>
            </form>

            <div class="form-row" style="margin-top:1rem;">
                <span>Already registered?</span>
                <a class="btn-login" href="{{ route('agency.login') }}">Agency login</a>
            </div>
        </div>
    </div>
@endsection
