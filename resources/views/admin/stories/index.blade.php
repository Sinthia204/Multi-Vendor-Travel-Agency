@extends('layouts.admin')

@section('title', 'Stories')
@section('page-title', 'Stories')

@section('content')
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.stories.index') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="tn-search-input" style="width:320px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" class="tn-form-control" placeholder="Search stories..."
                    value="{{ $search }}">
            </div>
            <button class="btn-outline-tn" type="submit">Filter</button>
            <a class="btn-outline-tn" href="{{ route('admin.stories.index') }}">Reset</a>
        </div>
        <div class="d-flex gap-2">
            <a class="btn-primary-tn" href="{{ route('admin.stories.create') }}"><i class="fas fa-plus me-1"></i>
                Add Story</a>
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
                        <th>Story</th>
                        <th>Status</th>
                        <th>Sort</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stories as $story)
                        @php
                            $imagePath = $story->image_url;
                            if ($imagePath && !str_starts_with($imagePath, 'http')) {
                                $imagePath = Storage::url($imagePath);
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($imagePath)
                                        <img src="{{ $imagePath }}" alt="{{ $story->title }}" width="40" height="40"
                                            style="border-radius:10px;object-fit:cover;">
                                    @else
                                        <div class="tn-avatar"
                                            style="background:rgba(45,138,122,0.1);color:var(--primary);">
                                            {{ strtoupper(substr($story->title, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $story->title }}</strong>
                                        <div class="text-muted-tn" style="font-size:0.85rem;">
                                            {{ $story->category ?: 'Uncategorized' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="tn-badge {{ $story->is_active ? 'tn-badge-success' : 'tn-badge-muted' }}">
                                    {{ $story->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $story->sort_order }}</td>
                            <td>{{ $story->updated_at->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="tn-action-btn" data-bs-toggle="dropdown"><i
                                            class="fas fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.stories.edit', $story) }}"><i
                                                    class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.stories.destroy', $story) }}"
                                                onsubmit="return confirm('Delete this story?');">
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
                            <td colspan="5">No stories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $stories->links('pagination::bootstrap-5') }}
    </div>
@endsection
