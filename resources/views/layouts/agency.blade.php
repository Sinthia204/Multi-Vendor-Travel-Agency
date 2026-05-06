<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agency Portal') — TravelNest</title>
    <meta name="description" content="TravelNest Agency Portal">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar" id="agencySidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div>
                    <div class="sidebar-brand-text">TravelNest</div>
                    <div class="sidebar-brand-subtitle">AGENCY PORTAL</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-group-label">PORTAL</div>
                <a href="{{ route('agency.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('agency.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="nav-label-text">Dashboard</span>
                </a>
                <a href="{{ route('agency.packages.index') }}" class="sidebar-nav-item {{ request()->is('agency/packages*') ? 'active' : '' }}">
                    <i class="fas fa-box-open"></i>
                    <span class="nav-label-text">My Packages</span>
                </a>
            </nav>

            <div class="sidebar-user-section">
                <div class="sidebar-user-avatar">AG</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth('agency')->user()->name ?? 'Agency' }}</div>
                    <div class="sidebar-user-email">{{ auth('agency')->user()->email ?? '' }}</div>
                </div>
                <form method="POST" action="{{ route('agency.logout') }}">
                    @csrf
                    <button class="sidebar-logout-btn" title="Logout" type="submit">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </aside>

        <div class="admin-main" id="agencyMain">
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="header-page-title">@yield('page-title', 'Agency Portal')</h1>
                </div>
                <div class="header-right">
                    <a href="{{ url('/') }}" class="header-back-link">
                        <i class="fas fa-chevron-left"></i>
                        <span>Back to Site</span>
                    </a>
                </div>
            </header>

            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('agencySidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const isMobile = () => window.innerWidth < 992;

            toggleBtn.addEventListener('click', function() {
                if (isMobile()) {
                    sidebar.classList.toggle('mobile-open');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
