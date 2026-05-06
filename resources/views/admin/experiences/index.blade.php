@extends('layouts.admin')

@section('title', 'Experiences')
@section('page-title', 'Experiences')

@section('content')
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.experiences.index') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="tn-search-input" style="width:320px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="tn-form-control" placeholder="Search experiences..."
                    value="{{ $search }}">
            </div>
            <button class="btn-outline-tn" type="submit">Filter</button>
            <a class="btn-outline-tn" href="{{ route('admin.experiences.index') }}">Reset</a>
        </div>
        <div class="d-flex gap-2">
            <a class="btn-primary-tn" href="{{ route('admin.experiences.create') }}"><i class="fas fa-plus me-1"></i>
                Add Experience</a>
        </div>
    </form>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tn-card-static">
        <div style="overflow-x:auto;">
            <table class="tn-table">
                <thead>
                    <tr>
                        <th>Experience</th>
                        <th>Status</th>
                        <th>Sort</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($experiences as $experience)
                        @php
                            $imagePath = $experience->image_url;
                            if ($imagePath && !str_starts_with($imagePath, 'http')) {
                                $imagePath = Storage::url($imagePath);
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($imagePath)
                                        <img src="{{ $imagePath }}" alt="{{ $experience->title }}" width="40"
                                            height="40" style="border-radius:10px;object-fit:cover;">
                                    @else
                                        <div class="tn-avatar"
                                            style="background:rgba(45,138,122,0.1);color:var(--primary);">
                                            {{ strtoupper(substr($experience->title, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $experience->title }}</strong>
                                        <div class="text-muted-tn" style="font-size:0.85rem;">
                                            {{ $experience->icon ?: 'No icon' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="tn-badge {{ $experience->is_active ? 'tn-badge-success' : 'tn-badge-muted' }}">
                                    {{ $experience->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $experience->sort_order }}</td>
                            <td>{{ $experience->updated_at->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="tn-action-btn" data-bs-toggle="dropdown"><i
                                            class="fas fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item"
                                                href="{{ route('admin.experiences.edit', $experience) }}"><i
                                                    class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}"
                                                onsubmit="return confirm('Delete this experience?');">
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
                            <td colspan="5">No experiences found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $experiences->links('pagination::bootstrap-5') }}
    </div>
@endsection
