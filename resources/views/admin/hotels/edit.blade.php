{{-- Use the shared admin layout with sidebar and header. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Edit Hotel')
{{-- Admin header title. --}}
@section('page-title', 'Edit Hotel')

@section('content')
    {{-- Show validation errors from the update request. --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                {{-- Render each validation message. --}}
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Update form posts to the hotel update route using PUT. --}}
    <form method="POST" action="{{ route('admin.hotels.update', $hotel) }}" class="tn-card-static p-4"
        enctype="multipart/form-data">
        @method('PUT')
        {{-- Reuse the shared hotel fields with existing values. --}}
        @include('admin.hotels._form', ['hotel' => $hotel])
        <div class="mt-4 d-flex gap-2">
            {{-- Save changes to this hotel record. --}}
            <button class="btn-primary-tn" type="submit">Save Changes</button>
            {{-- Return to the hotel list without saving. --}}
            <a class="btn-outline-tn" href="{{ route('admin.hotels.index') }}">Cancel</a>
        </div>
    </form>
@endsection
