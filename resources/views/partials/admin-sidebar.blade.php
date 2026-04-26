<!-- Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="fas fa-globe"></i>
        </div>
        <div>
            <div class="sidebar-brand-text">TravelNest</div>
            <div class="sidebar-brand-subtitle">ADMIN PANEL</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <!-- MAIN -->
        <div class="nav-group-label">MAIN</div>
        <a href="{{ url('/admin') }}" class="sidebar-nav-item {{ request()->is('admin') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span class="nav-label-text">Dashboard</span>
        </a>
        <a href="{{ url('/admin/agencies') }}" class="sidebar-nav-item {{ request()->is('admin/agencies*') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span class="nav-label-text">Agencies</span>
        </a>
        <a href="{{ url('/admin/users') }}" class="sidebar-nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span class="nav-label-text">Users</span>
        </a>
        <a href="{{ url('/admin/packages') }}" class="sidebar-nav-item {{ request()->is('admin/packages*') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i>
            <span class="nav-label-text">Tour Packages</span>
        </a>
        <a href="{{ url('/admin/bookings') }}" class="sidebar-nav-item {{ request()->is('admin/bookings*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span class="nav-label-text">Bookings</span>
        </a>

        <!-- MANAGEMENT -->
        <div class="nav-group-label">MANAGEMENT</div>
        <a href="{{ url('/admin/hotels') }}" class="sidebar-nav-item {{ request()->is('admin/hotels*') ? 'active' : '' }}">
            <i class="fas fa-hotel"></i>
            <span class="nav-label-text">Hotels</span>
        </a>
        <a href="{{ url('/admin/transport') }}" class="sidebar-nav-item {{ request()->is('admin/transport*') ? 'active' : '' }}">
            <i class="fas fa-bus"></i>
            <span class="nav-label-text">Transport</span>
        </a>
        <a href="{{ url('/admin/coupons') }}" class="sidebar-nav-item {{ request()->is('admin/coupons*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i>
            <span class="nav-label-text">Coupons</span>
        </a>
        <a href="{{ url('/admin/payments') }}" class="sidebar-nav-item {{ request()->is('admin/payments*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i>
            <span class="nav-label-text">Payments</span>
        </a>
        <a href="{{ url('/admin/reports') }}" class="sidebar-nav-item {{ request()->is('admin/reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span class="nav-label-text">Reports</span>
        </a>

        <!-- SYSTEM -->
        <div class="nav-group-label">SYSTEM</div>
        <a href="{{ url('/admin/settings') }}" class="sidebar-nav-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="nav-label-text">Settings</span>
        </a>
    </nav>

    <!-- User Section -->
    <div class="sidebar-user-section">
        <div class="sidebar-user-avatar">AD</div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">Admin User</div>
            <div class="sidebar-user-email">admin@travelnest.com</div>
        </div>
        <button class="sidebar-logout-btn" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </div>
</aside>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
