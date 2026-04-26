<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — TravelNest</title>
    <meta name="description" content="TravelNest Admin Panel — Manage agencies, bookings, tours, and more.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        @include('partials.admin-sidebar')

        <!-- Main Content -->
        <div class="admin-main" id="adminMain">
            <!-- Header -->
            @include('partials.admin-header')

            <!-- Content -->
            <main class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const notificationToggle = document.getElementById('notificationToggle');
            const notificationPanel = document.getElementById('notificationPanel');
            const notificationBadge = document.getElementById('notificationBadge');
            const markNotificationsRead = document.getElementById('markNotificationsRead');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const isMobile = () => window.innerWidth < 992;

            toggleBtn.addEventListener('click', function() {
                if (isMobile()) {
                    sidebar.classList.toggle('mobile-open');
                    overlay.classList.toggle('active');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            });

            overlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            });

            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('active');
                }
            });

            const closeNotifications = () => {
                if (!notificationPanel || !notificationToggle) return;
                notificationPanel.classList.remove('is-open');
                notificationToggle.setAttribute('aria-expanded', 'false');
            };

            const openNotifications = () => {
                if (!notificationPanel || !notificationToggle) return;
                notificationPanel.classList.add('is-open');
                notificationToggle.setAttribute('aria-expanded', 'true');
            };

            const updateUnreadUI = (count) => {
                const countEl = notificationPanel?.querySelector('.notification-count');
                if (notificationBadge) {
                    if (count > 0) {
                        notificationBadge.textContent = count;
                    } else {
                        notificationBadge.remove();
                    }
                }
                if (countEl) {
                    if (count > 0) {
                        countEl.textContent = `${count} new`;
                    } else {
                        countEl.remove();
                    }
                }
                if (notificationToggle) {
                    if (count > 0) {
                        notificationToggle.classList.add('has-unread');
                    } else {
                        notificationToggle.classList.remove('has-unread');
                    }
                }
            };

            const markItemRead = (item) => {
                if (!item || item.classList.contains('is-read')) return;
                item.classList.remove('is-unread');
                item.classList.add('is-read');
                const dot = item.querySelector('.notification-dot');
                if (dot) dot.remove();
                const markButton = item.querySelector('.notification-mark-read');
                if (markButton) markButton.remove();

                if (notificationBadge) {
                    const nextCount = Math.max(0, Number(notificationBadge.textContent || 0) - 1);
                    updateUnreadUI(nextCount);
                }
            };

            if (notificationToggle && notificationPanel) {
                if (notificationBadge) {
                    notificationToggle.classList.add('has-unread');
                }

                notificationToggle.addEventListener('click', function(event) {
                    event.stopPropagation();
                    if (notificationPanel.classList.contains('is-open')) {
                        closeNotifications();
                    } else {
                        openNotifications();
                    }
                });

                document.addEventListener('click', function(event) {
                    if (!notificationPanel.contains(event.target) && !notificationToggle.contains(event.target)) {
                        closeNotifications();
                    }
                });

                notificationPanel.querySelectorAll('.notification-mark-read').forEach((button) => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        const item = button.closest('.notification-item');
                        const url = item?.dataset.readUrl;
                        if (!url || !csrfToken) {
                            markItemRead(item);
                            return;
                        }
                        fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin',
                        }).then(() => markItemRead(item));
                    });
                });

                notificationPanel.querySelectorAll('.notification-content').forEach((link) => {
                    link.addEventListener('click', function(event) {
                        const item = link.closest('.notification-item');
                        const url = item?.dataset.readUrl;
                        if (!url || !csrfToken || item?.classList.contains('is-read')) {
                            return;
                        }
                        event.preventDefault();
                        fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin',
                            keepalive: true,
                        }).then(() => {
                            markItemRead(item);
                            window.location.href = link.getAttribute('href');
                        });
                    });
                });
            }

            if (markNotificationsRead && notificationPanel) {
                markNotificationsRead.addEventListener('click', function(event) {
                    event.preventDefault();
                    const url = notificationPanel.getAttribute('data-mark-all-url');
                    if (url && csrfToken) {
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin',
                        }).then(() => {
                            notificationPanel.querySelectorAll('.notification-item.is-unread').forEach((item) => {
                                markItemRead(item);
                            });
                            updateUnreadUI(0);
                        });
                    } else {
                        notificationPanel.querySelectorAll('.notification-item.is-unread').forEach((item) => {
                            markItemRead(item);
                        });
                        updateUnreadUI(0);
                    }
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
