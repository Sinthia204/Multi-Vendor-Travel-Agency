{{-- Use the admin layout that provides sidebar, header, and shared styles. --}}
@extends('layouts.admin')

{{-- Page title shown in the browser tab. --}}
@section('title', 'Add Transport')
{{-- Page title shown in the admin header. --}}
@section('page-title', 'Add Transport')

@section('content')
    {{-- Validation summary for failed form submissions. --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                {{-- List each validation error returned by the controller. --}}
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Create transport form: posts to the transport store route with file upload enabled. --}}
    <form method="POST" action="{{ route('admin.transport.store') }}" class="tn-card-static p-4" enctype="multipart/form-data">
        {{-- Reusable form fields used by both create and edit views. --}}
        @include('admin.transport._form')
        <div class="mt-4 d-flex gap-2">
            {{-- Submit the form to create a new transport. --}}
            <button class="btn-primary-tn" type="submit">Create Transport</button>
            {{-- Return to the transport list without saving. --}}
            <a class="btn-outline-tn" href="{{ route('admin.transport.index') }}">Cancel</a>
        </div>
    </form>
@endsection
