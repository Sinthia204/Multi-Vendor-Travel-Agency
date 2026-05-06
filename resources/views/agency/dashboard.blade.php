@extends('layouts.agency')

@section('title', 'Agency Dashboard')
@section('page-title', 'Agency Dashboard')

@section('content')
    <div class="tn-card-static p-4">
        <h3 style="margin-bottom:0.75rem;">Welcome back, {{ $agency->name }}</h3>
        <p class="text-muted-tn" style="margin-bottom:1.5rem;">Manage your packages and track your listings here.</p>
        <div class="d-flex gap-2">
            <a class="btn-primary-tn" href="{{ route('agency.packages.index') }}">Manage packages</a>
        </div>
    </div>
@endsection
