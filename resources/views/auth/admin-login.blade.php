<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title for the admin login screen. -->
    <title>Admin Login — TravelNest</title>
    <meta name="description" content="Sign in to the TravelNest Admin Portal.">

    <!-- Google Fonts for consistent admin typography. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3 for layout and components. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome icons. -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Custom admin styling. -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body style="margin:0; padding:0;">
    <div class="login-page">
        <div class="row g-0">
            <!-- Left Side — branding and marketing copy. -->
            <div class="col-lg-6">
                <div class="login-left">
                    <div class="login-left-content">
                        <div class="login-brand-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h1 class="login-brand-name">TravelNest</h1>
                        <p class="login-brand-tagline">Admin Portal</p>

                        <p class="login-subtitle">
                            Manage your travel empire with powerful tools and real-time insights
                        </p>

                        <div class="login-stats">
                            <div class="login-stat">
                                <div class="login-stat-value">1,200+</div>
                                <div class="login-stat-label">Agencies</div>
                            </div>
                            <div class="login-stat">
                                <div class="login-stat-value">50K+</div>
                                <div class="login-stat-label">Bookings</div>
                            </div>
                            <div class="login-stat">
                                <div class="login-stat-value">$2.5M</div>
                                <div class="login-stat-label">Revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side — admin login form. -->
            <div class="col-lg-6">
                <div class="login-right">
                    <div class="login-form-card">
                        <h2 class="login-heading">Welcome back</h2>
                        <p class="login-subheading">Sign in to your admin account</p>

                        {{-- Show validation errors after a failed login attempt. --}}
                        @if ($errors->any())
                            <div class="alert alert-danger"
                                style="border-radius:8px; font-size:14px; border:none; background:rgba(229,69,69,0.1); color:var(--destructive);">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Login form posts to the admin login route. --}}
                        <form method="POST" action="{{ url('/admin/login') }}">
                            @csrf

                            <!-- Email field for admin login. -->
                            <div class="login-form-group">
                                <label class="tn-form-label" for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="tn-form-control"
                                    placeholder="admin@travelnest.com" value="{{ old('email') }}" required autofocus>
                            </div>

                            <!-- Password field with show/hide toggle. -->
                            <div class="login-form-group">
                                <label class="tn-form-label" for="password">Password</label>
                                <div class="login-password-wrapper">
                                    <input type="password" id="password" name="password" class="tn-form-control"
                                        placeholder="Enter your password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember me and forgot password shortcut. -->
                            <div class="login-remember-row">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember" style="font-size:14px;">Remember
                                        me</label>
                                </div>
                                <a href="#">Forgot password?</a>
                            </div>

                            <!-- Submit button for login. -->
                            <button type="submit" class="login-submit-btn">
                                Sign In
                            </button>

                            <!-- Divider between login and social buttons. -->
                            <div class="login-divider">or continue with</div>

                            <!-- Social buttons are UI-only in this demo. -->
                            <div class="login-social-row">
                                <button type="button" class="login-social-btn">
                                    <i class="fab fa-google"></i>
                                    Google
                                </button>
                                <button type="button" class="login-social-btn">
                                    <i class="fab fa-apple"></i>
                                    Apple
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS for layout behavior. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility for the admin login form.
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
