<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('messages.brand_name')) - {{ __('messages.tagline') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts (Plus Jakarta Sans, Noto Sans Sinhala) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Noto+Sans+Sinhala:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS (Direction A: Premium Nature & Heritage) -->
    <style>
        :root {
            --primary-emerald: #059669;
            --primary-dark: #064e3b;
            --primary-light: #10b981;
            --primary-soft: #ecfdf5;
            --accent-amber: #f59e0b;
            --accent-gold: #d97706;
            --accent-cyan: #0284c7;
            --bg-light: #f8fafc;
            --bg-surface: #ffffff;
            --text-dark: #0f172a;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            --hover-shadow: 0 16px 28px -8px rgba(6, 78, 59, 0.16);
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Noto Sans Sinhala', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        /* Top Bar & Navbar - Clean, Elegant & Unified */
        .navbar-main {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0.55rem 0;
            min-height: 66px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 20px -4px rgba(6, 78, 59, 0.08);
        }

        .navbar-brand-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
        }

        .navbar-brand-logo img {
            height: 42px;
            width: auto;
            object-fit: contain;
            background: transparent;
            border: none !important;
            box-shadow: none !important;
            transition: transform 0.2s ease;
        }

        .navbar-brand-logo:hover img {
            transform: scale(1.03);
        }

        .brand-text-name {
            font-weight: 800;
            font-size: 1.25rem;
            color: #064e3b;
            letter-spacing: -0.3px;
            line-height: 1.15;
        }

        .brand-text-tagline {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--primary-emerald);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* Unified, Ultra-Attractive Navigation Items */
        .navbar-nav {
            gap: 0.35rem;
            align-items: center;
        }

        .nav-link-custom {
            font-weight: 600;
            font-size: 0.92rem;
            color: #334155 !important;
            padding: 0.45rem 0.95rem !important;
            height: 38px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap !important;
            transition: all 0.2s ease;
            text-decoration: none;
            position: relative;
        }

        .nav-link-custom i.nav-icon {
            font-size: 0.95rem;
            color: #059669;
            transition: color 0.2s ease;
        }

        .nav-link-custom:hover {
            color: var(--primary-emerald) !important;
            background-color: #f0fdf4;
        }

        .nav-link-custom.active {
            color: #064e3b !important;
            background-color: #ecfdf5;
            font-weight: 700;
        }

        /* Smart Trip Planner - High Visual Appeal Solid Pill Button */
        .nav-link-smart-planner {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 0.88rem;
            border-radius: 20px;
            box-shadow: 0 3px 10px rgba(5, 150, 105, 0.22);
            white-space: nowrap !important;
            height: 38px;
            display: inline-flex;
            align-items: center;
            padding: 0 1rem !important;
            gap: 6px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .nav-link-smart-planner i.nav-icon {
            color: #ffffff !important;
            font-size: 0.95rem;
        }

        .nav-link-smart-planner:hover {
            background: linear-gradient(135deg, #047857 0%, #064e3b 100%) !important;
            box-shadow: 0 5px 14px rgba(5, 150, 105, 0.35);
            transform: translateY(-1px);
            color: #ffffff !important;
        }

        .badge-smart-planner {
            font-size: 0.58rem;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
            padding: 2px 5px;
            border-radius: 8px;
            letter-spacing: 0.5px;
            margin-left: 4px;
            text-transform: uppercase;
            line-height: 1;
        }

        /* Dropdowns with animations */
        .dropdown-menu {
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 14px;
            box-shadow: 0 18px 40px -10px rgba(15, 23, 42, 0.12);
            padding: 0.5rem;
            animation: fadeInDown 0.2s ease-in-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            font-weight: 500;
            font-size: 0.88rem;
            border-radius: 8px;
            padding: 0.5rem 0.85rem;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: #ecfdf5;
            color: var(--primary-emerald);
            transform: translateX(3px);
        }

        /* User Profile Button */
        .btn-user-profile {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: var(--primary-emerald);
            border: 2px solid #a7f3d0;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.12);
        }

        .btn-user-profile:hover {
            transform: scale(1.05);
            border-color: var(--primary-emerald);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        /* Language Switcher Segmented Control */
        .lang-switch-box {
            background: #f1f5f9;
            padding: 3px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            height: 38px;
            display: inline-flex;
            align-items: center;
        }

        .lang-switch-btn {
            font-size: 0.76rem;
            font-weight: 700;
            padding: 0.22rem 0.55rem;
            border-radius: 14px;
            text-decoration: none;
            color: #64748b;
            background: transparent;
            transition: all 0.2s;
            line-height: 1.2;
        }

        .lang-switch-btn.active {
            background: var(--primary-emerald);
            color: #ffffff;
            box-shadow: 0 2px 5px rgba(5, 150, 105, 0.25);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-emerald) 0%, #047857 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.15rem;
            border-radius: 20px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 3px 10px rgba(5, 150, 105, 0.22);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #047857 0%, var(--primary-dark) 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(5, 150, 105, 0.30);
        }

        .btn-outline-custom {
            border: 1.5px solid var(--primary-emerald);
            color: var(--primary-emerald);
            background: transparent;
            font-weight: 600;
            padding: 0.5rem 1.15rem;
            border-radius: 20px;
            transition: all 0.25s ease;
        }

        .btn-outline-custom:hover {
            background: var(--primary-emerald);
            color: white;
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
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #047857;
            font-weight: 700;
            padding: 0.35rem 0.8rem;
            border-radius: 20px;
            font-size: 0.78rem;
            border: 1px solid #a7f3d0;
            box-shadow: 0 2px 5px rgba(5, 150, 105, 0.08);
        }

        .badge-rating {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            font-weight: 700;
            padding: 0.35rem 0.7rem;
            border-radius: 20px;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1px solid #fcd34d;
            box-shadow: 0 2px 5px rgba(217, 119, 6, 0.1);
        }

        /* Sticky Color Time Meter Bar */
        .sticky-time-meter {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            padding: 0.75rem 0;
            transition: all 0.3s;
        }

        /* Notice banner for Fallback */
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

        /* Footer - Sleek, Compact & Streamlined */
        .footer-main {
            background: linear-gradient(rgba(6, 78, 59, 0.96), rgba(15, 23, 42, 0.98)), url('/images/main/footer_background.jpg') center/cover no-repeat;
            color: #cbd5e1;
            margin-top: auto;
            padding: 1.25rem 0 0.5rem 0;
            border-top: 3px solid var(--primary-emerald);
            position: relative;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        .footer-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 0.88rem;
            margin-bottom: 0.45rem;
            position: relative;
            padding-bottom: 0.25rem;
            letter-spacing: 0.2px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 22px;
            height: 2px;
            background: var(--primary-light);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .footer-links li {
            margin-bottom: 0.22rem;
        }

        .footer-links a {
            color: #cbd5e1;
            font-size: 0.80rem;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .footer-links a:hover {
            color: #6ee7b7;
            transform: translateX(2px);
        }

        .footer-brand-desc {
            font-size: 0.78rem;
            line-height: 1.4;
            color: #cbd5e1;
            margin-bottom: 0.45rem;
        }

        .footer-form-control {
            font-size: 0.78rem;
            padding: 0.3rem 0.55rem;
            height: 30px;
            background-color: rgba(15, 23, 42, 0.65);
            border-color: #334155;
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .footer-form-control:focus {
            background-color: rgba(15, 23, 42, 0.95);
            border-color: var(--primary-light);
            color: #ffffff;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
        }

        .footer-form-control::placeholder {
            color: #94a3b8;
            font-size: 0.74rem;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 0.45rem;
            margin-top: 0.75rem;
            font-size: 0.74rem;
            color: #94a3b8;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-main">
            <div class="container-xl">
                <!-- Left: Brand / Logo with Text -->
                <a class="navbar-brand navbar-brand-logo py-0 me-3 me-xl-4 d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Elahera Serisara" height="42" class="object-fit-contain">
                    <div class="d-flex flex-column text-start">
                        <span class="brand-text-name">{{ __('messages.brand_name') }}</span>
                        <span class="brand-text-tagline">{{ __('messages.tagline') }}</span>
                    </div>
                </a>

                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSerisara" aria-controls="navbarSerisara" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSerisara">
                    <!-- Center/Right Navigation Menu with Single-Line Cohesive Styling -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                        <!-- Home -->
                        <li class="nav-item">
                            <a class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door nav-icon"></i>
                                <span>{{ __('messages.home') }}</span>
                            </a>
                        </li>

                        <!-- Smart Trip Planner (Single-Line Highlighted Pill) -->
                        <li class="nav-item">
                            <a class="nav-link-smart-planner {{ request()->routeIs('planner.*') ? 'active' : '' }}" href="{{ route('planner.index') }}">
                                <i class="bi bi-calendar2-range nav-icon"></i>
                                <span>{{ __('messages.trip_planner') }}</span>
                                <span class="badge-smart-planner">SMART</span>
                            </a>
                        </li>

                        <!-- Explore Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link-custom dropdown-toggle {{ request()->is('destinations*', 'map*', 'travel-guide*', 'public-transport*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-compass nav-icon"></i>
                                <span>{{ __('messages.explore') }}</span>
                            </a>
                            <ul class="dropdown-menu shadow-lg border-0 p-2 mt-2" style="min-width: 230px;">
                                <li>
                                    <a class="dropdown-item py-2 px-2.5 rounded-2" href="{{ route('destinations.index') }}">
                                        <i class="bi bi-geo-alt-fill text-success me-2 fs-6"></i>
                                        <span>{{ __('messages.destinations') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 px-2.5 rounded-2" href="{{ route('map.index') }}">
                                        <i class="bi bi-map-fill text-primary me-2 fs-6"></i>
                                        <span>{{ __('messages.interactive_map') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 px-2.5 rounded-2" href="{{ route('guide.index') }}">
                                        <i class="bi bi-journal-bookmark-fill text-warning me-2 fs-6"></i>
                                        <span>{{ __('messages.travel_guide') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 px-2.5 rounded-2" href="{{ route('transport.index') }}">
                                        <i class="bi bi-bus-front-fill text-danger me-2 fs-6"></i>
                                        <span>{{ __('messages.public_transport') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Community Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link-custom dropdown-toggle {{ request()->is('blog*', 'forum*', 'news*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-chat-heart nav-icon"></i>
                                <span>{{ __('messages.community') }}</span>
                            </a>
                            <ul class="dropdown-menu shadow-lg border-0 p-2 mt-2" style="min-width: 220px;">
                                <li>
                                    <a class="dropdown-item py-2 px-2.5 rounded-2" href="{{ route('blog.index') }}">
                                        <i class="bi bi-newspaper text-info me-2 fs-6"></i>
                                        <span>{{ __('messages.blog') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 px-2.5 rounded-2" href="{{ route('forum.index') }}">
                                        <i class="bi bi-chat-square-text text-primary me-2 fs-6"></i>
                                        <span>{{ __('messages.forum') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 px-2.5 rounded-2" href="{{ route('news.index') }}">
                                        <i class="bi bi-megaphone-fill text-danger me-2 fs-6"></i>
                                        <span>{{ __('messages.news_events') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Right Controls: Language Switcher (EN, SI), Guest Actions & User Profile -->
                    <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">
                        <!-- Desktop Language Switcher (EN / සිං) -->
                        <div class="d-none d-lg-flex align-items-center gap-1 lang-switch-box">
                            <a href="{{ route('locale.switch', 'en') }}" class="lang-switch-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" title="English">EN</a>
                            <a href="{{ route('locale.switch', 'si') }}" class="lang-switch-btn {{ app()->getLocale() === 'si' ? 'active' : '' }}" title="සිංහල">සිං</a>
                        </div>

                        @guest
                            <!-- Direct Desktop Guest Buttons -->
                            <div class="d-none d-lg-flex align-items-center gap-2 ms-1">
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold" style="height: 38px; display: inline-flex; align-items: center; font-size: 0.85rem;">
                                    <i class="bi bi-box-arrow-in-right me-1.5"></i> {{ __('messages.sign_in') }}
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary-custom rounded-pill px-3 fw-semibold shadow-sm" style="height: 38px; display: inline-flex; align-items: center; font-size: 0.85rem;">
                                    <i class="bi bi-person-plus me-1.5"></i> {{ __('messages.register') }}
                                </a>
                            </div>
                        @else
                            <!-- Authenticated User Profile Dropdown -->
                            <div class="dropdown ms-1">
                                <button class="btn-user-profile dropdown-toggle shadow-none" type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="{{ Auth::user()->name }}">
                                    <i class="bi bi-person-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end p-2 mt-2" aria-labelledby="userProfileDropdown" style="min-width: 250px;">
                                    <li class="px-3 py-2 border-bottom mb-2 bg-light rounded-3">
                                        <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }}</div>
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
                                </ul>
                            </div>
                        @endguest
                    </div>

                    <!-- Mobile Specific Drawer Menu (Search, Language, Account) -->
                    <div class="d-lg-none mt-3 pt-3 border-top">
                        <!-- Mobile Search -->
                        <form action="{{ route('search') }}" method="GET" class="position-relative mb-3">
                            <input type="text" name="q" class="form-control rounded-pill ps-4 bg-light border" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('q') }}">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted small"></i>
                        </form>

                        <!-- Mobile Language Switcher -->
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light mb-3">
                            <span class="small fw-semibold text-secondary">Language / භාෂාව:</span>
                            <div class="d-flex align-items-center gap-1 bg-white p-1 rounded-pill border">
                                <a href="{{ route('locale.switch', 'en') }}" class="lang-switch-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                                <a href="{{ route('locale.switch', 'si') }}" class="lang-switch-btn {{ app()->getLocale() === 'si' ? 'active' : '' }}">සිං</a>
                            </div>
                        </div>

                        <!-- Mobile Guest Actions -->
                        @guest
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-outline-success rounded-pill fw-semibold py-2">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('messages.sign_in') }}
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-primary-custom rounded-pill fw-semibold py-2">
                                    <i class="bi bi-person-plus me-1"></i> {{ __('messages.register') }}
                                </a>
                            </div>
                        @else
                            <div class="p-3 bg-light rounded-3">
                                <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                                <div class="small text-muted mb-2">{{ Auth::user()->email }}</div>
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ route('user.profile') }}" class="text-decoration-none text-dark small py-1"><i class="bi bi-person-vcard text-primary me-2"></i> {{ __('messages.my_profile') }}</a>
                                    <a href="{{ route('user.saved-trips') }}" class="text-decoration-none text-dark small py-1"><i class="bi bi-bookmark-heart text-success me-2"></i> {{ __('messages.saved_trips') }}</a>
                                    <a href="{{ route('user.my-blogs') }}" class="text-decoration-none text-dark small py-1"><i class="bi bi-journal-text text-warning me-2"></i> {{ __('messages.my_blog_posts') }}</a>
                                    <form action="{{ route('logout') }}" method="POST" class="mt-2 pt-2 border-top">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill">
                                            <i class="bi bi-box-arrow-right me-1"></i> {{ __('messages.sign_out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
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
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-octagon-fill fs-5 me-2 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer (Sleek, Compact & Streamlined) -->
    <footer class="footer-main">
        <div class="container-xl">
            <div class="row g-3">
                <!-- Col 1: Brand & About -->
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <img src="{{ asset('images/logo.png') }}" alt="Elahera Serisara" height="34" class="object-fit-contain" style="background: transparent;">
                        <div>
                            <div class="fw-bold text-white mb-0" style="font-size: 0.95rem;">{{ __('messages.brand_name') }}</div>
                            <div class="text-success fw-bold text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px;">{{ __('messages.tagline') }}</div>
                        </div>
                    </div>
                    <p class="footer-brand-desc mb-1">
                        {{ Str::limit(__('messages.brand_description'), 130) }}
                    </p>
                    <a href="{{ route('about') }}" class="btn btn-outline-success rounded-pill px-2 py-0.5 text-white border-success-subtle" style="font-size: 0.70rem; line-height: 1.2;">
                        <i class="bi bi-info-circle me-1"></i> {{ __('messages.read_more_about') }}
                    </a>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">{{ __('messages.quick_links') }}</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('destinations.index') }}"><i class="bi bi-chevron-right text-success" style="font-size: 0.65rem;"></i> {{ __('messages.destinations') }}</a></li>
                        <li><a href="{{ route('planner.index') }}"><i class="bi bi-chevron-right text-success" style="font-size: 0.65rem;"></i> {{ __('messages.trip_planner') }}</a></li>
                        <li><a href="{{ route('map.index') }}"><i class="bi bi-chevron-right text-success" style="font-size: 0.65rem;"></i> {{ __('messages.interactive_map') }}</a></li>
                        <li><a href="{{ route('transport.index') }}"><i class="bi bi-chevron-right text-success" style="font-size: 0.65rem;"></i> {{ __('messages.public_transport') }}</a></li>
                        <li><a href="{{ route('emergency.index') }}"><i class="bi bi-chevron-right text-danger" style="font-size: 0.65rem;"></i> {{ __('messages.emergency_info') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Community Hub -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">{{ __('messages.community_hub') }}</h5>
                    <ul class="footer-links mb-1">
                        <li><a href="{{ route('blog.index') }}"><i class="bi bi-journal-text text-info" style="font-size: 0.65rem;"></i> {{ __('messages.community_blog') }}</a></li>
                        <li><a href="{{ route('forum.index') }}"><i class="bi bi-chat-dots text-primary" style="font-size: 0.65rem;"></i> {{ __('messages.forum') }}</a></li>
                        <li><a href="{{ route('news.index') }}"><i class="bi bi-megaphone text-warning" style="font-size: 0.65rem;"></i> {{ __('messages.news_events') }}</a></li>
                        <li><a href="{{ route('guide.index') }}"><i class="bi bi-card-checklist text-success" style="font-size: 0.65rem;"></i> {{ __('messages.contribute_guideline') }}</a></li>
                    </ul>
                    <a href="{{ route('blog.create') }}" class="btn btn-success rounded-pill px-2.5 py-0.5 fw-bold text-white shadow-sm" style="font-size: 0.70rem; line-height: 1.2;">
                        <i class="bi bi-pencil-square me-1"></i> {{ __('messages.become_contributor') }}
                    </a>
                </div>

                <!-- Col 4: Contact Us Form (Compact 2-Row Layout) -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">{{ __('messages.contact_us') }}</h5>

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-1 mb-1">
                            <div class="col-6">
                                <input type="text" name="name" class="form-control footer-form-control rounded-2 @error('name') is-invalid @enderror" placeholder="Your Name" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-6">
                                <input type="email" name="email" class="form-control footer-form-control rounded-2 @error('email') is-invalid @enderror" placeholder="Your Email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="mb-1">
                            <input type="text" name="message" class="form-control footer-form-control rounded-2 @error('message') is-invalid @enderror" placeholder="Message (min 10 chars)..." value="{{ old('message') }}" required>
                        </div>
                        @if($errors->any())
                            <div class="text-danger small mb-1" style="font-size: 0.68rem; line-height: 1.2;">{{ $errors->first() }}</div>
                        @endif
                        <button type="submit" class="btn btn-primary-custom w-100 fw-semibold py-0.5" style="font-size: 0.72rem; height: 28px; line-height: 1.2;">
                            <i class="bi bi-send me-1"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="footer-bottom text-center">
                <div>
                    &copy; {{ date('Y') }} <strong>Elahera Serisara (ඇලහැර සැරිසර)</strong>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
