{{-- Use the shared admin layout with sidebar and header. --}}
@extends('layouts.admin')

{{-- Browser tab title. --}}
@section('title', 'User Management')
{{-- Admin header title. --}}
@section('page-title', 'User Management')

@section('content')
    <!-- Toolbar for search, filters, and actions. -->
    <form class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" method="GET"
        action="{{ route('admin.users') }}">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="tn-search-input" style="width:320px;">
                <i class="fas fa-search"></i>
                <input type="text" class="tn-form-control" name="search" value="{{ $search }}"
                    placeholder="Search users...">
            </div>
            @if ($roleFilter !== 'all')
                <input type="hidden" name="role" value="{{ $roleFilter }}">
            @endif
            <select class="tn-form-control" name="status" style="width:160px;">
                <option value="all" @selected($statusFilter === 'all')>All Status</option>
                <option value="active" @selected($statusFilter === 'active')>Active</option>
                <option value="pending" @selected($statusFilter === 'pending')>Pending</option>
                <option value="suspended" @selected($statusFilter === 'suspended')>Suspended</option>
            </select>
            <button class="btn-outline-tn" type="submit">Filter</button>
        </div>
        <div class="d-flex gap-2">
            <a class="btn-outline-tn"
                href="{{ route('admin.users.export', ['search' => $search, 'status' => $statusFilter, 'role' => $roleFilter]) }}">
                <i class="fas fa-download me-1"></i> Export</a>
            <button class="btn-primary-tn" type="button" data-bs-toggle="modal" data-bs-target="#userFormModal"
                data-add-user><i class="fas fa-plus me-1"></i> Add User</button>
        </div>
    </form>

    <!-- Role filter pills. -->
    <div class="d-flex flex-wrap gap-2 mb-4" id="userRoleFilters">
        @php
            $roleBase = ['search' => $search, 'status' => $statusFilter];
        @endphp
        <a class="filter-pill {{ $roleFilter === 'all' ? 'active' : '' }}" href="{{ route('admin.users', $roleBase) }}"
            data-role="all">
            All <span class="pill-count" id="countAll">{{ $totalCount }}</span>
        </a>
        <a class="filter-pill {{ $roleFilter === 'admin' ? 'active' : '' }}"
            href="{{ route('admin.users', array_merge($roleBase, ['role' => 'admin'])) }}" data-role="admin">
            Admins <span class="pill-count" id="countAdmin">{{ $roleCounts->get('admin', 0) }}</span>
        </a>
        <a class="filter-pill {{ $roleFilter === 'agency' ? 'active' : '' }}"
            href="{{ route('admin.users', array_merge($roleBase, ['role' => 'agency'])) }}" data-role="agency">
            Agencies <span class="pill-count" id="countAgency">{{ $roleCounts->get('agency', 0) }}</span>
        </a>
        <a class="filter-pill {{ $roleFilter === 'customer' ? 'active' : '' }}"
            href="{{ route('admin.users', array_merge($roleBase, ['role' => 'customer'])) }}" data-role="customer">
            Customers <span class="pill-count" id="countCustomer">{{ $roleCounts->get('customer', 0) }}</span>
        </a>
    </div>

    <!-- Users table. -->
    <div class="tn-card-static">
        <div style="overflow-x:auto;">
            <table class="tn-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Bookings</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $initials = collect(explode(' ', $user->name))
                                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                ->take(2)
                                ->implode('');
                        @endphp
                        <tr data-user-name="{{ $user->name }}" data-email="{{ $user->email }}"
                            data-role="{{ $user->role }}" data-status="{{ $user->status }}"
                            data-join="{{ optional($user->created_at)->format('M d, Y') }}"
                            data-bookings="{{ $user->bookings_count ?? 0 }}"
                            data-update-url="{{ route('admin.users.update', $user) }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="tn-avatar" style="background:rgba(45,138,122,0.1);color:var(--primary);">
                                        {{ $initials }}</div>
                                    <div>
                                        <div style="font-weight:600;">{{ $user->name }}</div>
                                        <div class="text-muted-tn" style="font-size:13px;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="tn-badge {{ $user->role === 'admin' ? 'tn-badge-primary' : ($user->role === 'agency' ? 'tn-badge-secondary' : 'tn-badge-muted') }}">
                                    {{ ucfirst($user->role) }}</span>
                            </td>
                            <td>
                                <span
                                    class="tn-badge {{ $user->status === 'active' ? 'tn-badge-success' : ($user->status === 'pending' ? 'tn-badge-warning' : 'tn-badge-danger') }}">
                                    {{ ucfirst($user->status) }}</span>
                            </td>
                            <td>{{ optional($user->created_at)->format('M d, Y') }}</td>
                            <td>{{ $user->bookings_count ?? 0 }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="tn-action-btn" data-bs-toggle="dropdown"><i
                                            class="fas fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" data-action="view"><i
                                                    class="fas fa-eye me-2"></i>View Profile</a></li>
                                        <li><a class="dropdown-item" href="#" data-action="edit"><i
                                                    class="fas fa-user-edit me-2"></i>Edit</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="name" value="{{ $user->name }}">
                                                <input type="hidden" name="email" value="{{ $user->email }}">
                                                <input type="hidden" name="role" value="{{ $user->role }}">
                                                <input type="hidden" name="status"
                                                    value="{{ $user->status === 'active' ? 'suspended' : 'active' }}">
                                                <button class="dropdown-item" type="submit">
                                                    <i class="fas fa-ban me-2"></i>
                                                    {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Delete this user?');">
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
                            <td colspan="6" class="text-muted-tn" style="padding:1.5rem;">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3">
            <span class="text-muted-tn" style="font-size:14px;">Showing {{ $users->firstItem() ?? 0 }} to
                {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</span>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- User Detail Modal -->
    <div class="modal fade" id="userDetailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-family:'Space Grotesk';font-weight:600;">User Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Name</span>
                            <span id="userDetailName">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Email</span>
                            <span id="userDetailEmail">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Role</span>
                            <span id="userDetailRole" class="tn-badge tn-badge-muted">Customer</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Status</span>
                            <span id="userDetailStatus" class="tn-badge tn-badge-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Joined</span>
                            <span id="userDetailJoin">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted-tn">Bookings</span>
                            <span id="userDetailBookings">-</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline-tn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add / Edit User Modal -->
    <div class="modal fade" id="userFormModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userFormTitle" style="font-family:'Space Grotesk';font-weight:600;">Add
                        User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="userFormMethod" value="POST">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="tn-form-label" for="userName">Full Name</label>
                                <input class="tn-form-control" id="userName" name="name" type="text" required>
                            </div>
                            <div class="col-12">
                                <label class="tn-form-label" for="userEmail">Email</label>
                                <input class="tn-form-control" id="userEmail" name="email" type="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="tn-form-label" for="userRole">Role</label>
                                <select class="tn-form-control" id="userRole" name="role" required>
                                    <option value="customer">Customer</option>
                                    <option value="agency">Agency</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="tn-form-label" for="userStatus">Status</label>
                                <select class="tn-form-control" id="userStatus" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="tn-form-label" for="userPassword">Password (optional)</label>
                                <input class="tn-form-control" id="userPassword" name="password" type="password"
                                    placeholder="Leave blank to generate">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-outline-tn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-tn">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterForm = document.querySelector('form[action="{{ route('admin.users') }}"]');
            const statusSelect = filterForm?.querySelector('select[name="status"]');
            const form = document.getElementById('userForm');
            const formTitle = document.getElementById('userFormTitle');
            const formMethod = document.getElementById('userFormMethod');
            const addButton = document.querySelector('[data-add-user]');
            const detailModalElement = document.getElementById('userDetailModal');
            const detailModal = detailModalElement ? new bootstrap.Modal(detailModalElement) : null;

            const resetForm = () => {
                form.action = "{{ route('admin.users.store') }}";
                formMethod.value = 'POST';
                form.reset();
                document.getElementById('userRole').value = 'customer';
                document.getElementById('userStatus').value = 'active';
            };

            const setDetailModal = (row) => {
                if (!detailModalElement) return;
                detailModalElement.querySelector('#userDetailName').textContent = row.dataset.userName || 'User';
                detailModalElement.querySelector('#userDetailEmail').textContent = row.dataset.email || '';
                const roleBadge = detailModalElement.querySelector('#userDetailRole');
                const statusBadge = detailModalElement.querySelector('#userDetailStatus');
                const role = row.dataset.role || 'customer';
                const status = row.dataset.status || 'active';
                roleBadge.className = role === 'admin'
                    ? 'tn-badge tn-badge-primary'
                    : role === 'agency'
                        ? 'tn-badge tn-badge-secondary'
                        : 'tn-badge tn-badge-muted';
                roleBadge.textContent = role.charAt(0).toUpperCase() + role.slice(1);
                statusBadge.className = status === 'active'
                    ? 'tn-badge tn-badge-success'
                    : status === 'pending'
                        ? 'tn-badge tn-badge-warning'
                        : 'tn-badge tn-badge-danger';
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                detailModalElement.querySelector('#userDetailJoin').textContent = row.dataset.join || '-';
                detailModalElement.querySelector('#userDetailBookings').textContent = row.dataset.bookings || '-';
            };

            if (addButton) {
                addButton.addEventListener('click', () => {
                    if (formTitle) formTitle.textContent = 'Add User';
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
                    if (formTitle) formTitle.textContent = 'Edit User';
                    form.action = row.dataset.updateUrl;
                    formMethod.value = 'PUT';
                    document.getElementById('userName').value = row.dataset.userName || '';
                    document.getElementById('userEmail').value = row.dataset.email || '';
                    document.getElementById('userRole').value = row.dataset.role || 'customer';
                    document.getElementById('userStatus').value = row.dataset.status || 'active';
                    document.getElementById('userPassword').value = '';
                    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('userFormModal'));
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
