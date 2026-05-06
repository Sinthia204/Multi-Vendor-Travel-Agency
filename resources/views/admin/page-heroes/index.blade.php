@extends('layouts.admin')

@section('title', 'Page Heroes')
@section('page-title', 'Page Heroes')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tn-card-static">
        <div style="overflow-x:auto;">
            <table class="tn-table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Title</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pageHeroes as $pageHero)
                        <tr>
                            <td>{{ ucfirst(str_replace('-', ' ', $pageHero->slug)) }}</td>
                            <td>{{ $pageHero->title }}</td>
                            <td>{{ $pageHero->updated_at->format('M d, Y') }}</td>
                            <td>
                                <a class="btn-outline-tn btn-sm-tn" href="{{ route('admin.page-heroes.edit', $pageHero) }}">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No page heroes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
