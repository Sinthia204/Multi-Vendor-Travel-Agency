{{-- Use the shared admin layout with sidebar, header, and styles. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Edit Transport')
{{-- Admin header title. --}}
@section('page-title', 'Edit Transport')

@section('content')
    {{-- Show validation errors when the update request fails. --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                {{-- List each validation message from the backend. --}}
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Update form posts to the transport update route using PUT. --}}
    <form method="POST" action="{{ route('admin.transport.update', $transport) }}" class="tn-card-static p-4"
        enctype="multipart/form-data">
        @method('PUT')
        {{-- Reuse the shared transport fields with existing values. --}}
        @include('admin.transport._form', ['transport' => $transport])
        <div class="mt-4 d-flex gap-2">
            {{-- Save changes to this transport record. --}}
            <button class="btn-primary-tn" type="submit">Save Changes</button>
            {{-- Navigate back to the transport list without saving. --}}
            <a class="btn-outline-tn" href="{{ route('admin.transport.index') }}">Cancel</a>
        </div>
    </form>
@endsection
