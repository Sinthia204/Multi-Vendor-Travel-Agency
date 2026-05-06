@extends('layouts.agency')

@section('title', 'My Packages')
@section('page-title', 'My Packages')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h3 style="margin-bottom:0.25rem;">Packages</h3>
            <p class="text-muted-tn" style="margin:0;">Only your approved agency packages appear here.</p>
        </div>
        <a class="btn-primary-tn" href="{{ route('agency.packages.create') }}"><i class="fas fa-plus me-1"></i> Add Package</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tn-card-static">
        <div style="overflow-x:auto;">
            <table class="tn-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($packages as $package)
                        <tr>
                            <td><strong>{{ $package->name }}</strong></td>
                            <td>{{ $package->location }}</td>
                            <td>${{ number_format($package->price, 0) }}</td>
                            <td>
                                <span class="tn-badge {{ $package->status === 'active' ? 'tn-badge-success' : 'tn-badge-muted' }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                            </td>
                            <td>{{ $package->updated_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="btn-outline-tn btn-sm-tn" href="{{ route('agency.packages.edit', $package->id) }}">Edit</a>
                                    <form method="POST" action="{{ route('agency.packages.destroy', $package->id) }}"
                                        onsubmit="return confirm('Delete this package?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-outline-tn btn-sm-tn" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No packages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $packages->links('pagination::bootstrap-5') }}
    </div>
@endsection
