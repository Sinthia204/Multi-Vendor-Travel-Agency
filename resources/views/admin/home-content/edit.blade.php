@extends('layouts.admin')

@section('title', 'Home Content')
@section('page-title', 'Home Content')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $heroImage = $homeContent?->hero_image_url;
        if ($heroImage && !str_starts_with($heroImage, 'http')) {
            $heroImage = Storage::url($heroImage);
        }
    @endphp

    <form method="POST" action="{{ route('admin.home-content.update') }}" class="tn-card-static p-4"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h3 class="mb-3">Hero</h3>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Badge</label>
                <input type="text" name="hero_badge" class="tn-form-control"
                    value="{{ old('hero_badge', $homeContent->hero_badge ?? '') }}">
            </div>
            <div class="col-md-8">
                <label class="form-label">Title</label>
                <input type="text" name="hero_title" class="tn-form-control" required
                    value="{{ old('hero_title', $homeContent->hero_title ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Subtitle</label>
                <textarea name="hero_subtitle" class="tn-form-control" rows="3">{{ old('hero_subtitle', $homeContent->hero_subtitle ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Hero Image Upload</label>
                <input type="file" name="hero_image" class="tn-form-control" accept="image/*">
            </div>
            <div class="col-md-6">
                <label class="form-label">Hero Image URL (optional)</label>
                <input type="text" name="hero_image_url" class="tn-form-control"
                    value="{{ old('hero_image_url', $homeContent->hero_image_url ?? '') }}" placeholder="https://...">
            </div>
            <div class="col-md-6">
                <label class="form-label">CTA Text</label>
                <input type="text" name="hero_cta_text" class="tn-form-control"
                    value="{{ old('hero_cta_text', $homeContent->hero_cta_text ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">CTA URL</label>
                <input type="text" name="hero_cta_url" class="tn-form-control"
                    value="{{ old('hero_cta_url', $homeContent->hero_cta_url ?? '') }}">
            </div>
            @if ($heroImage)
                <div class="col-12">
                    <img src="{{ $heroImage }}" alt="Hero image" width="240" height="140"
                        style="border-radius:12px;object-fit:cover;">
                </div>
            @endif
        </div>

        <h3 class="mb-3">Featured Destinations</h3>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Badge</label>
                <input type="text" name="destinations_badge" class="tn-form-control"
                    value="{{ old('destinations_badge', $homeContent->destinations_badge ?? '') }}">
            </div>
            <div class="col-md-8">
                <label class="form-label">Title</label>
                <input type="text" name="destinations_title" class="tn-form-control"
                    value="{{ old('destinations_title', $homeContent->destinations_title ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Subtitle</label>
                <textarea name="destinations_subtitle" class="tn-form-control" rows="2">{{ old('destinations_subtitle', $homeContent->destinations_subtitle ?? '') }}</textarea>
            </div>
        </div>

        <h3 class="mb-3">Featured Packages</h3>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Badge</label>
                <input type="text" name="packages_badge" class="tn-form-control"
                    value="{{ old('packages_badge', $homeContent->packages_badge ?? '') }}">
            </div>
            <div class="col-md-8">
                <label class="form-label">Title</label>
                <input type="text" name="packages_title" class="tn-form-control"
                    value="{{ old('packages_title', $homeContent->packages_title ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Subtitle</label>
                <textarea name="packages_subtitle" class="tn-form-control" rows="2">{{ old('packages_subtitle', $homeContent->packages_subtitle ?? '') }}</textarea>
            </div>
        </div>

        <h3 class="mb-3">Experiences Section</h3>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Badge</label>
                <input type="text" name="experiences_badge" class="tn-form-control"
                    value="{{ old('experiences_badge', $homeContent->experiences_badge ?? '') }}">
            </div>
            <div class="col-md-8">
                <label class="form-label">Title</label>
                <input type="text" name="experiences_title" class="tn-form-control"
                    value="{{ old('experiences_title', $homeContent->experiences_title ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Subtitle</label>
                <textarea name="experiences_subtitle" class="tn-form-control" rows="2">{{ old('experiences_subtitle', $homeContent->experiences_subtitle ?? '') }}</textarea>
            </div>
        </div>

        <h3 class="mb-3">Stories Section</h3>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Badge</label>
                <input type="text" name="stories_badge" class="tn-form-control"
                    value="{{ old('stories_badge', $homeContent->stories_badge ?? '') }}">
            </div>
            <div class="col-md-8">
                <label class="form-label">Title</label>
                <input type="text" name="stories_title" class="tn-form-control"
                    value="{{ old('stories_title', $homeContent->stories_title ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Subtitle</label>
                <textarea name="stories_subtitle" class="tn-form-control" rows="2">{{ old('stories_subtitle', $homeContent->stories_subtitle ?? '') }}</textarea>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn-primary-tn" type="submit">Save Home Content</button>
        </div>
    </form>
@endsection
