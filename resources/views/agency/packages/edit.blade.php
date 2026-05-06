@extends('layouts.agency')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

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

    <form method="POST" action="{{ route('agency.packages.update', $package->id) }}" class="tn-card-static p-4">
        @csrf
        @method('PUT')
        @include('agency.packages._form', ['package' => $package])
        <div class="mt-4 d-flex gap-2">
            <button class="btn-primary-tn" type="submit">Save Changes</button>
            <a class="btn-outline-tn" href="{{ route('agency.packages.index') }}">Cancel</a>
        </div>
    </form>
@endsection
