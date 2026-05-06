@extends('layouts.admin')

@section('title', 'Add Experience')
@section('page-title', 'Add Experience')

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

    <form method="POST" action="{{ route('admin.experiences.store') }}" class="tn-card-static p-4"
        enctype="multipart/form-data">
        @include('admin.experiences._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn-primary-tn" type="submit">Create Experience</button>
            <a class="btn-outline-tn" href="{{ route('admin.experiences.index') }}">Cancel</a>
        </div>
    </form>
@endsection
