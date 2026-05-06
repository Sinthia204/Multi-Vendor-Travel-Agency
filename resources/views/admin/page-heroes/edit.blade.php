@extends('layouts.admin')

@section('title', 'Edit Page Hero')
@section('page-title', 'Edit Page Hero')

@section('content')
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
        $imagePath = $pageHero->background_image_url;
        if ($imagePath && !str_starts_with($imagePath, 'http')) {
            $imagePath = Storage::url($imagePath);
        }
    @endphp

    <form method="POST" action="{{ route('admin.page-heroes.update', $pageHero) }}" class="tn-card-static p-4"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Badge</label>
                <input type="text" name="badge" class="tn-form-control"
                    value="{{ old('badge', $pageHero->badge ?? '') }}">
            </div>
            <div class="col-md-8">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="tn-form-control" required
                    value="{{ old('title', $pageHero->title ?? '') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Subtitle</label>
                <textarea name="subtitle" class="tn-form-control" rows="3">{{ old('subtitle', $pageHero->subtitle ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Background Image Upload</label>
                <input type="file" name="background_image" class="tn-form-control" accept="image/*">
            </div>
            <div class="col-md-6">
                <label class="form-label">Background Image URL (optional)</label>
                <input type="text" name="background_image_url" class="tn-form-control"
                    value="{{ old('background_image_url', $pageHero->background_image_url ?? '') }}" placeholder="https://...">
            </div>
            <div class="col-md-6">
                <label class="form-label">CTA Text</label>
                <input type="text" name="cta_text" class="tn-form-control"
                    value="{{ old('cta_text', $pageHero->cta_text ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">CTA URL</label>
                <input type="text" name="cta_url" class="tn-form-control"
                    value="{{ old('cta_url', $pageHero->cta_url ?? '') }}">
            </div>
            @if ($imagePath)
                <div class="col-12">
                    <img src="{{ $imagePath }}" alt="{{ $pageHero->title }}" width="240" height="140"
                        style="border-radius:12px;object-fit:cover;">
                </div>
            @endif
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn-primary-tn" type="submit">Save Hero</button>
            <a class="btn-outline-tn" href="{{ route('admin.page-heroes.index') }}">Cancel</a>
        </div>
    </form>
@endsection
