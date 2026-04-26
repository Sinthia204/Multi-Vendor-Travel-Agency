{{-- Use the shared admin layout with sidebar and header. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Add Hotel')
{{-- Admin header title. --}}
@section('page-title', 'Add Hotel')

@section('content')
    {{-- Show validation errors from the store request. --}}
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

    {{-- Create hotel form posts to the store route with file upload enabled. --}}
    <form method="POST" action="{{ route('admin.hotels.store') }}" class="tn-card-static p-4" enctype="multipart/form-data">
        {{-- Reusable hotel fields shared with edit view. --}}
        @include('admin.hotels._form')
        <div class="mt-4 d-flex gap-2">
            {{-- Submit the form to create a new hotel. --}}
            <button class="btn-primary-tn" type="submit">Create Hotel</button>
            {{-- Return to the hotel list without saving. --}}
            <a class="btn-outline-tn" href="{{ route('admin.hotels.index') }}">Cancel</a>
        </div>
    </form>
@endsection
