{{-- Use the shared admin layout for sidebar/header. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Transport Management')
{{-- Admin header title. --}}
@section('page-title', 'Transport Management')

@section('content')
    {{-- Filter/search toolbar for transport listing. --}}
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.transport.index') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="tn-search-input" style="width:320px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="tn-form-control" placeholder="Search transport..."
                    value="{{ request('search') }}">
            </div>
            <select class="tn-form-control" name="type" style="width:160px;">
                <option value="">All Types</option>
                @foreach (['Bus', 'Van', 'Car', 'Boat', 'Flight'] as $label)
                    <option value="{{ $label }}" @selected(request('type') === $label)>{{ $label }}</option>
                @endforeach
            </select>
            <select class="tn-form-control" name="status" style="width:160px;">
                <option value="">All Status</option>
                @foreach (['active' => 'Active', 'draft' => 'Draft', 'inactive' => 'Inactive'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            {{-- Submit filter criteria as query params. --}}
            <button class="btn-outline-tn" type="submit">Filter</button>
            <a class="btn-outline-tn" href="{{ route('admin.transport.index') }}">Reset</a>
        </div>
        <div class="d-flex gap-2">
            {{-- Link to the create form for new transport items. --}}
            <a class="btn-primary-tn" href="{{ route('admin.transport.create') }}"><i class="fas fa-plus me-1"></i>
                Add Transport</a>
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
                        <th>Type</th>
                        <th>Provider</th>
                        <th>Price / Trip</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop through transport records passed from the controller. --}}
                    @forelse ($transports as $transport)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {{-- Resolve storage URL for locally uploaded images. --}}
                                    @php
                                        $imagePath = $transport->image_url;
                                        if ($imagePath && !str_starts_with($imagePath, 'http')) {
                                            $imagePath = Storage::url($imagePath);
                                        }
                                    @endphp
                                    {{-- Show image when available, otherwise show initials. --}}
                                    @if ($imagePath)
                                        <img src="{{ $imagePath }}" alt="{{ $transport->name }}" width="40"
                                            height="40" style="border-radius:10px;object-fit:cover;">
                                    @else
                                        <div class="tn-avatar"
                                            style="background:rgba(212,160,48,0.1);color:var(--secondary);">
                                            {{ strtoupper(substr($transport->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <strong>{{ $transport->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $transport->type }}</td>
                            <td>{{ $transport->provider ?? 'N/A' }}</td>
                            <td>${{ number_format($transport->price_per_trip, 2) }}</td>
                            <td>{{ $transport->capacity ?? 'N/A' }}</td>
                            <td>
                                <span
                                    class="tn-badge {{ $transport->status === 'active' ? 'tn-badge-success' : 'tn-badge-warning' }}">
                                    {{ ucfirst($transport->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    {{-- Actions menu for edit/delete. --}}
                                    <button class="tn-action-btn" data-bs-toggle="dropdown"><i
                                            class="fas fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item"
                                                href="{{ route('admin.transport.edit', $transport) }}"><i
                                                    class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li>
                                            {{-- Delete uses a POST form with method spoofing. --}}
                                            <form method="POST"
                                                action="{{ route('admin.transport.destroy', $transport) }}"
                                                onsubmit="return confirm('Delete this transport option?');">
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
                            <td colspan="7">No transport options found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($transports->hasPages())
        <nav class="mt-4">
            <ul class="pagination">
                <li class="page-item {{ $transports->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $transports->previousPageUrl() ?? '#' }}">Previous</a>
                </li>
                <li class="page-item {{ $transports->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $transports->nextPageUrl() ?? '#' }}">Next</a>
                </li>
            </ul>
        </nav>
    @endif
@endsection
