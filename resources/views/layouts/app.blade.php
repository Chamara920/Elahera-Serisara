<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('messages.brand_name')) - {{ __('messages.tagline') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Noto+Sans+Sinhala:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-emerald: #059669;
            --primary-dark: #064e3b;
            --primary-light: #10b981;
            --accent-amber: #d97706;
            --accent-cyan: #0284c7;
            --bg-light: #f8fafc;
            --text-dark: #0f172a;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            --hover-shadow: 0 20px 30px -10px rgba(5, 150, 105, 0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Noto Sans Sinhala', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar & Navbar */
        .navbar-main {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 0;
            transition: all 0.3s ease;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
        }

        .navbar-brand-logo img {
            height: 48px;
            width: auto;
        }

        .nav-link {
            font-weight: 600;
            font-size: 0.95rem;
            color: #334155 !important;
            padding: 0.5rem 0.9rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-emerald) !important;
            background-color: #ecfdf5;
        }

        .dropdown-menu {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            padding: 0.5rem;
        }

        .dropdown-item {
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.15s ease;
        }

        .dropdown-item:hover {
            background-color: #ecfdf5;
            color: var(--primary-emerald);
        }

        /* User Profile Button */
        .btn-user-profile {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: var(--primary-emerald);
            border: 2px solid #a7f3d0;
            font-size: 1.15rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-user-profile:hover {
            transform: scale(1.05);
            border-color: var(--primary-emerald);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
        }

        /* Language Switcher */
        .lang-switch-btn {
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            text-decoration: none;
            color: #475569;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .lang-switch-btn.active {
            background: var(--primary-emerald);
            color: #ffffff;
            border-color: var(--primary-emerald);
        }

        /* Card Styles */
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
            border-color: #a7f3d0;
        }

        /* Badges */
        .badge-category {
            background-color: #ecfdf5;
            color: var(--primary-emerald);
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid #a7f3d0;
        }

        .badge-rating {
            background: #fef3c7;
            color: #92400e;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-emerald) 0%, #047857 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.65rem 1.4rem;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #047857 0%, var(--primary-dark) 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-emerald);
            color: var(--primary-emerald);
            background: transparent;
            font-weight: 600;
            padding: 0.6rem 1.3rem;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .btn-outline-custom:hover {
            background: var(--primary-emerald);
            color: white;
        }

        /* Sticky Color Time Meter Bar */
        .sticky-time-meter {
            position: sticky;
            top: 73px;
            z-index: 1020;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            padding: 0.75rem 0;
            transition: all 0.3s;
        }

        /* Notice banner for Sinhala Fallback */
        .sinhala-fallback-banner {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            color: #92400e;
            padding: 0.9rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Footer - Ultra sleek and compact */
        .footer-main {
            background: linear-gradient(rgba(15, 23, 42, 0.92), rgba(15, 23, 42, 0.95)), url('/images/main/footer_background.jpg') center/cover no-repeat;
            color: #94a3b8;
            margin-top: auto;
            padding: 1rem 0 0.45rem 0;
            border-top: 2px solid var(--primary-emerald);
            position: relative;
            font-size: 0.74rem;
        }

        .footer-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 0.8rem;
            margin-bottom: 0.45rem;
            position: relative;
            padding-bottom: 0.2rem;
            letter-spacing: 0.2px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 18px;
            height: 1.5px;
            background: var(--primary-emerald);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .footer-links li {
            margin-bottom: 0.2rem;
        }

        .footer-links a {
            color: #94a3b8;
            font-size: 0.72rem;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .footer-links a:hover {
            color: #34d399;
            transform: translateX(2px);
        }

        .footer-brand-desc {
            font-size: 0.7rem;
            line-height: 1.35;
            color: #94a3b8;
            margin-bottom: 0.4rem;
        }

        .footer-form-control {
            font-size: 0.7rem;
            padding: 0.2rem 0.45rem;
            height: 26px;
            background-color: rgba(15, 23, 42, 0.7);
            border-color: #334155;
            color: #ffffff;
        }

        .footer-form-control:focus {
            background-color: rgba(15, 23, 42, 0.9);
            border-color: var(--primary-emerald);
            color: #ffffff;
            box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 0.45rem;
            margin-top: 0.75rem;
            font-size: 0.68rem;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-main">
            <div class="container-xl">
                <!-- Left: Brand / Logo -->
                <a class="navbar-brand navbar-brand-logo" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.svg') }}" alt="Elahera Serisara" height="46">
                </a>

                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSerisara" aria-controls="navbarSerisara" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSerisara">
                    <!-- Center: Navigation Menu -->
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door me-1"></i> {{ __('messages.home') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('planner.*') ? 'active' : '' }}" href="{{ route('planner.index') }}">
                                <i class="bi bi-calendar2-range me-1 text-primary"></i> {{ __('messages.trip_planner') }}
                            </a>
                        </li>

                        <!-- Explore Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('destinations*', 'map*', 'travel-guide*', 'public-transport*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-compass me-1"></i> {{ __('messages.explore') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('destinations.index') }}">
                                        <i class="bi bi-geo-alt-fill text-success me-2"></i> {{ __('messages.destinations') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('map.index') }}">
                                        <i class="bi bi-map-fill text-primary me-2"></i> {{ __('messages.interactive_map') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('guide.index') }}">
                                        <i class="bi bi-journal-bookmark-fill text-warning me-2"></i> {{ __('messages.travel_guide') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('transport.index') }}">
                                        <i class="bi bi-bus-front-fill text-danger me-2"></i> {{ __('messages.public_transport') }}
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Community Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('blog*', 'forum*', 'news*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-people me-1"></i> {{ __('messages.community') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('blog.index') }}">
                                        <i class="bi bi-newspaper text-info me-2"></i> {{ __('messages.blog') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('forum.index') }}">
                                        <i class="bi bi-chat-square-text text-primary me-2"></i> {{ __('messages.forum') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('news.index') }}">
                                        <i class="bi bi-megaphone-fill text-danger me-2"></i> {{ __('messages.news_events') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Right: Search, Language Switcher & Interactive User Control -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Global Search Box -->
                        <form action="{{ route('search') }}" method="GET" class="d-none d-lg-block position-relative" style="width: 200px;">
                            <input type="text" name="q" class="form-control form-control-sm rounded-pill ps-4 bg-light border" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('q') }}">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted small"></i>
                        </form>

                        <!-- Language Switcher (EN / SI) -->
                        <div class="d-flex align-items-center gap-1">
                            <a href="{{ route('locale.switch', 'en') }}" class="lang-switch-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" title="English">EN</a>
                            <a href="{{ route('locale.switch', 'si') }}" class="lang-switch-btn {{ app()->getLocale() === 'si' ? 'active' : '' }}" title="සිංහල">සිං</a>
                        </div>

                        <!-- Interactive User Icon (Profile Dropdown) -->
                        <div class="dropdown">
                            <button class="btn-user-profile dropdown-toggle shadow-none" type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                @auth
                                    <i class="bi bi-person-fill"></i>
                                @else
                                    <i class="bi bi-person"></i>
                                @endauth
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end p-2 mt-2" aria-labelledby="userProfileDropdown" style="min-width: 240px;">
                                @auth
                                    <!-- User Header Info -->
                                    <li class="px-3 py-2 border-bottom mb-2 bg-light rounded-3">
                                        <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                                        <div class="small text-muted text-truncate">{{ Auth::user()->email }}</div>
                                        @if(Auth::user()->isAdmin())
                                            <span class="badge bg-danger mt-1">Admin</span>
                                        @else
                                            <span class="badge bg-success mt-1">Community Member</span>
                                        @endif
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('user.profile') }}">
                                            <i class="bi bi-person-vcard text-primary me-2"></i> {{ __('messages.my_profile') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('user.saved-trips') }}">
                                            <i class="bi bi-bookmark-heart text-success me-2"></i> {{ __('messages.saved_trips') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('user.my-blogs') }}">
                                            <i class="bi bi-journal-text text-warning me-2"></i> {{ __('messages.my_blog_posts') }}
                                        </a>
                                    </li>
                                    @if(Auth::user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item py-2 text-danger fw-semibold" href="/admin" target="_blank">
                                                <i class="bi bi-speedometer2 text-danger me-2"></i> Filament Admin Panel
                                            </a>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider my-2"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-danger fw-semibold">
                                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.sign_out') }}
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li class="px-3 py-2 text-center text-muted small">
                                        Join our explorer community!
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold text-success" href="{{ route('login') }}">
                                            <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('messages.sign_in') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 fw-semibold text-primary" href="{{ route('register') }}">
                                            <i class="bi bi-person-plus me-2"></i> {{ __('messages.register') }}
                                        </a>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Flash Notifications -->
    <div class="container-xl mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-target="alert" aria-label="Close" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-octagon-fill fs-5 me-2 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-target="alert" aria-label="Close" onclick="this.parentElement.remove()"></button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer (4 Equal Columns on Desktop - Streamlined & Compact) -->
    <footer class="footer-main">
        <div class="container-xl">
            <div class="row g-2 g-lg-3">
                <!-- Col 1: Brand & About -->
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-1.5">
                        <img src="{{ asset('images/logo.svg') }}" alt="Elahera Serisara" height="28" class="bg-white p-1 rounded-2">
                    </div>
                    <div class="fw-bold text-white mb-0" style="font-size: 0.85rem;">{{ __('messages.brand_name') }}</div>
                    <div class="text-success fw-bold text-uppercase letter-spacing-1 mb-1" style="font-size: 0.62rem;">{{ __('messages.tagline') }}</div>
                    <p class="footer-brand-desc">
                        {{ __('messages.brand_description') }}
                    </p>
                    <a href="{{ route('about') }}" class="btn btn-outline-success rounded-pill px-2 py-0.5" style="font-size: 0.68rem; line-height: 1.2;">
                        <i class="bi bi-info-circle me-1"></i> {{ __('messages.read_more_about') }}
                    </a>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">{{ __('messages.quick_links') }}</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('destinations.index') }}"><i class="bi bi-chevron-right text-success" style="font-size: 0.6rem;"></i> {{ __('messages.destinations') }}</a></li>
                        <li><a href="{{ route('planner.index') }}"><i class="bi bi-chevron-right text-success" style="font-size: 0.6rem;"></i> {{ __('messages.trip_planner') }}</a></li>
                        <li><a href="{{ route('map.index') }}"><i class="bi bi-chevron-right text-success" style="font-size: 0.6rem;"></i> {{ __('messages.interactive_map') }}</a></li>
                        <li><a href="{{ route('transport.index') }}"><i class="bi bi-chevron-right text-success" style="font-size: 0.6rem;"></i> {{ __('messages.public_transport') }}</a></li>
                        <li><a href="{{ route('emergency.index') }}"><i class="bi bi-chevron-right text-danger" style="font-size: 0.6rem;"></i> {{ __('messages.emergency_info') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Community Hub -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">{{ __('messages.community_hub') }}</h5>
                    <ul class="footer-links mb-1.5">
                        <li><a href="{{ route('blog.index') }}"><i class="bi bi-journal-text text-info" style="font-size: 0.65rem;"></i> {{ __('messages.community_blog') }}</a></li>
                        <li><a href="{{ route('forum.index') }}"><i class="bi bi-chat-dots text-primary" style="font-size: 0.65rem;"></i> {{ __('messages.forum') }}</a></li>
                        <li><a href="{{ route('news.index') }}"><i class="bi bi-megaphone text-warning" style="font-size: 0.65rem;"></i> {{ __('messages.news_events') }}</a></li>
                        <li><a href="{{ route('guide.index') }}"><i class="bi bi-card-checklist text-success" style="font-size: 0.65rem;"></i> {{ __('messages.contribute_guideline') }}</a></li>
                    </ul>
                    <a href="{{ route('blog.create') }}" class="btn btn-success rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.68rem; line-height: 1.2;">
                        <i class="bi bi-pencil-square me-1"></i> {{ __('messages.become_contributor') }}
                    </a>
                </div>

                <!-- Col 4: Contact Us Form -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">{{ __('messages.contact_us') }}</h5>
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                            <input type="text" name="name" class="form-control form-control-sm footer-form-control rounded-2" placeholder="Your Name" required>
                        </div>
                        <div class="mb-1">
                            <input type="email" name="email" class="form-control form-control-sm footer-form-control rounded-2" placeholder="Your Email" required>
                        </div>
                        <div class="mb-1">
                            <input type="text" name="message" class="form-control form-control-sm footer-form-control rounded-2" placeholder="Your Message / Query" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom py-0.5 w-100 fw-semibold" style="font-size: 0.7rem; line-height: 1.3;">
                            <i class="bi bi-send me-1"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-1">
                <div class="text-secondary" style="font-size: 0.68rem;">
                    &copy; {{ date('Y') }} <strong>Elahera Serisara (ඇලහැර සැරිසර)</strong>. All rights reserved.
                </div>
                <div class="d-flex gap-3 text-secondary" style="font-size: 0.68rem;">
                    <a href="{{ route('about') }}" class="text-secondary text-decoration-none hover-emerald">About</a>
                    <a href="{{ route('guide.index') }}" class="text-secondary text-decoration-none hover-emerald">Guide</a>
                    <a href="{{ route('emergency.index') }}" class="text-secondary text-decoration-none hover-emerald">Emergency</a>
                    <a href="/admin" class="text-secondary text-decoration-none hover-emerald" target="_blank">Admin Portal</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @stack('scripts')
</body>
</html>
