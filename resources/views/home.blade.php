@extends('layouts.app')

@section('title', __('messages.brand_name') . ' - ' . __('messages.tagline'))

@section('content')

    <!-- 1. Full-Width Hero Slider (Admin Controlled via Filament) -->
    <div id="heroCarousel" class="carousel slide carousel-fade shadow-sm" data-bs-ride="carousel" data-bs-interval="6000">
        <!-- Indicators -->
        <div class="carousel-indicators">
            @foreach($sliders as $index => $slider)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @forelse($sliders as $index => $slider)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="height: 520px; min-height: 400px; background-color: #064e3b;">
                    @php
                        $sliderImage = $slider->image ? (file_exists(public_path($slider->image)) ? asset($slider->image) : asset('images/sliders/slider1.jpg')) : asset('images/sliders/slider1.jpg');
                    @endphp
                    <img src="{{ $sliderImage }}" class="d-block w-100 h-100 object-fit-cover opacity-75" alt="{{ $slider->getLocalizedTitle() }}">
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-start text-white pb-5 px-3 px-md-5" style="max-width: 850px; left: 8%; right: auto;">
                        <span class="badge bg-warning text-dark fw-bold text-uppercase px-3 py-2 rounded-pill mb-3 align-self-start shadow-sm" style="font-size: 0.8rem; letter-spacing: 1px;">
                            <i class="bi bi-star-fill text-dark me-1"></i> Featured Experience
                        </span>
                        <h1 class="display-4 fw-extrabold mb-3 text-white text-shadow-lg" style="font-weight: 800; line-height: 1.15;">
                            {{ $slider->getLocalizedTitle() }}
                        </h1>
                        <p class="lead mb-4 text-light text-shadow opacity-90 fs-5 d-none d-md-block" style="max-width: 700px;">
                            {{ $slider->getLocalizedSubtitle() }}
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ $slider->button1_url ?: route('planner.index') }}" class="btn btn-primary-custom btn-lg shadow px-4 py-2 fw-bold">
                                <i class="bi bi-compass me-2"></i> {{ $slider->getLocalizedButton1Text() }}
                            </a>
                            <a href="{{ $slider->button2_url ?: route('destinations.index') }}" class="btn btn-outline-light btn-lg shadow px-4 py-2 fw-bold bg-dark bg-opacity-25 backdrop-blur">
                                <i class="bi bi-geo-alt me-2"></i> {{ $slider->getLocalizedButton2Text() }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="carousel-item active" style="height: 480px; background: linear-gradient(135deg, #064e3b, #047857);">
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-center text-white">
                        <h1 class="display-4 fw-bold">Elahera Sirisara</h1>
                        <p class="lead">Discover ancient irrigation marvels and peaceful waterways.</p>
                        <div>
                            <a href="{{ route('planner.index') }}" class="btn btn-warning btn-lg fw-bold px-4">Start Planning</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon rounded-circle bg-dark bg-opacity-50 p-3" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon rounded-circle bg-dark bg-opacity-50 p-3" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <div class="container-xl py-5">

        <!-- 2. Quick Access Cards Bar (5 Cards with Image Backgrounds) -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div>
                    <h6 class="text-success text-uppercase fw-bold letter-spacing-1 mb-1">Quick Access Hub</h6>
                    <h2 class="fw-bold fs-3 text-dark mb-0">Explore Elahera Corridor</h2>
                </div>
            </div>

            <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5">
                <!-- Card 1: Destinations -->
                <div class="col">
                    <a href="{{ route('destinations.index') }}" class="text-decoration-none">
                        <div class="card card-custom h-100 border-0 shadow-sm position-relative overflow-hidden text-white" style="min-height: 180px; background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.85)), url('{{ asset('images/cards/card_destinations.svg') }}') center/cover;">
                            <div class="card-body d-flex flex-column justify-content-end p-3">
                                <div class="bg-success text-white rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 36px; height: 36px;">
                                    <i class="bi bi-geo-alt-fill fs-6"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-white">Destinations</h5>
                                <p class="small text-light opacity-75 mb-0" style="font-size: 0.75rem;">Heritage & Waterfalls</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card 2: Interactive Map -->
                <div class="col">
                    <a href="{{ route('map.index') }}" class="text-decoration-none">
                        <div class="card card-custom h-100 border-0 shadow-sm position-relative overflow-hidden text-white" style="min-height: 180px; background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.85)), url('{{ asset('images/cards/card_map.svg') }}') center/cover;">
                            <div class="card-body d-flex flex-column justify-content-end p-3">
                                <div class="bg-primary text-white rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 36px; height: 36px;">
                                    <i class="bi bi-map-fill fs-6"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-white">Interactive Map</h5>
                                <p class="small text-light opacity-75 mb-0" style="font-size: 0.75rem;">GPS & Navigation</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card 3: Travel Guide -->
                <div class="col">
                    <a href="{{ route('guide.index') }}" class="text-decoration-none">
                        <div class="card card-custom h-100 border-0 shadow-sm position-relative overflow-hidden text-white" style="min-height: 180px; background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.85)), url('{{ asset('images/cards/card_guide.svg') }}') center/cover;">
                            <div class="card-body d-flex flex-column justify-content-end p-3">
                                <div class="bg-warning text-dark rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 36px; height: 36px;">
                                    <i class="bi bi-journal-check fs-6"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-white">Travel Guide</h5>
                                <p class="small text-light opacity-75 mb-0" style="font-size: 0.75rem;">Tips, Safety & Packing</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card 4: Public Transport -->
                <div class="col">
                    <a href="{{ route('transport.index') }}" class="text-decoration-none">
                        <div class="card card-custom h-100 border-0 shadow-sm position-relative overflow-hidden text-white" style="min-height: 180px; background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.85)), url('{{ asset('images/cards/card_transport.svg') }}') center/cover;">
                            <div class="card-body d-flex flex-column justify-content-end p-3">
                                <div class="bg-danger text-white rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 36px; height: 36px;">
                                    <i class="bi bi-bus-front-fill fs-6"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-white">Public Transport</h5>
                                <p class="small text-light opacity-75 mb-0" style="font-size: 0.75rem;">Bakamuna Bus Schedule</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card 5: Emergency Information -->
                <div class="col">
                    <a href="{{ route('emergency.index') }}" class="text-decoration-none">
                        <div class="card card-custom h-100 border-0 shadow-sm position-relative overflow-hidden text-white" style="min-height: 180px; background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.85)), url('{{ asset('images/cards/card_emergency.svg') }}') center/cover;">
                            <div class="card-body d-flex flex-column justify-content-end p-3">
                                <div class="bg-danger text-white rounded-circle p-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 36px; height: 36px;">
                                    <i class="bi bi-telephone-plus-fill fs-6"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-white">Emergency Info</h5>
                                <p class="small text-light opacity-75 mb-0" style="font-size: 0.75rem;">Hospitals & Police 24/7</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>


        <!-- 3. Main Content Area (2/3 and 1/3 Layout Grid) -->
        <div class="row g-4 mb-5">
            <!-- Left (2/3 Width): 6 Major Destination Cards -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="text-success text-uppercase fw-bold letter-spacing-1 mb-1">Featured Attractions</h6>
                        <h3 class="fw-bold text-dark mb-0">Must-Visit Spots in Elahera</h3>
                    </div>
                    <a href="{{ route('destinations.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold">
                        View All ({{ \App\Models\Destination::where('is_active', true)->count() }}) <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4 row-cols-1 row-cols-md-2">
                    @forelse($featuredDestinations as $dest)
                        <div class="col">
                            <div class="card card-custom h-100">
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    @php
                                        $imageSrc = $dest->featured_image ? (file_exists(public_path($dest->featured_image)) ? asset($dest->featured_image) : asset('images/destinations/anicut.svg')) : asset('images/destinations/anicut.svg');
                                    @endphp
                                    <img src="{{ $imageSrc }}" class="card-img-top w-100 h-100 object-fit-cover" alt="{{ $dest->getLocalizedTitle() }}">
                                    <span class="position-absolute top-0 start-0 m-3 badge-category shadow-sm">
                                        {{ $dest->getLocalizedCategory() }}
                                    </span>
                                    <span class="position-absolute top-0 end-0 m-3 badge-rating shadow-sm">
                                        <i class="bi bi-star-fill text-warning"></i> {{ $dest->averageRating() }}
                                    </span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h5 class="fw-bold mb-2">
                                        <a href="{{ route('destinations.show', $dest->slug) }}" class="text-dark text-decoration-none hover-success">
                                            {{ $dest->getLocalizedTitle() }}
                                        </a>
                                    </h5>
                                    
                                    <!-- Sinhala Fallback Indicator if Sinhala is chosen and missing -->
                                    @if($dest->needsSinhalaNotice())
                                        <div class="small text-warning-emphasis bg-warning bg-opacity-10 border border-warning-subtle rounded px-2 py-1 mb-2" style="font-size: 0.78rem;">
                                            <i class="bi bi-info-circle me-1"></i> {{ __('messages.sinhala_fallback_notice') }}
                                        </div>
                                    @endif

                                    <p class="text-secondary small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $dest->getLocalizedShortDescription() }}
                                    </p>

                                    <!-- Quick Stats -->
                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small mb-3">
                                        <span><i class="bi bi-signpost-2 text-success me-1"></i> {{ $dest->distance_km }} km</span>
                                        <span><i class="bi bi-stopwatch text-primary me-1"></i> {{ $dest->stay_duration_minutes }} mins</span>
                                        <span><i class="bi bi-geo-alt text-danger me-1"></i> {{ Str::limit($dest->nearest_bus_stop ?: 'Bakamuna', 14) }}</span>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('destinations.show', $dest->slug) }}" class="btn btn-outline-success btn-sm w-100 fw-semibold">
                                            {{ __('messages.view_details') }}
                                        </a>
                                        <a href="{{ route('planner.index') }}?add_id={{ $dest->id }}" class="btn btn-success btn-sm px-3" title="Add to Smart Trip Planner">
                                            <i class="bi bi-plus-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No featured destinations available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right (1/3 Width - Sidebar): Smart Day-Trip Planner Widget + Latest Blogs + Latest News -->
            <div class="col-lg-4">
                
                <!-- Smart Day-Trip Planner Widget (Placed Above Latest Blogs, Exact 1/3 Width, Database-Driven) -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-top: 4px solid var(--primary-emerald) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle small">
                            <i class="bi bi-database-check me-1"></i> Data-Verified
                        </span>
                        <span class="small text-muted" style="font-size: 0.72rem;">
                            <i class="bi bi-clock-history text-success"></i> Real Metrics
                        </span>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">
                        <i class="bi bi-calendar2-range text-success me-1"></i> Smart Day-Trip Planner
                    </h5>
                    <p class="small text-secondary mb-3" style="font-size: 0.8rem; line-height: 1.4;">
                        යෙදවුමේ ඇති ගමනාන්ත, රැඳෙන කාලය, ගමන් කාලය සහ පොදු ප්‍රවාහන දත්ත ඇසුරෙන්ම ඔබේ සංචාරය සැලසුම් කරන්න.
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
                                <option value="Car/Van" selected>Car / Van (Comfort)</option>
                                <option value="Motorbike/Tuk">Motorbike / Tuk Tuk (Agile)</option>
                                <option value="Public Bus">Public Bus (Transit)</option>
                            </select>
                        </div>

                        <!-- Field 4: Meal Break Toggle -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="meal_break" value="1" id="mealBreakSwitchSidebar" checked>
                            <label class="form-check-label small fw-semibold text-secondary" for="mealBreakSwitchSidebar">
                                <i class="bi bi-cup-hot text-warning me-1"></i> Meal Break (45 mins)
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary-custom btn-sm w-100 shadow-sm fw-bold py-2">
                            <i class="bi bi-play-circle-fill me-1"></i> {{ __('messages.start_planning') }}
                        </button>
                    </form>
                </div>

                <!-- Sidebar Widget 1: Latest Community Blog Posts -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-journal-richtext text-success me-2"></i> Latest Blog Posts
                        </h5>
                        <a href="{{ route('blog.index') }}" class="small text-success text-decoration-none fw-semibold">View All</a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse($latestBlogs as $blog)
                            <div class="d-flex gap-3 align-items-start">
                                @php
                                    $blogImg = $blog->featured_image ? (file_exists(public_path($blog->featured_image)) ? asset($blog->featured_image) : asset('images/blog/blog1.svg')) : asset('images/blog/blog1.svg');
                                @endphp
                                <img src="{{ $blogImg }}" class="rounded-3 object-fit-cover flex-shrink-0" width="68" height="68" alt="{{ $blog->title }}">
                                <div>
                                    <span class="badge bg-light text-secondary border small mb-1" style="font-size: 0.7rem;">{{ $blog->category?->name_en ?: 'Travel' }}</span>
                                    <h6 class="fw-semibold mb-1" style="font-size: 0.88rem; line-height: 1.3;">
                                        <a href="{{ route('blog.show', $blog->slug) }}" class="text-dark text-decoration-none hover-success">
                                            {{ Str::limit($blog->title, 50) }}
                                        </a>
                                    </h6>
                                    <div class="small text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-person me-1"></i> {{ $blog->user->name }} • {{ $blog->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No approved articles yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <a href="{{ route('blog.create') }}" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-semibold">
                            <i class="bi bi-pen me-1"></i> Write a Community Blog Post
                        </a>
                    </div>
                </div>

                <!-- Sidebar Widget 2: Latest News & Events -->
                <div class="card card-custom border-0 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold text-dark mb-0">
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
                            <p class="text-muted small mb-0">No notices currently.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


        <!-- 5. Community Banner ("Have something to share or ask?") -->
        <div class="card card-custom border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
            <div class="card-body p-4 p-md-5 text-white">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-20 px-3 py-1 rounded-pill mb-3 small">
                            <i class="bi bi-chat-heart-fill text-warning"></i> Traveler Community Hub
                        </div>
                        <h2 class="fw-bold mb-2">Have something to share or ask about Elahera?</h2>
                        <p class="lead mb-0 text-light opacity-90 fs-6">
                            Join our open traveler forum! Ask about bus routes, water currents, local home-stays, or share your recent discoveries with fellow travelers.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                            <a href="{{ route('forum.index') }}" class="btn btn-warning btn-lg fw-bold shadow-sm px-4">
                                <i class="bi bi-chat-dots-fill me-1"></i> Visit Travel Forum
                            </a>
                            <a href="{{ route('forum.create') }}" class="btn btn-outline-light btn-lg fw-semibold shadow-sm px-3">
                                Ask a Question
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
