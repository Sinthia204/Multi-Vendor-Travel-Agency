{{-- Use the shared admin layout for consistent UI. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'Agency Management')
{{-- Admin header title. --}}
@section('page-title', 'Agency Management')

@section('content')
    <!-- Toolbar for search, filters, and actions. -->
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.agencies') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="tn-search-input" style="width:320px;">
                <i class="fas fa-search"></i>
                <input type="text" class="tn-form-control" name="search" value="{{ $search }}"
                    placeholder="Search agencies...">
            </div>
            <select class="tn-form-control" name="status" style="width:160px;">
                <option value="all" @selected($statusFilter === 'all')>All Status</option>
                <option value="pending" @selected($statusFilter === 'pending')>Pending</option>
                <option value="approved" @selected($statusFilter === 'approved')>Approved</option>
                <option value="rejected" @selected($statusFilter === 'rejected')>Rejected</option>
                <option value="suspended" @selected($statusFilter === 'suspended')>Suspended</option>
            </select>
            <button class="btn-outline-tn" type="submit">Filter</button>
        </div>
        <div class="d-flex gap-2">
            <a class="btn-outline-tn" href="{{ route('admin.agencies.export', ['search' => $search, 'status' => $statusFilter]) }}">
                <i class="fas fa-download me-1"></i> Export</a>
            <button class="btn-primary-tn" type="button" data-bs-toggle="modal" data-bs-target="#agencyFormModal"
                data-add-agency><i class="fas fa-plus me-1"></i> Add Agency</button>
        </div>
    </form>

    <!-- Status filter pills. -->
    @php
        $statusBase = ['search' => $search];
    @endphp
    <div class="d-flex flex-wrap gap-2 mb-4" id="agencyStatusFilters">
        <a class="filter-pill {{ $statusFilter === 'all' ? 'active' : '' }}"
            href="{{ route('admin.agencies', $statusBase) }}" data-status="all">
            All <span class="pill-count">{{ $totalCount }}</span>
        </a>
        <a class="filter-pill {{ $statusFilter === 'approved' ? 'active' : '' }}"
            href="{{ route('admin.agencies', array_merge($statusBase, ['status' => 'approved'])) }}"
            data-status="approved">
            Approved <span class="pill-count">{{ $statusCounts->get('approved', 0) }}</span>
        </a>
        <a class="filter-pill {{ $statusFilter === 'pending' ? 'active' : '' }}"
            href="{{ route('admin.agencies', array_merge($statusBase, ['status' => 'pending'])) }}"
            data-status="pending">
            Pending <span class="pill-count">{{ $statusCounts->get('pending', 0) }}</span>
        </a>
        <a class="filter-pill {{ $statusFilter === 'suspended' ? 'active' : '' }}"
            href="{{ route('admin.agencies', array_merge($statusBase, ['status' => 'suspended'])) }}"
            data-status="suspended">
            Suspended <span class="pill-count">{{ $statusCounts->get('suspended', 0) }}</span>
        </a>
        <a class="filter-pill {{ $statusFilter === 'rejected' ? 'active' : '' }}"
            href="{{ route('admin.agencies', array_merge($statusBase, ['status' => 'rejected'])) }}"
            data-status="rejected">
            Rejected <span class="pill-count">{{ $statusCounts->get('rejected', 0) }}</span>
        </a>
    </div>

    <!-- Mini stats for quick agency status overview. -->
    <div class="d-flex flex-wrap gap-3 mb-4">
        <div class="mini-stat">
            <div>
                <div class="mini-stat-value" id="totalCount">{{ $totalCount }}</div>
                <div class="mini-stat-label">Total</div>
            </div>
        </div>
        <div class="mini-stat">
            <div>
                <div class="mini-stat-value text-success-tn" id="approvedCount">{{ $statusCounts->get('approved', 0) }}
                </div>
                <div class="mini-stat-label">Approved</div>
            </div>
        </div>
        <div class="mini-stat">
            <div>
                <div class="mini-stat-value" id="pendingCount" style="color:var(--warning);">
                    {{ $statusCounts->get('pending', 0) }}</div>
                <div class="mini-stat-label">Pending</div>
            </div>
        </div>
        <div class="mini-stat">
            <div>
                <div class="mini-stat-value text-destructive" id="suspendedCount">
                    {{ $statusCounts->get('suspended', 0) }}</div>
                <div class="mini-stat-label">Suspended</div>
            </div>
        </div>
        <div class="mini-stat">
            <div>
                <div class="mini-stat-value text-destructive" id="rejectedCount">
                    {{ $statusCounts->get('rejected', 0) }}</div>
                <div class="mini-stat-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Agencies data table (static demo rows). -->
    <div class="tn-card-static">
        <div style="overflow-x:auto;">
            <table class="tn-table">
                <thead>
                    <tr>
                        <th>Agency Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="agencyTableBody">
                    @forelse ($agencies as $agency)
                        @php
                            $initials = collect(explode(' ', $agency->name))
                                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                ->take(2)
                                ->implode('');
                        @endphp
                        <tr data-agency-name="{{ $agency->name }}" data-contact="{{ $agency->contact_person }}"
                            data-email="{{ $agency->email }}" data-phone="{{ $agency->phone }}"
                            data-status="{{ $agency->status }}"
                            data-registered="{{ optional($agency->registered_at)->format('M d, Y') }}"
                            data-registered-raw="{{ optional($agency->registered_at)->format('Y-m-d') }}"
                            data-update-url="{{ route('admin.agencies.update', $agency) }}"
                            data-id="{{ $agency->id }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="tn-avatar" style="background:rgba(45,138,122,0.1);color:var(--primary);">
                                        {{ $initials }}</div>
                                    <strong>{{ $agency->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $agency->contact_person }}</td>
                            <td>{{ $agency->email }}</td>
                            <td>{{ $agency->phone }}</td>
                            <td>
                                <span
                                    class="tn-badge tn-badge-{{ $agency->status === 'approved' ? 'success' : ($agency->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($agency->status) }}</span>
                            </td>
                            <td>{{ optional($agency->registered_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="tn-action-btn" data-bs-toggle="dropdown"><i
                                            class="fas fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" data-action="view"><i
                                                    class="fas fa-eye me-2"></i>View Details</a></li>
                                        <li><a class="dropdown-item" href="#" data-action="edit"><i
                                                    class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li>
                                            @if ($agency->status === 'pending')
                                                <form method="POST" action="{{ route('admin.agencies.approve', $agency) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="dropdown-item" type="submit">
                                                        <i class="fas fa-check me-2"></i>Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.agencies.reject', $agency) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="dropdown-item" type="submit">
                                                        <i class="fas fa-xmark me-2"></i>Reject
                                                    </button>
                                                </form>
                                            @elseif ($agency->status === 'approved')
                                                <form method="POST" action="{{ route('admin.agencies.update', $agency) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="name" value="{{ $agency->name }}">
                                                    <input type="hidden" name="contact_person" value="{{ $agency->contact_person }}">
                                                    <input type="hidden" name="email" value="{{ $agency->email }}">
                                                    <input type="hidden" name="phone" value="{{ $agency->phone }}">
                                                    <input type="hidden" name="registered_at" value="{{ optional($agency->registered_at)->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="suspended">
                                                    <button class="dropdown-item" type="submit">
                                                        <i class="fas fa-ban me-2"></i>Suspend
                                                    </button>
                                                </form>
                                            @elseif ($agency->status === 'rejected')
                                                <form method="POST" action="{{ route('admin.agencies.approve', $agency) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="dropdown-item" type="submit">
                                                        <i class="fas fa-check me-2"></i>Approve
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.agencies.update', $agency) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="name" value="{{ $agency->name }}">
                                                    <input type="hidden" name="contact_person" value="{{ $agency->contact_person }}">
                                                    <input type="hidden" name="email" value="{{ $agency->email }}">
                                                    <input type="hidden" name="phone" value="{{ $agency->phone }}">
                                                    <input type="hidden" name="registered_at" value="{{ optional($agency->registered_at)->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="approved">
                                                    <button class="dropdown-item" type="submit">
                                                        <i class="fas fa-check me-2"></i>Approve
                                                    </button>
                                                </form>
                                            @endif
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.agencies.destroy', $agency) }}"
                                                onsubmit="return confirm('Delete this agency?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit"><i
                                                        class="fas fa-trash me-2"></i>Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted-tn" style="padding:1.5rem;">No agencies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3">
            <span class="text-muted-tn" style="font-size:14px;">Showing {{ $agencies->firstItem() ?? 0 }} to
                {{ $agencies->lastItem() ?? 0 }} of {{ $agencies->total() }} agencies</span>
            {{ $agencies->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Agency Detail Modal -->
    <div class="modal fade" id="agencyDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <h5 class="modal-title" style="font-family:'Space Grotesk';font-weight:600;">Bengal Tours Ltd</h5>
                        <span class="tn-badge tn-badge-success">Approved</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs tn-tabs mb-4" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                href="#tabOverview">Overview</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabDocuments">Documents</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabBookings">Bookings</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tabRevenue">Revenue</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Overview -->
                        <div class="tab-pane fade show active" id="tabOverview">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <p class="tn-form-label mb-1">Contact Person</p>
                                    <p>Rafiq Ahmed</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="tn-form-label mb-1">Email</p>
                                    <p>rafiq@bengaltours.com</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="tn-form-label mb-1">Phone</p>
                                    <p>+880 1711-234567</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="tn-form-label mb-1">Registration Date</p>
                                    <p>January 15, 2026</p>
                                </div>
                                <div class="col-12">
                                    <p class="tn-form-label mb-1">Address</p>
                                    <p>42 Motijheel C/A, Dhaka-1000, Bangladesh</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="tn-form-label mb-1">Total Bookings</p>
                                    <p style="font-family:'Space Grotesk';font-weight:600;font-size:20px;">342</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="tn-form-label mb-1">Total Revenue</p>
                                    <p
                                        style="font-family:'Space Grotesk';font-weight:600;font-size:20px;color:var(--primary);">
                                        $48,750</p>
                                </div>
                            </div>
                        </div>
                        <!-- Documents -->
                        <div class="tab-pane fade" id="tabDocuments">
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center justify-content-between p-3"
                                    style="background:var(--muted);border-radius:8px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-file-pdf text-destructive" style="font-size:24px;"></i>
                                        <div>
                                            <div style="font-weight:500;">Business Registration Certificate</div>
                                            <div class="text-muted-tn" style="font-size:12px;">PDF · 2.4 MB · Uploaded Mar
                                                20, 2026</div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn-outline-tn btn-sm-tn"><i class="fas fa-eye"></i></button>
                                        <button class="btn-outline-tn btn-sm-tn"><i class="fas fa-download"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-3"
                                    style="background:var(--muted);border-radius:8px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-file-image text-primary-tn" style="font-size:24px;"></i>
                                        <div>
                                            <div style="font-weight:500;">Trade License</div>
                                            <div class="text-muted-tn" style="font-size:12px;">JPG · 1.8 MB · Uploaded Mar
                                                20, 2026</div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn-outline-tn btn-sm-tn"><i class="fas fa-eye"></i></button>
                                        <button class="btn-outline-tn btn-sm-tn"><i class="fas fa-download"></i></button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-3"
                                    style="background:var(--muted);border-radius:8px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-file-pdf text-destructive" style="font-size:24px;"></i>
                                        <div>
                                            <div style="font-weight:500;">TIN Certificate</div>
                                            <div class="text-muted-tn" style="font-size:12px;">PDF · 0.9 MB · Uploaded Mar
                                                20, 2026</div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn-outline-tn btn-sm-tn"><i class="fas fa-eye"></i></button>
                                        <button class="btn-outline-tn btn-sm-tn"><i class="fas fa-download"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Bookings Tab -->
                        <div class="tab-pane fade" id="tabBookings">
                            <p class="text-muted-tn">This agency has 342 total bookings. Showing the latest 3:</p>
                            <table class="tn-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Package</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="booking-id">#BK-4501</td>
                                        <td>Anika Rahman</td>
                                        <td>Cox's Bazar Deluxe</td>
                                        <td><span class="tn-badge tn-badge-success">Confirmed</span></td>
                                    </tr>
                                    <tr>
                                        <td class="booking-id">#BK-4489</td>
                                        <td>Farhan Ali</td>
                                        <td>Sundarbans Safari</td>
                                        <td><span class="tn-badge tn-badge-warning">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td class="booking-id">#BK-4475</td>
                                        <td>Tasnim Jahan</td>
                                        <td>Sajek Valley Tour</td>
                                        <td><span class="tn-badge tn-badge-success">Confirmed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Revenue Tab -->
                        <div class="tab-pane fade" id="tabRevenue">
                            <div class="row g-4 text-center">
                                <div class="col-4">
                                    <div
                                        style="font-family:'Space Grotesk';font-weight:700;font-size:24px;color:var(--primary);">
                                        $48,750</div>
                                    <div class="text-muted-tn" style="font-size:13px;">Total Revenue</div>
                                </div>
                                <div class="col-4">
                                    <div
                                        style="font-family:'Space Grotesk';font-weight:700;font-size:24px;color:var(--success);">
                                        $5,200</div>
                                    <div class="text-muted-tn" style="font-size:13px;">This Month</div>
                                </div>
                                <div class="col-4">
                                    <div
                                        style="font-family:'Space Grotesk';font-weight:700;font-size:24px;color:var(--secondary);">
                                        $142</div>
                                    <div class="text-muted-tn" style="font-size:13px;">Avg / Booking</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline-tn" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn-primary-tn">Edit Agency</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add / Edit Agency Modal -->
    <div class="modal fade" id="agencyFormModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agencyFormTitle" style="font-family:'Space Grotesk';font-weight:600;">
                        Add Agency
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="agencyForm" class="row g-3" method="POST" action="{{ route('admin.agencies.store') }}">
                        @csrf
                        <input type="hidden" id="agencyFormMethod" name="_method" value="POST">
                        <div class="col-md-6">
                            <label class="tn-form-label" for="agencyName">Agency Name</label>
                            <input class="tn-form-control" id="agencyName" name="name" type="text" required>
                        </div>
                        <div class="col-md-6">
                            <label class="tn-form-label" for="agencyContact">Contact Person</label>
                            <input class="tn-form-control" id="agencyContact" name="contact_person" type="text" required>
                        </div>
                        <div class="col-md-6">
                            <label class="tn-form-label" for="agencyEmail">Email</label>
                            <input class="tn-form-control" id="agencyEmail" name="email" type="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="tn-form-label" for="agencyPhone">Phone</label>
                            <input class="tn-form-control" id="agencyPhone" name="phone" type="text" required>
                        </div>
                        <div class="col-md-6">
                            <label class="tn-form-label" for="agencyStatus">Status</label>
                            <select class="tn-form-control" id="agencyStatus" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="tn-form-label" for="agencyRegistered">Registered</label>
                            <input class="tn-form-control" id="agencyRegistered" name="registered_at" type="date">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline-tn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-tn" form="agencyForm">Save Agency</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterForm = document.querySelector('form[action="{{ route('admin.agencies') }}"]');
            const statusSelect = filterForm?.querySelector('select[name="status"]');
            const form = document.getElementById('agencyForm');
            const formTitle = document.getElementById('agencyFormTitle');
            const formMethod = document.getElementById('agencyFormMethod');
            const addButton = document.querySelector('[data-add-agency]');
            const detailModalElement = document.getElementById('agencyDetailModal');
            const detailModal = detailModalElement ? new bootstrap.Modal(detailModalElement) : null;

            const resetForm = () => {
                form.action = "{{ route('admin.agencies.store') }}";
                formMethod.value = 'POST';
                form.reset();
            };

            const setDetailModal = (row) => {
                if (!detailModalElement) return;
                const title = detailModalElement.querySelector('.modal-title');
                const badge = detailModalElement.querySelector('.tn-badge');
                const fields = detailModalElement.querySelectorAll('.tab-pane#tabOverview p:not(.tn-form-label)');
                if (title) title.textContent = row.dataset.agencyName || 'Agency';
                if (badge) {
                    badge.className = `tn-badge tn-badge-${row.dataset.status === 'approved' ? 'success' : row.dataset.status === 'pending' ? 'warning' : 'danger'}`;
                    badge.textContent = row.dataset.status ? row.dataset.status.charAt(0).toUpperCase() + row.dataset.status.slice(1) : 'Pending';
                }
                if (fields.length >= 4) {
                    fields[0].textContent = row.dataset.contact || '-';
                    fields[1].textContent = row.dataset.email || '-';
                    fields[2].textContent = row.dataset.phone || '-';
                    fields[3].textContent = row.dataset.registered || '-';
                }
            };

            if (addButton) {
                addButton.addEventListener('click', () => {
                    if (formTitle) formTitle.textContent = 'Add Agency';
                    resetForm();
                });
            }

            if (statusSelect && filterForm) {
                statusSelect.addEventListener('change', () => {
                    filterForm.submit();
                });
            }

            document.querySelectorAll('[data-action="edit"]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const row = button.closest('tr');
                    if (!row) return;
                    if (formTitle) formTitle.textContent = 'Edit Agency';
                    form.action = row.dataset.updateUrl;
                    formMethod.value = 'PUT';
                    document.getElementById('agencyName').value = row.dataset.agencyName || '';
                    document.getElementById('agencyContact').value = row.dataset.contact || '';
                    document.getElementById('agencyEmail').value = row.dataset.email || '';
                    document.getElementById('agencyPhone').value = row.dataset.phone || '';
                    document.getElementById('agencyStatus').value = row.dataset.status || 'pending';
                    document.getElementById('agencyRegistered').value = row.dataset.registeredRaw || '';
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('agencyFormModal'));
                    modal.show();
                });
            });

            document.querySelectorAll('[data-action="view"]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const row = button.closest('tr');
                    if (!row) return;
                    setDetailModal(row);
                    detailModal?.show();
                });
            });
        });
    </script>
@endsection
