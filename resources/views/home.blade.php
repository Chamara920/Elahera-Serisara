@extends('layouts.app')

@section('title', __('messages.brand_name') . ' - ' . __('messages.tagline'))

@section('content')

    <!-- 1. Full-Width Hero Slider (Admin Controlled via Filament) -->
    <div id="heroCarousel" class="carousel slide carousel-fade shadow-sm position-relative" data-bs-ride="carousel" data-bs-interval="6500">
        <!-- Indicators -->
        <div class="carousel-indicators" style="bottom: 90px; z-index: 15;">
            @foreach($sliders as $index => $slider)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>

        <!-- Carousel Play / Pause Toggle -->
        @if(count($sliders) > 1)
            <div class="position-absolute end-0 d-none d-sm-block me-3 me-md-4" style="bottom: 84px; z-index: 18;">
                <button type="button" id="carouselPlayPauseBtn" class="btn btn-sm text-white rounded-pill px-3 py-1 shadow-sm border border-white border-opacity-25 d-flex align-items-center gap-1.5" style="background: rgba(15, 23, 42, 0.55); backdrop-filter: blur(8px); font-size: 0.78rem;" title="Pause / Play Slideshow">
                    <i class="bi bi-pause-fill" id="carouselPlayPauseIcon"></i> <span id="carouselPlayPauseText">Pause</span>
                </button>
            </div>
        @endif

        <div class="carousel-inner">
            @forelse($sliders as $index => $slider)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="min-height: 520px; height: 580px; background-color: #064e3b;">
                    <img src="{{ $slider->getImageUrl() }}" class="d-block w-100 h-100 object-fit-cover opacity-85" alt="{{ $slider->getLocalizedTitle() }}">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(6, 78, 59, 0.65) 0%, rgba(6, 78, 59, 0.25) 35%, rgba(15, 23, 42, 0.90) 100%);"></div>

                    <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-start text-white px-3 px-md-5" style="max-width: 860px; left: 6%; right: auto; z-index: 10; padding-bottom: 75px; padding-top: 20px;">
                        
                        <!-- Badges Ribbon -->
                        <div class="d-flex flex-wrap gap-2 mb-2.5">
                            <span class="badge bg-warning text-dark fw-bold text-uppercase px-3 py-1.5 rounded-pill shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.7px;">
                                <i class="bi bi-star-fill text-dark me-1"></i> Elahera Corridor
                            </span>
                            <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25 px-3 py-1.5 rounded-pill small d-none d-sm-inline-flex align-items-center">
                                <i class="bi bi-compass text-warning me-1.5"></i> Heritage & Waterways
                            </span>
                        </div>

                        <h1 class="display-5 fw-extrabold mb-2.5 text-white text-shadow-lg" style="font-weight: 800; line-height: 1.18; letter-spacing: -0.5px;">
                            {{ $slider->getLocalizedTitle() }}
                        </h1>
                        <p class="lead mb-3.5 text-light text-shadow opacity-95 fs-6 d-none d-md-block" style="max-width: 680px; line-height: 1.55;">
                            {{ $slider->getLocalizedSubtitle() }}
                        </p>

                        <!-- Translucent Hero Quick Search Bar -->
                        <form action="{{ route('search') }}" method="GET" class="d-none d-sm-flex align-items-center bg-white bg-opacity-95 rounded-pill p-1.5 shadow-lg mb-4" style="max-width: 520px; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.9); margin-bottom: 1.5rem !important;">
                            <i class="bi bi-search text-success ms-3 me-2 fs-6"></i>
                            <input type="text" name="q" class="form-control form-control-sm border-0 bg-transparent text-dark" placeholder="{{ __('messages.search_placeholder') }}" style="box-shadow: none;">
                            <button type="submit" class="btn btn-primary-custom btn-sm rounded-pill px-4 py-2 fw-bold text-nowrap">
                                {{ __('messages.explore') }}
                            </button>
                        </form>

                        <!-- Hero Action Buttons -->
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ $slider->button1_url ?: route('planner.index') }}" class="btn btn-primary-custom btn-md shadow px-4 py-2.5 fw-bold rounded-pill">
                                <i class="bi bi-calendar2-range me-2"></i> {{ $slider->getLocalizedButton1Text() ?: __('messages.smart_trip_planner') }}
                            </a>
                            <a href="{{ $slider->button2_url ?: route('destinations.index') }}" class="btn btn-outline-light btn-md shadow-sm px-4 py-2.5 fw-bold rounded-pill" style="background: rgba(15, 23, 42, 0.40); backdrop-filter: blur(6px); border: 1.5px solid rgba(255,255,255,0.75);">
                                <i class="bi bi-geo-alt me-2"></i> {{ $slider->getLocalizedButton2Text() ?: __('messages.destinations') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- High-Impact Cinematic Hero Fallback when database has 0 sliders -->
                <div class="carousel-item active" style="min-height: 520px; height: 580px; background: linear-gradient(180deg, rgba(6, 78, 59, 0.72) 0%, rgba(6, 78, 59, 0.45) 45%, rgba(15, 23, 42, 0.94) 100%), url('{{ asset('images/main/Destinations.jpg') }}') center 35% / cover no-repeat;">
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-start text-white px-3 px-md-5" style="max-width: 880px; left: 6%; right: auto; z-index: 10; padding-bottom: 75px; padding-top: 20px;">
                        
                        <!-- Badges Ribbon -->
                        <div class="d-flex flex-wrap gap-2 mb-2.5">
                            <span class="badge bg-warning text-dark fw-bold text-uppercase px-3.5 py-1.5 rounded-pill shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.8px;">
                                <i class="bi bi-star-fill text-dark me-1"></i> Elahera Corridor • Central Province
                            </span>
                            <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-30 px-3 py-1.5 rounded-pill small d-none d-sm-inline-flex align-items-center">
                                <i class="bi bi-water text-warning me-1.5"></i> Historic 40km Yodha Ela & Amban Ganga Valley
                            </span>
                        </div>

                        <h1 class="display-4 fw-extrabold mb-2.5 text-white text-shadow-lg" style="font-weight: 800; line-height: 1.15; letter-spacing: -0.5px;">
                            Discover Ancient Irrigation Wonders & Tranquil Wilderness
                        </h1>
                        <p class="lead mb-3.5 text-light text-shadow opacity-95 d-none d-md-block" style="max-width: 720px; font-size: 1.08rem; line-height: 1.55;">
                            Your definitive digital guide to King Mahasen's historic 40km canal, Kumbuk-shaded river eco-parks, wildlife corridors, and authentic regional public transit.
                        </p>

                        <!-- Translucent Hero Quick Search Bar -->
                        <form action="{{ route('search') }}" method="GET" class="d-flex align-items-center bg-white bg-opacity-95 rounded-pill p-1.5 shadow-lg mb-4" style="max-width: 540px; backdrop-filter: blur(12px); border: 2px solid rgba(255, 255, 255, 0.85); margin-bottom: 1.5rem !important;">
                            <i class="bi bi-search text-success ms-3 me-2 fs-6"></i>
                            <input type="text" name="q" class="form-control form-control-sm border-0 bg-transparent text-dark" placeholder="{{ __('messages.search_placeholder') }}" style="box-shadow: none;">
                            <button type="submit" class="btn btn-primary-custom btn-sm rounded-pill px-4 py-2 fw-bold text-nowrap">
                                {{ __('messages.explore') }}
                            </button>
                        </form>

                        <!-- Hero Action Buttons -->
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <a href="{{ route('planner.index') }}" class="btn btn-primary-custom btn-md shadow px-4 py-2.5 fw-bold rounded-pill">
                                <i class="bi bi-calendar2-range me-2"></i> {{ __('messages.smart_trip_planner') }}
                            </a>
                            <a href="{{ route('map.index') }}" class="btn btn-outline-light btn-md shadow-sm px-4 py-2.5 fw-bold rounded-pill" style="background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(8px); border: 1.5px solid rgba(255,255,255,0.75);">
                                <i class="bi bi-map me-2"></i> Interactive GPS Map
                            </a>
                            <span class="text-white-50 small d-none d-lg-inline-flex align-items-center gap-2 ms-2">
                                <span class="badge bg-success bg-opacity-25 text-success-light border border-success border-opacity-25 rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">Live Portal</span>
                                <span>Zero-Setup Navigation</span>
                            </span>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 12; width: 6%;">
            <span class="carousel-control-prev-icon rounded-circle d-flex align-items-center justify-content-center p-3" style="background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 0.2);" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 12; width: 6%;">
            <span class="carousel-control-next-icon rounded-circle d-flex align-items-center justify-content-center p-3" style="background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(6px); border: 1px solid rgba(255, 255, 255, 0.2);" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <!-- 2. Quick Access Cards Bar (Overlapping Bottom of Hero Slider) -->
    <div class="container-xl position-relative" style="margin-top: -70px; z-index: 20;">
        <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-5">
            <!-- Card 1: Destinations -->
            <div class="col">
                <a href="{{ route('destinations.index') }}" class="text-decoration-none d-block h-100 group">
                    <div class="card card-custom h-100 border-0 shadow-lg position-relative overflow-hidden text-white" style="min-height: 175px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2) !important; background: linear-gradient(180deg, rgba(15,23,42,0.08) 0%, rgba(15,23,42,0.35) 45%, rgba(15,23,42,0.88) 100%), url('{{ asset('images/main/Destinations.jpg') }}') center 65% / cover no-repeat;">
                        <div class="card-body d-flex flex-column justify-content-end p-3 position-relative" style="z-index: 2;">
                            <div class="bg-success text-white rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2 shadow" style="width: 38px; height: 38px;">
                                <i class="bi bi-geo-alt-fill fs-6"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-white text-shadow" style="font-size: 0.98rem; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">{{ __('messages.destinations') }}</h6>
                            <p class="text-light opacity-90 mb-0 small" style="font-size: 0.74rem; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">Heritage & Waterfalls</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2: Interactive Map -->
            <div class="col">
                <a href="{{ route('map.index') }}" class="text-decoration-none d-block h-100 group">
                    <div class="card card-custom h-100 border-0 shadow-lg position-relative overflow-hidden text-white" style="min-height: 175px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2) !important; background: linear-gradient(180deg, rgba(15,23,42,0.08) 0%, rgba(15,23,42,0.32) 45%, rgba(15,23,42,0.88) 100%), url('{{ asset('images/main/interactive_map.jpg') }}') center 75% / cover no-repeat;">
                        <div class="card-body d-flex flex-column justify-content-end p-3 position-relative" style="z-index: 2;">
                            <div class="bg-primary text-white rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2 shadow" style="width: 38px; height: 38px;">
                                <i class="bi bi-map-fill fs-6"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-white text-shadow" style="font-size: 0.98rem; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">{{ __('messages.interactive_map') }}</h6>
                            <p class="text-light opacity-90 mb-0 small" style="font-size: 0.74rem; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">Geographic Waypoints</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 3: Travel Guide -->
            <div class="col">
                <a href="{{ route('guide.index') }}" class="text-decoration-none d-block h-100 group">
                    <div class="card card-custom h-100 border-0 shadow-lg position-relative overflow-hidden text-white" style="min-height: 175px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2) !important; background: linear-gradient(180deg, rgba(15,23,42,0.08) 0%, rgba(15,23,42,0.30) 45%, rgba(15,23,42,0.88) 100%), url('{{ asset('images/main/travel_guide.jpg') }}') left 40% / cover no-repeat;">
                        <div class="card-body d-flex flex-column justify-content-end p-3 position-relative" style="z-index: 2;">
                            <div class="bg-warning text-dark rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2 shadow" style="width: 38px; height: 38px;">
                                <i class="bi bi-journal-check fs-6"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-white text-shadow" style="font-size: 0.98rem; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">{{ __('messages.travel_guide') }}</h6>
                            <p class="text-light opacity-90 mb-0 small" style="font-size: 0.74rem; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">Tips, Safety & Packing</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 4: Public Transport -->
            <div class="col">
                <a href="{{ route('transport.index') }}" class="text-decoration-none d-block h-100 group">
                    <div class="card card-custom h-100 border-0 shadow-lg position-relative overflow-hidden text-white" style="min-height: 175px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2) !important; background: linear-gradient(180deg, rgba(15,23,42,0.08) 0%, rgba(15,23,42,0.30) 45%, rgba(15,23,42,0.88) 100%), url('{{ asset('images/main/public_transport.jpg') }}') right 62% / cover no-repeat;">
                        <div class="card-body d-flex flex-column justify-content-end p-3 position-relative" style="z-index: 2;">
                            <div class="bg-info text-white rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2 shadow" style="width: 38px; height: 38px;">
                                <i class="bi bi-bus-front-fill fs-6"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-white text-shadow" style="font-size: 0.98rem; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">{{ __('messages.public_transport') }}</h6>
                            <p class="text-light opacity-90 mb-0 small" style="font-size: 0.74rem; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">Bakamuna Bus Schedule</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 5: Emergency Information (Distinct Crimson Alert Accent) -->
            <div class="col">
                <a href="{{ route('emergency.index') }}" class="text-decoration-none d-block h-100 group">
                    <div class="card card-custom h-100 border-0 shadow-lg position-relative overflow-hidden text-white" style="min-height: 175px; border-radius: 16px; border: 2px solid #ef4444 !important; background: linear-gradient(180deg, rgba(15,23,42,0.08) 0%, rgba(15,23,42,0.30) 45%, rgba(153,27,27,0.88) 100%), url('{{ asset('images/main/emergency_info.jpg') }}') center 55% / cover no-repeat;">
                        <div class="card-body d-flex flex-column justify-content-end p-3 position-relative" style="z-index: 2;">
                            <div class="bg-danger text-white rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2 shadow" style="width: 38px; height: 38px;">
                                <i class="bi bi-telephone-plus-fill fs-6"></i>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <h6 class="fw-bold mb-0 text-white text-shadow" style="font-size: 0.98rem; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">{{ __('messages.emergency_info') }}</h6>
                                <span class="badge bg-danger text-white px-1.5 py-0.5" style="font-size: 0.62rem;">24/7</span>
                            </div>
                            <p class="text-light opacity-90 mb-0 small" style="font-size: 0.74rem; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">Hospitals & Police</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>


    <!-- 3. Regional Highlights & Live Stats Ribbon -->
    <div class="container-xl pt-4">
        <div class="row g-3 py-2">
            <!-- Stat 1: Verified Destinations -->
            <div class="col-6 col-md-3">
                <div class="card card-custom border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-geo-alt-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark lh-1">{{ ($stats['destinations'] ?? 0) > 0 ? $stats['destinations'] : '5+' }}</div>
                        <div class="small text-muted fw-semibold" style="font-size: 0.8rem;">Scenic Spots & Trails</div>
                    </div>
                </div>
            </div>

            <!-- Stat 2: Categories -->
            <div class="col-6 col-md-3">
                <div class="card card-custom border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-tags-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark lh-1">{{ ($stats['categories'] ?? 0) > 0 ? $stats['categories'] : '5' }}</div>
                        <div class="small text-muted fw-semibold" style="font-size: 0.8rem;">Unique Categories</div>
                    </div>
                </div>
            </div>

            <!-- Stat 3: Bus Routes -->
            <div class="col-6 col-md-3">
                <div class="card card-custom border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-bus-front-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark lh-1">{{ ($stats['bus_routes'] ?? 0) > 0 ? $stats['bus_routes'] : '7' }}</div>
                        <div class="small text-muted fw-semibold" style="font-size: 0.8rem;">Transit Schedules</div>
                    </div>
                </div>
            </div>

            <!-- Stat 4: Actionable CTA for Smart Planner -->
            <div class="col-6 col-md-3">
                <a href="{{ route('planner.index') }}" class="text-decoration-none d-block h-100">
                    <div class="card card-custom border-0 shadow-sm p-3 h-100 d-flex flex-row align-items-center justify-content-between gap-2 bg-success text-white" style="background: linear-gradient(135deg, #059669 0%, #064e3b 100%);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white bg-opacity-20 text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                <i class="bi bi-calendar2-check-fill fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white lh-1" style="font-size: 1rem;">Plan Your Trip</div>
                                <div class="small text-white-50" style="font-size: 0.75rem;">Instant Schedules</div>
                            </div>
                        </div>
                        <i class="bi bi-arrow-right text-white fs-5 pe-2"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>


    <!-- 4. Main Content Area (2/3 Attractions & 1/3 Planner & Widgets) -->
    <div class="container-xl pt-4 pb-5">
        <div class="row g-4 mb-5">
            <!-- Left (2/3 Width): Destination Cards + Interactive Category Filter -->
            <div class="col-lg-8">
                
                <!-- Section Header -->
                <div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-1 rounded-pill small mb-1">
                            <i class="bi bi-compass me-1"></i> Featured Corridor Wonders
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold text-dark mb-0">Must-Visit Spots in Elahera</h3>
                            <span id="filterCountBadge" class="badge bg-light text-secondary border small">
                                @if($featuredDestinations->count() > 0)
                                    Showing {{ $featuredDestinations->count() }}
                                @else
                                    Corridor Highlights
                                @endif
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('destinations.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold mt-2 mt-sm-0">
                        {{ __('messages.explore') }} Directory <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <!-- Interactive Category Filter Pills -->
                @if(isset($categories) && $categories->count() > 0)
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-4 pb-1" id="homeCategoryFilterBar">
                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-semibold shadow-sm home-cat-filter active" data-filter="all">
                            <i class="bi bi-grid-fill me-1"></i> {{ __('messages.all_categories') }}
                        </button>
                        @foreach($categories as $cat)
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold home-cat-filter"
                                data-filter-id="{{ $cat->id }}"
                                data-filter-slug="{{ Str::slug($cat->name_en) }}">
                                {{ app()->getLocale() === 'si' && !empty($cat->name_si) ? $cat->name_si : $cat->name_en }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Destinations Cards Grid -->
                <div class="row g-4 row-cols-1 row-cols-md-2" id="homeDestinationsGrid">
                    @forelse($featuredDestinations as $dest)
                        <div class="col home-dest-card"
                            data-category-id="{{ $dest->category_id }}"
                            data-category-slug="{{ Str::slug($dest->category ?? '') }}">
                            <div class="card card-custom h-100 position-relative">
                                <div class="position-relative overflow-hidden" style="height: 210px;">
                                    <img src="{{ $dest->getFeaturedImageUrl() }}" class="card-img-top w-100 h-100 object-fit-cover" style="transition: transform 0.4s ease;" alt="{{ $dest->getLocalizedTitle() }}">
                                    <span class="position-absolute top-0 start-0 m-3 badge-category shadow-sm">
                                        {{ $dest->getLocalizedCategory() }}
                                    </span>

                                    <!-- Rating badge (Verified: show only when reviews exist) -->
                                    @if($dest->reviewsCount() > 0)
                                        <span class="position-absolute top-0 end-0 m-3 badge-rating shadow-sm">
                                            <i class="bi bi-star-fill text-warning"></i> {{ $dest->averageRating() }} ({{ $dest->reviewsCount() }})
                                        </span>
                                    @else
                                        <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-75 text-white rounded-pill px-2.5 py-1 small shadow-sm" style="font-size: 0.72rem;">
                                            <i class="bi bi-check-circle-fill text-success me-1"></i> Featured Spot
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="fw-bold mb-2">
                                        <a href="{{ route('destinations.show', $dest->slug) }}" class="text-dark text-decoration-none hover-success">
                                            {{ $dest->getLocalizedTitle() }}
                                        </a>
                                    </h5>
                                    
                                    <!-- Fallback Notice if Sinhala is missing -->
                                    @if($dest->needsSinhalaNotice())
                                        <div class="small text-warning-emphasis bg-warning bg-opacity-10 border border-warning-subtle rounded px-2 py-1 mb-2" style="font-size: 0.78rem;">
                                            <i class="bi bi-info-circle me-1"></i> {{ __('messages.sinhala_fallback_notice') }}
                                        </div>
                                    @endif

                                    <p class="text-secondary small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.55;">
                                        {{ $dest->getLocalizedShortDescription() }}
                                    </p>

                                    <!-- Quick Travel Stats -->
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small mb-3">
                                        <span><i class="bi bi-signpost-2 text-success me-1"></i> {{ $dest->distance_km }} km</span>
                                        <span><i class="bi bi-stopwatch text-primary me-1"></i> {{ $dest->stay_duration_minutes }} mins</span>
                                        <span><i class="bi bi-geo-alt text-danger me-1"></i> {{ Str::limit($dest->nearest_bus_stop ?: 'Bakamuna', 14) }}</span>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('destinations.show', $dest->slug) }}" class="btn btn-outline-success btn-sm w-100 fw-semibold rounded-pill">
                                            {{ __('messages.view_details') }}
                                        </a>
                                        <a href="{{ route('planner.index') }}?add_id={{ $dest->id }}" class="btn btn-success btn-sm px-3 rounded-pill" title="Add to Smart Trip Planner">
                                            <i class="bi bi-plus-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Showcase Card 1: Elahera Ancient Anicut (Historical & Irrigation) -->
                        <div class="col home-dest-card"
                            data-category-id="1"
                            data-category-slug="historical-cultural canals-lakes">
                            <div class="card card-custom h-100 position-relative shadow-sm border-0 overflow-hidden" style="border-radius: 16px;">
                                <div class="position-relative overflow-hidden" style="height: 220px;">
                                    <img src="{{ asset('images/main/Destinations.jpg') }}" class="card-img-top w-100 h-100 object-fit-cover" alt="Elahera Ancient Anicut">
                                    <span class="position-absolute top-0 start-0 m-3 badge-category shadow-sm">
                                        {{ app()->getLocale() === 'si' ? 'ඓතිහාසික හා ඇළ මාර්ග' : 'Historical & Waterways' }}
                                    </span>
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-80 text-white rounded-pill px-2.5 py-1 small shadow-sm" style="font-size: 0.72rem;">
                                        <i class="bi bi-star-fill text-warning me-1"></i> Signature Wonder
                                    </span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="fw-bold mb-1">
                                        <a href="{{ route('map.index') }}" class="text-dark text-decoration-none hover-success">
                                            {{ app()->getLocale() === 'si' ? 'ඇලහැර පුරාණ අමුණ සහ ඇළ පද්ධතිය' : 'Elahera Ancient Anicut & Canal System' }}
                                        </a>
                                    </h5>
                                    <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.55;">
                                        {{ app()->getLocale() === 'si' 
                                            ? 'අඹන් ගඟ හරහා ඉදිකළ ඓතිහාසික ජල ඉංජිනේරු නිර්මාණයකි. සිසිල් ගං ඉවුර සහ පැරණි සොරොව් නටබුන් සිරි නරඹන්න.' 
                                            : 'The historic hydraulic marvel built across the Amban Ganga. Features cool riverbanks, ancient stone sluices, and scenic water flow.' }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small mb-3">
                                        <span><i class="bi bi-signpost-2 text-success me-1"></i> 5.0 km</span>
                                        <span><i class="bi bi-stopwatch text-primary me-1"></i> 60 mins</span>
                                        <span><i class="bi bi-geo-alt text-danger me-1"></i> Elahera Town</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('map.index') }}" class="btn btn-outline-success btn-sm w-100 fw-semibold rounded-pill">
                                            <i class="bi bi-map me-1"></i> {{ __('messages.interactive_map') }}
                                        </a>
                                        <a href="{{ route('planner.index') }}" class="btn btn-success btn-sm px-3 rounded-pill" title="Plan Visit">
                                            <i class="bi bi-calendar2-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Showcase Card 2: Bakamuna River Walk & Eco Green Trail (Nature & Wildlife) -->
                        <div class="col home-dest-card"
                            data-category-id="2"
                            data-category-slug="nature-wildlife">
                            <div class="card card-custom h-100 position-relative shadow-sm border-0 overflow-hidden" style="border-radius: 16px;">
                                <div class="position-relative overflow-hidden" style="height: 220px;">
                                    <img src="{{ asset('images/main/travel_guide.jpg') }}" class="card-img-top w-100 h-100 object-fit-cover" alt="Bakamuna River Walk">
                                    <span class="position-absolute top-0 start-0 m-3 badge-category shadow-sm">
                                        {{ app()->getLocale() === 'si' ? 'සොබාදහම සහ වනජීවී' : 'Nature & Wildlife' }}
                                    </span>
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-80 text-white rounded-pill px-2.5 py-1 small shadow-sm" style="font-size: 0.72rem;">
                                        <i class="bi bi-tree-fill text-success me-1"></i> Eco Sanctuary
                                    </span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="fw-bold mb-1">
                                        <a href="{{ route('guide.index') }}" class="text-dark text-decoration-none hover-success">
                                            {{ app()->getLocale() === 'si' ? 'බකමූණ හරිත මංතීරුව සහ ගං ඉවුර' : 'Bakamuna Green Corridor & River Walk' }}
                                        </a>
                                    </h5>
                                    <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.55;">
                                        {{ app()->getLocale() === 'si' 
                                            ? 'මහ කුඹුක් ගස් සෙවණේ ගලායන නිස්කලංක දිය දහරාවන් සහ පක්ෂීන් නැරඹීමට සුදුසු මනරම් පරිසර පද්ධතියකි.' 
                                            : 'Pristine riverside shaded trails under giant Kumbuk trees. Perfect for birdwatching, peaceful nature walks, and family picnics.' }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small mb-3">
                                        <span><i class="bi bi-signpost-2 text-success me-1"></i> 1.2 km</span>
                                        <span><i class="bi bi-stopwatch text-primary me-1"></i> 90 mins</span>
                                        <span><i class="bi bi-geo-alt text-danger me-1"></i> Bakamuna Hub</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('transport.index') }}" class="btn btn-outline-success btn-sm w-100 fw-semibold rounded-pill">
                                            <i class="bi bi-bus-front me-1"></i> {{ __('messages.public_transport') }}
                                        </a>
                                        <a href="{{ route('guide.index') }}" class="btn btn-success btn-sm px-3 rounded-pill" title="Travel Guidelines">
                                            <i class="bi bi-journal-text"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Showcase Card 3: Angamedilla & Sudu Kanda Ridge (Viewpoints & Wildlife) -->
                        <div class="col home-dest-card"
                            data-category-id="3"
                            data-category-slug="viewpoints nature-wildlife">
                            <div class="card card-custom h-100 position-relative shadow-sm border-0 overflow-hidden" style="border-radius: 16px;">
                                <div class="position-relative overflow-hidden" style="height: 220px;">
                                    <img src="{{ asset('images/main/interactive_map.jpg') }}" class="card-img-top w-100 h-100 object-fit-cover" alt="Angamedilla Sudu Kanda Ridge">
                                    <span class="position-absolute top-0 start-0 m-3 badge-category shadow-sm">
                                        {{ app()->getLocale() === 'si' ? 'දසුන් නැරඹුම් ස්ථාන' : 'Viewpoints & Ridges' }}
                                    </span>
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-80 text-white rounded-pill px-2.5 py-1 small shadow-sm" style="font-size: 0.72rem;">
                                        <i class="bi bi-eye-fill text-info me-1"></i> Scenic Vista
                                    </span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="fw-bold mb-1">
                                        <a href="{{ route('map.index') }}" class="text-dark text-decoration-none hover-success">
                                            {{ app()->getLocale() === 'si' ? 'අංගමැඩිල්ල සහ සුදු කන්ද කඳු පන්තිය' : 'Angamedilla & Sudu Kanda Mountain Ridge' }}
                                        </a>
                                    </h5>
                                    <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.55;">
                                        {{ app()->getLocale() === 'si' 
                                            ? 'අඹන් ගං නිම්නය සහ සුදු කන්ද පර්වත වටපිටාව දර්ශනය වන පරිදර්ශනීය ස්ථානයකි. වන අලින්ගේ හැසිරීම් නැරඹිය හැක.' 
                                            : 'Panoramic vistas overlooking the river valley and Sudu Kanda rocky ridges. Wildlife sanctuary with frequent elephant corridor sightings.' }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small mb-3">
                                        <span><i class="bi bi-signpost-2 text-success me-1"></i> 8.5 km</span>
                                        <span><i class="bi bi-stopwatch text-primary me-1"></i> 120 mins</span>
                                        <span><i class="bi bi-geo-alt text-danger me-1"></i> Angamedilla</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('map.index') }}" class="btn btn-outline-success btn-sm w-100 fw-semibold rounded-pill">
                                            <i class="bi bi-compass me-1"></i> {{ __('messages.interactive_map') }}
                                        </a>
                                        <a href="{{ route('planner.index') }}" class="btn btn-success btn-sm px-3 rounded-pill" title="Add to Planner">
                                            <i class="bi bi-plus-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Showcase Card 4: Haththota Amuna Waterway Cascades (Waterfalls & Canals) -->
                        <div class="col home-dest-card"
                            data-category-id="4"
                            data-category-slug="waterfalls canals-lakes">
                            <div class="card card-custom h-100 position-relative shadow-sm border-0 overflow-hidden" style="border-radius: 16px;">
                                <div class="position-relative overflow-hidden" style="height: 220px;">
                                    <img src="{{ asset('images/main/public_transport.jpg') }}" class="card-img-top w-100 h-100 object-fit-cover" alt="Haththota Amuna Cascades">
                                    <span class="position-absolute top-0 start-0 m-3 badge-category shadow-sm">
                                        {{ app()->getLocale() === 'si' ? 'දියඇලි සහ ඇළ මාර්ග' : 'Waterfalls & Rapids' }}
                                    </span>
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-80 text-white rounded-pill px-2.5 py-1 small shadow-sm" style="font-size: 0.72rem;">
                                        <i class="bi bi-droplet-fill text-primary me-1"></i> River Rapids
                                    </span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="fw-bold mb-1">
                                        <a href="{{ route('map.index') }}" class="text-dark text-decoration-none hover-success">
                                            {{ app()->getLocale() === 'si' ? 'හත්තොට අමුණ ස්වභාවික දිය දහරාවන්' : 'Haththota Amuna Natural Cascades' }}
                                        </a>
                                    </h5>
                                    <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.55;">
                                        {{ app()->getLocale() === 'si' 
                                            ? 'ස්වභාවික ගල් පර මතින් ගලා බසින ජල ධාරා සහ ආරක්ෂිතව දියනෑමට සුදුසු සොඳුරු ඉසව්වකි.' 
                                            : 'Cascading river rapids flowing over natural granite boulders. A serene haven for cooling baths and natural photography.' }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small mb-3">
                                        <span><i class="bi bi-signpost-2 text-success me-1"></i> 6.0 km</span>
                                        <span><i class="bi bi-stopwatch text-primary me-1"></i> 45 mins</span>
                                        <span><i class="bi bi-geo-alt text-danger me-1"></i> Haththota</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('map.index') }}" class="btn btn-outline-success btn-sm w-100 fw-semibold rounded-pill">
                                            <i class="bi bi-map me-1"></i> {{ __('messages.interactive_map') }}
                                        </a>
                                        <a href="{{ route('planner.index') }}" class="btn btn-success btn-sm px-3 rounded-pill" title="Plan Route">
                                            <i class="bi bi-calendar2-range"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Empty State Container for Interactive Filter -->
                <div id="homeFilterEmptyState" class="p-5 bg-white rounded-4 border text-center mt-3 d-none">
                    <i class="bi bi-funnel text-muted fs-1 mb-2"></i>
                    <h5 class="fw-bold text-dark mb-1">No destinations in this category</h5>
                    <p class="text-muted small mb-3">We couldn't find any destinations matching the selected category on the homepage.</p>
                    <a href="{{ route('destinations.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-4 fw-semibold">
                        Browse Full Directory in Explore <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Right (1/3 Width - Sidebar): Smart Day-Trip Planner Widget + Interactive Map + Latest Blogs + News -->
            <div class="col-lg-4">
                
                <!-- Smart Day-Trip Planner Widget (Database-Driven, Highlight Card) -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-top: 5px solid var(--primary-emerald) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle small">
                            <i class="bi bi-calendar2-range me-1"></i> Day Planner
                        </span>
                        <span class="small text-muted" style="font-size: 0.74rem;">
                            <i class="bi bi-clock-history text-success"></i> Regional Transit Times
                        </span>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">
                        <i class="bi bi-calendar2-range text-success me-1"></i> {{ __('messages.smart_trip_planner') }}
                    </h5>
                    <p class="small text-secondary mb-3" style="font-size: 0.82rem; line-height: 1.45;">
                        {{ __('messages.planner_widget_desc') }}
                    </p>

                    <form action="{{ route('planner.index') }}" method="GET">
                        <!-- Field 1: Trip Date -->
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary mb-1">
                                <i class="bi bi-calendar3 text-success me-1"></i> {{ __('messages.trip_date') }}
                            </label>
                            <input type="date" name="date" class="form-control form-control-sm bg-white" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Field 2: Start Location -->
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary mb-1">
                                <i class="bi bi-geo-alt text-success me-1"></i> {{ __('messages.start_location') }}
                            </label>
                            <select name="start_location" class="form-select form-select-sm bg-white">
                                <option value="Bakamuna Town" selected>Bakamuna Town (Hub)</option>
                                <option value="Elahera Anicut Junction">Elahera Anicut Junction</option>
                                <option value="Matale Central">Matale Town</option>
                                <option value="Polonnaruwa Station">Polonnaruwa</option>
                                <option value="Dambulla Clock Tower">Dambulla</option>
                            </select>
                        </div>

                        <!-- Field 3: Transport Mode -->
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-secondary mb-1">
                                <i class="bi bi-car-front text-success me-1"></i> {{ __('messages.transport_mode') }}
                            </label>
                            <select name="transport_mode" class="form-select form-select-sm bg-white">
                                <option value="Car/Van" selected>Car / Van (Comfort - 1.0x)</option>
                                <option value="Motorbike/Tuk">Motorbike / Tuk Tuk (Agile - 1.2x)</option>
                                <option value="Public Bus">Public Bus (Transit - 1.5x)</option>
                            </select>
                        </div>

                        <!-- Field 4: Meal Break Toggle -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="meal_break" value="1" id="mealBreakSwitchSidebar" checked>
                            <label class="form-check-label small fw-semibold text-secondary" for="mealBreakSwitchSidebar">
                                <i class="bi bi-cup-hot text-warning me-1"></i> {{ __('messages.meal_break') }}
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary-custom btn-sm w-100 shadow-sm fw-bold py-2 rounded-pill">
                            <i class="bi bi-play-circle-fill me-1"></i> {{ __('messages.start_planning') }}
                        </button>
                    </form>
                </div>

                <!-- Sidebar Widget: Latest Community Blog Posts -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
                            <i class="bi bi-journal-richtext text-success me-2"></i> Latest Blog Posts
                        </h5>
                        <a href="{{ route('blog.index') }}" class="small text-success text-decoration-none fw-semibold">View All</a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse($latestBlogs as $blog)
                            <div class="d-flex gap-3 align-items-start">
                                <img src="{{ $blog->getFeaturedImageUrl() }}" class="rounded-3 object-fit-cover flex-shrink-0" width="68" height="68" alt="{{ $blog->title }}">
                                <div>
                                    <span class="badge bg-light text-secondary border small mb-1" style="font-size: 0.7rem;">{{ $blog->category?->name_en ?: 'Travel' }}</span>
                                    <h6 class="fw-semibold mb-1" style="font-size: 0.88rem; line-height: 1.3;">
                                        <a href="{{ route('blog.show', $blog->slug) }}" class="text-dark text-decoration-none hover-success">
                                            {{ Str::limit($blog->title, 48) }}
                                        </a>
                                    </h6>
                                    <div class="small text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-person me-1"></i> {{ $blog->user->name }} • {{ $blog->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 rounded-3 text-center" style="background: rgba(16, 185, 129, 0.05); border: 1px dashed rgba(16, 185, 129, 0.3);">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center p-2 mb-2" style="width: 42px; height: 42px;">
                                    <i class="bi bi-pen-fill fs-5"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">Be the First Storyteller</h6>
                                <p class="text-muted small mb-2" style="font-size: 0.76rem; line-height: 1.45;">
                                    Share your Elahera travel stories, waterfall tips, or hidden photography spots with fellow explorers.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <a href="{{ route('blog.create') }}" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-semibold">
                            <i class="bi bi-pen me-1"></i> Write a Community Post
                        </a>
                    </div>
                </div>

                <!-- Sidebar Widget: Latest News & Events -->
                <div class="card card-custom border-0 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
                            <i class="bi bi-megaphone text-danger me-2"></i> News & Events
                        </h5>
                        <a href="{{ route('news.index') }}" class="small text-success text-decoration-none fw-semibold">All News</a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse($latestNews as $item)
                            <div class="p-3 rounded-3 bg-light border-start border-3 {{ $item->type === 'event' ? 'border-warning' : 'border-primary' }}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge {{ $item->type === 'event' ? 'bg-warning text-dark' : 'bg-primary' }} small" style="font-size: 0.7rem;">
                                        {{ ucfirst($item->type) }}
                                    </span>
                                    @if($item->event_date)
                                        <span class="small text-muted" style="font-size: 0.75rem;">
                                            <i class="bi bi-calendar-event me-1"></i> {{ $item->event_date->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.88rem;">
                                    <a href="{{ route('news.show', $item->slug) }}" class="text-dark text-decoration-none">
                                        {{ $item->title }}
                                    </a>
                                </h6>
                                <p class="small text-secondary mb-0" style="font-size: 0.8rem;">
                                    {{ Str::limit($item->content, 70) }}
                                </p>
                            </div>
                        @empty
                            <div class="p-3 rounded-3" style="background: rgba(59, 130, 246, 0.05); border: 1px dashed rgba(59, 130, 246, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle small" style="font-size: 0.7rem;">
                                        <i class="bi bi-shield-check me-1"></i> Live Status
                                    </span>
                                    <span class="small text-muted" style="font-size: 0.72rem;">All Trails Clear</span>
                                </div>
                                <h6 class="fw-semibold text-dark mb-1" style="font-size: 0.84rem;">Elahera Green Corridor Active</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.76rem; line-height: 1.45;">
                                    River waters are flowing safely and hiking paths are open. Please travel responsibly and keep waterways clean!
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


        <!-- 5. Community Strip Banner ("Have something to share or ask?") - Slim Ribbon -->
        <div class="card border-0 shadow-sm overflow-hidden rounded-4" style="background: linear-gradient(90deg, #064e3b 0%, #047857 55%, #0284c7 100%);">
            <div class="card-body py-2.5 px-3 px-md-4 text-white">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2.5">
                    <div class="d-flex align-items-center gap-3 text-center text-md-start">
                        <div class="bg-white bg-opacity-20 text-warning rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                            <i class="bi bi-chat-heart-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white fs-6 lh-1 mb-1">
                                Have something to share or ask about Elahera?
                            </div>
                            <div class="text-light opacity-80 small mb-0" style="font-size: 0.80rem;">
                                Join our open traveler forum to ask about bus halts, local stays, waterfalls, or share your trip discoveries.
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-shrink-0 mt-2 mt-md-0">
                        <a href="{{ route('forum.index') }}" class="btn btn-warning btn-sm fw-bold rounded-pill px-3 py-1.5 text-dark shadow-sm text-nowrap">
                            <i class="bi bi-chat-dots-fill me-1"></i> {{ __('messages.forum') }}
                        </a>
                        <a href="{{ route('forum.create') }}" class="btn btn-outline-light btn-sm fw-semibold rounded-pill px-3 py-1.5 text-nowrap">
                            Ask a Question
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Play / Pause Slideshow Button
        const heroCarouselElem = document.getElementById('heroCarousel');
        const playPauseBtn = document.getElementById('carouselPlayPauseBtn');
        const playPauseIcon = document.getElementById('carouselPlayPauseIcon');
        const playPauseText = document.getElementById('carouselPlayPauseText');

        if (heroCarouselElem && playPauseBtn) {
            const carouselInstance = bootstrap.Carousel.getOrCreateInstance(heroCarouselElem);
            let isPaused = false;

            playPauseBtn.addEventListener('click', function () {
                if (isPaused) {
                    carouselInstance.cycle();
                    playPauseIcon.className = 'bi bi-pause-fill me-1';
                    playPauseText.textContent = 'Pause';
                    isPaused = false;
                } else {
                    carouselInstance.pause();
                    playPauseIcon.className = 'bi bi-play-fill me-1';
                    playPauseText.textContent = 'Play';
                    isPaused = true;
                }
            });
        }

        // 2. Interactive Category Filter with Robust ID & Slug Matching
        const filterButtons = document.querySelectorAll('.home-cat-filter');
        const cards = document.querySelectorAll('.home-dest-card');
        const filterCountBadge = document.getElementById('filterCountBadge');
        const emptyState = document.getElementById('homeFilterEmptyState');

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                // Update active button state
                filterButtons.forEach(b => {
                    b.classList.remove('btn-success', 'active', 'shadow-sm');
                    b.classList.add('btn-outline-secondary');
                });
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-success', 'active', 'shadow-sm');

                const filter = this.getAttribute('data-filter');
                const filterId = this.getAttribute('data-filter-id');
                const filterSlug = this.getAttribute('data-filter-slug');

                let visibleCount = 0;

                cards.forEach(card => {
                    const cardCatId = card.getAttribute('data-category-id');
                    const cardCatSlug = card.getAttribute('data-category-slug');

                    let matches = false;
                    if (filter === 'all') {
                        matches = true;
                    } else if (filterId && cardCatId && filterId === cardCatId) {
                        matches = true;
                    } else if (filterSlug && cardCatSlug && (filterSlug === cardCatSlug || cardCatSlug.includes(filterSlug))) {
                        matches = true;
                    }

                    if (matches) {
                        card.style.display = 'block';
                        card.style.opacity = '1';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Update badge and empty state
                if (filterCountBadge) {
                    filterCountBadge.textContent = 'Showing ' + visibleCount;
                }

                if (emptyState) {
                    if (visibleCount === 0) {
                        emptyState.classList.remove('d-none');
                    } else {
                        emptyState.classList.add('d-none');
                    }
                }
            });
        });
    });
</script>
@endpush
