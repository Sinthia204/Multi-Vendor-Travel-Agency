@extends('layouts.agency')

@section('title', 'Add Package')
@section('page-title', 'Add Package')

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

    <form method="POST" action="{{ route('agency.packages.store') }}" class="tn-card-static p-4">
        @include('agency.packages._form')
        <div class="mt-4 d-flex gap-2">
            <button class="btn-primary-tn" type="submit">Create Package</button>
            <a class="btn-outline-tn" href="{{ route('agency.packages.index') }}">Cancel</a>
        </div>
    </form>
@endsection
