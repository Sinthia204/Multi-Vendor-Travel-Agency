<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TravelNest - Plan journeys that feel effortless')</title>
    <meta name="description" content="@yield('meta_description', 'TravelNest helps you discover curated destinations, flexible stays, and unforgettable experiences around the world.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/front.css') }}" rel="stylesheet">
    @yield('styles')
</head>

<body>
    @php
        $siteName = getSetting('site_name', 'TravelNest');
        $logoPath = getSetting('site_logo');
        if ($logoPath && !str_starts_with($logoPath, 'http')) {
            $logoPath = Storage::url($logoPath);
        }
    @endphp

    <header class="navbar" id="navbar">
        <div class="container nav-container">
            <a class="logo" href="{{ route('home') }}">
                @if ($logoPath)
                    <img src="{{ $logoPath }}" alt="{{ $siteName }}" style="height:32px; width:auto;">
                @else
                    <i class="fa-solid fa-compass"></i>
                @endif
                {{ $siteName }}
            </a>
            <nav class="nav-links" id="primary-nav">
                <a href="{{ route('destinations') }}" class="{{ request()->routeIs('destinations') ? 'active' : '' }}"
                    aria-current="{{ request()->routeIs('destinations') ? 'page' : 'false' }}">Destinations</a>
                <a href="{{ route('packages') }}" class="{{ request()->routeIs('packages') ? 'active' : '' }}"
                    aria-current="{{ request()->routeIs('packages') ? 'page' : 'false' }}">Packages</a>
                <a href="{{ route('experiences') }}" class="{{ request()->routeIs('experiences') ? 'active' : '' }}"
                    aria-current="{{ request()->routeIs('experiences') ? 'page' : 'false' }}">Experiences</a>
                <a href="{{ route('stories') }}" class="{{ request()->routeIs('stories') ? 'active' : '' }}"
                    aria-current="{{ request()->routeIs('stories') ? 'page' : 'false' }}">Stories</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"
                    aria-current="{{ request()->routeIs('contact') ? 'page' : 'false' }}">Contact</a>
            </nav>
            <div class="nav-actions">
                @guest
                    <button class="btn-login" type="button" data-login-open>Login</button>
                    @if (Route::has('register'))
                        <a class="btn-primary" href="{{ route('register') }}">Get started</a>
                    @endif
                @endguest
                @auth
                    @php
                        $user = auth()->user();
                        $isAdmin = $user && (($user->role ?? null) === 'admin' || ($user->is_admin ?? false));
                        $dashboardUrl = $isAdmin ? route('admin.dashboard') : url('/');
                    @endphp
                    <div class="nav-user" data-user-menu>
                        <button class="user-trigger" type="button" data-user-toggle>
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown">
                            <a href="{{ $dashboardUrl }}">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false"
                aria-controls="primary-nav" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        <div class="nav-backdrop" data-nav-backdrop></div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a class="footer-logo" href="{{ route('home') }}"><i class="fa-solid fa-compass"></i> TravelNest</a>
                    <p class="footer-text">Designing journeys that feel elevated, effortless, and unforgettable.</p>
                    <div class="footer-social">
                        <a href="#" aria-label="TravelNest on Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="TravelNest on X"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" aria-label="TravelNest on Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" aria-label="TravelNest on YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="footer-heading">Company</h4>
                    <ul class="footer-links">
                        <li><a href="#">About TravelNest</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-heading">Explore</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('destinations') }}">Destinations</a></li>
                        <li><a href="{{ route('experiences') }}">Experiences</a></li>
                        <li><a href="{{ route('stories') }}">Stories</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-heading">Support</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="#">Cancellation options</a></li>
                        <li><a href="#">Help center</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">(c) 2026 TravelNest. All rights reserved.</div>
        </div>
    </footer>

    @include('components.login-modal')
    @include('components.register-modal')

    <script>
        const navbar = document.getElementById('navbar');
        const onScroll = () => {
            if (window.scrollY > 40) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };
        window.addEventListener('scroll', onScroll);
        onScroll();

        const modal = document.querySelector('[data-login-modal]');
        const registerModal = document.querySelector('[data-register-modal]');
        const modalOpeners = document.querySelectorAll('[data-login-open]');
        const registerOpeners = document.querySelectorAll('[data-register-open]');
        const modalClosers = modal ? modal.querySelectorAll('[data-login-close]') : [];
        const registerClosers = registerModal ? registerModal.querySelectorAll('[data-register-close]') : [];
        const userToggle = document.querySelector('[data-user-toggle]');
        const userMenu = document.querySelector('[data-user-menu]');
        const navToggle = document.querySelector('[data-nav-toggle]');
        const navBackdrop = document.querySelector('[data-nav-backdrop]');
        const navLinks = document.querySelectorAll('#primary-nav a');

        const setNavOpen = (isOpen) => {
            document.body.classList.toggle('nav-open', isOpen);
            if (navToggle) {
                navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }
        };

        const openModal = () => {
            if (!modal) {
                return;
            }
            modal.classList.add('open');
            if (registerModal) {
                registerModal.classList.remove('open');
            }
            document.body.classList.add('modal-open');
        };

        const openRegisterModal = () => {
            if (!registerModal) {
                return;
            }
            registerModal.classList.add('open');
            if (modal) {
                modal.classList.remove('open');
            }
            document.body.classList.add('modal-open');
        };

        const closeModal = () => {
            if (modal) {
                modal.classList.remove('open');
            }
            if (registerModal) {
                registerModal.classList.remove('open');
            }
            document.body.classList.remove('modal-open');
        };

        modalOpeners.forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                openModal();
            });
        });

        registerOpeners.forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                openRegisterModal();
            });
        });

        modalClosers.forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                closeModal();
            });
        });

        registerClosers.forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                closeModal();
            });
        });

        if (modal) {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });
        }

        if (registerModal) {
            registerModal.addEventListener('click', (event) => {
                if (event.target === registerModal) {
                    closeModal();
                }
            });
        }

        if (userToggle && userMenu) {
            userToggle.addEventListener('click', () => {
                userMenu.classList.toggle('open');
            });

            document.addEventListener('click', (event) => {
                if (!userMenu.contains(event.target)) {
                    userMenu.classList.remove('open');
                }
            });
        }

        if (navToggle) {
            navToggle.addEventListener('click', () => {
                const isOpen = document.body.classList.contains('nav-open');
                setNavOpen(!isOpen);
            });
        }

        if (navBackdrop) {
            navBackdrop.addEventListener('click', () => setNavOpen(false));
        }

        navLinks.forEach((link) => {
            link.addEventListener('click', () => setNavOpen(false));
        });

        if (modal && modal.dataset.openOnLoad === 'true') {
            openModal();
        }

        if (registerModal && registerModal.dataset.openOnLoad === 'true') {
            openRegisterModal();
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
                setNavOpen(false);
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
