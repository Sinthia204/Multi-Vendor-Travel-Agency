{{-- Use the shared admin layout for sidebar/header. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Tour Packages')
{{-- Admin header title. --}}
@section('page-title', 'Tour Packages')

@section('content')
    <!-- Toolbar with search and category filter. -->
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.packages') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="tn-search-input" style="width:280px;">
                <i class="fas fa-search"></i>
                <input type="text" class="tn-form-control" name="search" value="{{ $search }}"
                    placeholder="Search packages...">
            </div>
            <select class="tn-form-control" name="category" style="width:160px;">
                <option value="all" @selected($categoryFilter === 'all')>All Categories</option>
                <option value="beach" @selected($categoryFilter === 'beach')>Beach</option>
                <option value="mountain" @selected($categoryFilter === 'mountain')>Mountain</option>
                <option value="city" @selected($categoryFilter === 'city')>City</option>
                <option value="adventure" @selected($categoryFilter === 'adventure')>Adventure</option>
            </select>
            <button class="btn-outline-tn" type="submit">Filter</button>
        </div>
        <button class="btn-primary-tn" type="button" data-bs-toggle="modal" data-bs-target="#packageFormModal"
            data-add-package><i class="fas fa-plus me-1"></i> Add Package</button>
    </form>

    <!-- Package cards grid. -->
    <div class="row g-4" id="packageGrid">
        @forelse ($packages as $package)
            @php
                $gradient = $package->gradient ?: 'linear-gradient(135deg, #2d8a7a 0%, #3da88f 50%, #d4a030 100%)';
                $image = $package->image_url ?: 'images/dest_maldives_1775112608148.png';
                $image = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://', '/']) ? $image : asset($image);
                $pct = $package->capacity > 0 ? round(($package->booked / $package->capacity) * 100) : 0;
            @endphp
            <div class="col-md-6 col-xl-4">
                <div class="package-card animate-in" style="animation-delay:{{ $loop->index * 0.05 }}s;"
                    data-id="{{ $package->id }}" data-name="{{ $package->name }}"
                    data-agency="{{ $package->agency?->name }}" data-agency-id="{{ $package->agency_id }}"
                    data-category="{{ $package->category }}" data-price="{{ $package->price }}"
                    data-duration="{{ $package->duration }}" data-location="{{ $package->location }}"
                    data-capacity="{{ $package->capacity }}" data-booked="{{ $package->booked }}"
                    data-status="{{ $package->status }}" data-image="{{ $image }}"
                    data-gradient="{{ $gradient }}" data-featured="{{ $package->is_featured ? '1' : '0' }}"
                    data-featured-order="{{ $package->featured_order ?? '' }}"
                    data-update-url="{{ route('admin.packages.update', $package) }}">
                    <div class="package-card-image" style="background:{{ $gradient }};">
                        <img class="package-card-cover" src="{{ $image }}" alt="{{ $package->name }}">
                        <span class="package-card-category">{{ ucfirst($package->category) }}</span>
                    </div>
                    <div class="package-card-body">
                        <h4 class="package-card-title">{{ $package->name }}</h4>
                        <div class="package-card-agency"><i class="fas fa-building me-1"></i>
                            {{ $package->agency?->name }}</div>
                        <div class="package-card-price">From ${{ number_format($package->price, 0) }} <span>/person</span>
                        </div>
                        <div class="package-card-meta">
                            <span><i class="fas fa-clock"></i> {{ $package->duration }}</span>
                            <span><i class="fas fa-map-marker-alt"></i> {{ $package->location }}</span>
                        </div>
                        <div class="package-card-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width:{{ $pct }}%;"></div>
                            </div>
                            <div class="package-card-progress-label">{{ $package->booked }}/{{ $package->capacity }} seats
                                booked
                            </div>
                        </div>
                        <div class="mt-2">
                            @if ($package->status === 'active')
                                <span class="tn-badge tn-badge-success">Active</span>
                            @elseif($package->status === 'draft')
                                <span class="tn-badge tn-badge-muted">Draft</span>
                            @else
                                <span class="tn-badge tn-badge-danger">Sold Out</span>
                            @endif
                        </div>
                    </div>
                    <div class="package-card-footer">
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn-outline-tn btn-sm-tn" type="button" data-action="edit">Edit</button>
                            <form method="POST" action="{{ route('admin.packages.destroy', $package) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn-outline-tn btn-sm-tn" type="submit">Delete</button>
                            </form>
                        </div>
                        <form method="POST" action="{{ route('bookings.from-package') }}">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <input type="hidden" name="package_name" value="{{ $package->name }}">
                            <input type="hidden" name="amount" value="{{ $package->price }}">
                            <input type="date" name="travel_date" class="tn-form-control" style="max-width:140px;"
                                @disabled($package->status !== 'active')>
                            <input type="text" name="coupon_code" class="tn-form-control" placeholder="Coupon"
                                style="max-width:120px;" @disabled($package->status !== 'active')>
                            <button class="btn-primary-tn btn-sm-tn" type="submit"
                                @disabled($package->status !== 'active')>Book Now</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-muted-tn" style="padding:1.5rem;">No packages found.</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $packages->links('pagination::bootstrap-5') }}
    </div>

    <!-- Add / Edit Package Modal -->
    <div class="modal fade" id="packageFormModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packageFormTitle" style="font-family:'Space Grotesk';font-weight:600;">
                        Add Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="packageForm" method="POST" action="{{ route('admin.packages.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="packageFormMethod" value="POST">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="tn-form-label" for="packageName">Package Name</label>
                                <input class="tn-form-control" id="packageName" name="name" type="text" required>
                            </div>
                            <div class="col-md-6">
                                <label class="tn-form-label" for="packageAgency">Agency</label>
                                <select class="tn-form-control" id="packageAgency" name="agency_id" required>
                                    <option value="">Select agency</option>
                                    @foreach ($agencies as $agency)
                                        <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="tn-form-label" for="packageCategoryInput">Category</label>
                                <select class="tn-form-control" id="packageCategoryInput" name="category" required>
                                    <option value="beach">Beach</option>
                                    <option value="mountain">Mountain</option>
                                    <option value="city">City</option>
                                    <option value="adventure">Adventure</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="tn-form-label" for="packagePrice">Price</label>
                                <input class="tn-form-control" id="packagePrice" name="price" type="number" min="0"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="tn-form-label" for="packageDuration">Duration</label>
                                <input class="tn-form-control" id="packageDuration" name="duration" type="text"
                                    placeholder="4 Days" required>
                            </div>
                            <div class="col-md-6">
                                <label class="tn-form-label" for="packageLocation">Location</label>
                                <input class="tn-form-control" id="packageLocation" name="location" type="text" required>
                            </div>
                            <div class="col-md-3">
                                <label class="tn-form-label" for="packageCapacity">Capacity</label>
                                <input class="tn-form-control" id="packageCapacity" name="capacity" type="number" min="0"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="tn-form-label" for="packageStatus">Status</label>
                                <select class="tn-form-control" id="packageStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="sold-out">Sold Out</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tn-form-label" for="packageFeaturedOrder">Featured Order</label>
                                <input class="tn-form-control" id="packageFeaturedOrder" name="featured_order"
                                    type="number" min="0" placeholder="0">
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="packageFeatured"
                                        name="is_featured" value="1">
                                    <label class="form-label" for="packageFeatured">Featured</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="tn-form-label" for="packageImage">Cover Image URL</label>
                                <input class="tn-form-control" id="packageImage" name="image_url" type="text"
                                    placeholder="https://example.com/image.jpg">
                            </div>
                            <div class="col-12">
                                <label class="tn-form-label" for="packageGradient">Cover Gradient</label>
                                <input class="tn-form-control" id="packageGradient" name="gradient" type="text"
                                    placeholder="linear-gradient(135deg, #2d8a7a 0%, #3da88f 50%, #d4a030 100%)">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-outline-tn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-tn">Save Package</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('packageForm');
            const formMethod = document.getElementById('packageFormMethod');
            const formTitle = document.getElementById('packageFormTitle');
            const addButton = document.querySelector('[data-add-package]');

            const resetForm = () => {
                form.action = "{{ route('admin.packages.store') }}";
                formMethod.value = 'POST';
                form.reset();
                document.getElementById('packageCategoryInput').value = 'beach';
                document.getElementById('packageStatus').value = 'active';
                document.getElementById('packageFeaturedOrder').value = '';
                document.getElementById('packageFeatured').checked = false;
            };

            if (addButton) {
                addButton.addEventListener('click', () => {
                    if (formTitle) formTitle.textContent = 'Add Package';
                    resetForm();
                });
            }

            document.querySelectorAll('[data-action="edit"]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const card = button.closest('.package-card');
                    if (!card) return;
                    if (formTitle) formTitle.textContent = 'Edit Package';
                    form.action = card.dataset.updateUrl;
                    formMethod.value = 'PUT';
                    document.getElementById('packageName').value = card.dataset.name || '';
                    document.getElementById('packageAgency').value = card.dataset.agencyId || '';
                    document.getElementById('packageCategoryInput').value = card.dataset.category || 'beach';
                    document.getElementById('packagePrice').value = card.dataset.price || '';
                    document.getElementById('packageDuration').value = card.dataset.duration || '';
                    document.getElementById('packageLocation').value = card.dataset.location || '';
                    document.getElementById('packageCapacity').value = card.dataset.capacity || '';
                    document.getElementById('packageStatus').value = card.dataset.status || 'active';
                    document.getElementById('packageImage').value = card.dataset.image || '';
                    document.getElementById('packageGradient').value = card.dataset.gradient || '';
                    document.getElementById('packageFeaturedOrder').value = card.dataset.featuredOrder || '';
                    document.getElementById('packageFeatured').checked = card.dataset.featured === '1';
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('packageFormModal'));
                    modal.show();
                });
            });
        });
    </script>
@endsection
