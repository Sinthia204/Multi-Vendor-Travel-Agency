@extends('layouts.admin')

@section('title', 'Edit Experience')
@section('page-title', 'Edit Experience')

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

    <form method="POST" action="{{ route('admin.experiences.update', $experience) }}" class="tn-card-static p-4"
        enctype="multipart/form-data">
        @method('PUT')
        @include('admin.experiences._form', ['experience' => $experience])
        <div class="mt-4 d-flex gap-2">
            <button class="btn-primary-tn" type="submit">Save Changes</button>
            <a class="btn-outline-tn" href="{{ route('admin.experiences.index') }}">Cancel</a>
        </div>
    </form>
@endsection
