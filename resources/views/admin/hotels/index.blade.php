{{-- Use the shared admin layout for consistent UI. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Hotel Management')
{{-- Admin header title. --}}
@section('page-title', 'Hotel Management')

@section('content')
    {{-- Filter/search toolbar for the hotel list. --}}
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.hotels.index') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="tn-search-input" style="width:320px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="tn-form-control" placeholder="Search hotels..."
                    value="{{ request('search') }}">
            </div>
            <select class="tn-form-control" name="status" style="width:160px;">
                <option value="">All Status</option>
                @foreach (['active' => 'Active', 'draft' => 'Draft', 'inactive' => 'Inactive'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            {{-- Submit filters as query params. --}}
            <button class="btn-outline-tn" type="submit">Filter</button>
            <a class="btn-outline-tn" href="{{ route('admin.hotels.index') }}">Reset</a>
        </div>
        <div class="d-flex gap-2">
            {{-- Link to the create hotel form. --}}
            <a class="btn-primary-tn" href="{{ route('admin.hotels.create') }}"><i class="fas fa-plus me-1"></i>
                Add Hotel</a>
        </div>
    </form>

    {{-- Flash success message after create/update/delete. --}}
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
                        <th>Price / Night</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop through hotel records from the controller. --}}
                    @forelse ($hotels as $hotel)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {{-- Resolve storage URLs for locally uploaded images. --}}
                                    @php
                                        $imagePath = $hotel->image_url;
                                        if ($imagePath && !str_starts_with($imagePath, 'http')) {
                                            $imagePath = Storage::url($imagePath);
                                        }
                                    @endphp
                                    {{-- Show image when available, otherwise show initials. --}}
                                    @if ($imagePath)
                                        <img src="{{ $imagePath }}" alt="{{ $hotel->name }}" width="40"
                                            height="40" style="border-radius:10px;object-fit:cover;">
                                    @else
                                        <div class="tn-avatar"
                                            style="background:rgba(45,138,122,0.1);color:var(--primary);">
                                            {{ strtoupper(substr($hotel->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <strong>{{ $hotel->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $hotel->city }}, {{ $hotel->country }}</td>
                            <td>${{ number_format($hotel->price_per_night, 2) }}</td>
                            <td>{{ $hotel->rating ? number_format($hotel->rating, 1) : 'N/A' }}</td>
                            <td>
                                <span
                                    class="tn-badge {{ $hotel->status === 'active' ? 'tn-badge-success' : 'tn-badge-warning' }}">
                                    {{ ucfirst($hotel->status) }}
                                </span>
                            </td>
                            <td>{{ $hotel->created_at->format('M d, Y') }}</td>
                            <td>
                                {{-- Actions menu for edit/delete. --}}
                                <div class="dropdown">
                                    <button class="tn-action-btn" data-bs-toggle="dropdown"><i
                                            class="fas fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.hotels.edit', $hotel) }}"><i
                                                    class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li>
                                            {{-- Delete uses method spoofing with CSRF protection. --}}
                                            <form method="POST" action="{{ route('admin.hotels.destroy', $hotel) }}"
                                                onsubmit="return confirm('Delete this hotel?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i
                                                        class="fas fa-trash me-2"></i>Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No hotels found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($hotels->hasPages())
        <nav class="mt-4">
            <ul class="pagination">
                <li class="page-item {{ $hotels->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $hotels->previousPageUrl() ?? '#' }}">Previous</a>
                </li>
                <li class="page-item {{ $hotels->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $hotels->nextPageUrl() ?? '#' }}">Next</a>
                </li>
            </ul>
        </nav>
    @endif
@endsection
