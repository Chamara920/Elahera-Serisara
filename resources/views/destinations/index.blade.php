@extends('layouts.app')

@section('title', __('messages.destinations') . ' - ' . __('messages.brand_name'))

@section('content')

    <!-- Top Featured Slider (Admin Controlled: 3-5 Featured Destinations) -->
    @if($sliderDestinations->count() > 0)
        <div id="destinationsTopCarousel" class="carousel slide carousel-fade mb-5 shadow-sm" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                @foreach($sliderDestinations as $i => $sDest)
                    <button type="button" data-bs-target="#destinationsTopCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach($sliderDestinations as $i => $sDest)
                    @php
                        $sImg = $sDest->featured_image ? (file_exists(public_path($sDest->featured_image)) ? asset($sDest->featured_image) : asset('images/destinations/anicut.svg')) : asset('images/destinations/anicut.svg');
                    @endphp
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}" style="height: 420px; background-color: #064e3b;">
                        <img src="{{ $sImg }}" class="d-block w-100 h-100 object-fit-cover opacity-75" alt="{{ $sDest->getLocalizedTitle() }}">
                        <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-start text-white pb-5 px-3 px-md-5" style="max-width: 800px; left: 8%; right: auto;">
                            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-2 align-self-start shadow-sm" style="font-size: 0.78rem;">
                                Top Rated Destination
                            </span>
                            <h2 class="display-5 fw-extrabold text-white mb-2" style="font-weight: 800;">
                                {{ $sDest->getLocalizedTitle() }}
                            </h2>
                            <p class="lead mb-3 text-light text-shadow opacity-90 fs-6 d-none d-md-block" style="max-width: 650px;">
                                {{ $sDest->getLocalizedShortDescription() }}
                            </p>
                            <div>
                                <a href="{{ route('destinations.show', $sDest->slug) }}" class="btn btn-primary-custom btn-md fw-bold shadow px-4">
                                    <i class="bi bi-eye-fill me-1"></i> Explore This Destination
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#destinationsTopCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon rounded-circle bg-dark bg-opacity-50 p-3" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#destinationsTopCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon rounded-circle bg-dark bg-opacity-50 p-3" aria-hidden="true"></span>
            </button>
        </div>
    @endif


    <div class="container-xl pb-5">

        <!-- Filter & Search Bar -->
        <div class="card card-custom border-0 shadow-sm p-4 mb-4">
            <form action="{{ route('destinations.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <!-- Search Input -->
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 bg-light" placeholder="Search by name or keyword..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="col-md-3">
                        <select name="category" class="form-select bg-light">
                            <option value="all">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Route Zone Filter -->
                    <div class="col-md-3">
                        <select name="zone" class="form-select bg-light">
                            <option value="all">All Route Zones</option>
                            @foreach($zones as $zn)
                                <option value="{{ $zn }}" {{ request('zone') == $zn ? 'selected' : '' }}>
                                    {{ $zn }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter & Reset Buttons -->
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary-custom btn-sm w-100 fw-bold">
                            <i class="bi bi-funnel-fill me-1"></i> Filter
                        </button>
                        <a href="{{ route('destinations.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>


        <!-- Destinations Grid (9 Items Per Page - 3 Columns x 3 Rows on Desktop) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-dark">
                Available Destinations <span class="badge bg-light text-secondary border ms-1">{{ $destinations->total() }}</span>
            </h5>
            <div class="small text-muted">Showing {{ $destinations->firstItem() ?: 0 }} - {{ $destinations->lastItem() ?: 0 }} of {{ $destinations->total() }}</div>
        </div>

        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3 mb-5">
            @forelse($destinations as $dest)
                <div class="col">
                    <div class="card card-custom h-100 d-flex flex-column">
                        <div class="position-relative overflow-hidden" style="height: 210px;">
                            @php
                                $img = $dest->featured_image ? (file_exists(public_path($dest->featured_image)) ? asset($dest->featured_image) : asset('images/destinations/anicut.svg')) : asset('images/destinations/anicut.svg');
                            @endphp
                            <img src="{{ $img }}" class="card-img-top w-100 h-100 object-fit-cover" alt="{{ $dest->getLocalizedTitle() }}">
                            <span class="position-absolute top-0 start-0 m-3 badge-category shadow-sm">
                                {{ $dest->getLocalizedCategory() }}
                            </span>
                            <span class="position-absolute top-0 end-0 m-3 badge-rating shadow-sm">
                                <i class="bi bi-star-fill text-warning"></i> {{ $dest->averageRating() }}
                            </span>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <div class="text-muted small mb-1">
                                <i class="bi bi-compass text-success me-1"></i> {{ $dest->route_zone }}
                            </div>

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

                            <p class="text-secondary small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $dest->getLocalizedShortDescription() }}
                            </p>

                            <!-- Quick Stats -->
                            <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small mb-2">
                                <span><i class="bi bi-signpost-2 text-success me-1"></i> {{ $dest->distance_km }} km</span>
                                <span><i class="bi bi-stopwatch text-primary me-1"></i> {{ $dest->stay_duration_minutes }}m</span>
                                <span><i class="bi bi-chat-text text-secondary me-1"></i> {{ $dest->reviewsCount() }} reviews</span>
                            </div>

                            <!-- Operating Hours & Entrance Fee -->
                            <div class="d-flex align-items-center justify-content-between mb-3 text-muted small" style="font-size: 0.74rem;">
                                <span class="text-truncate me-1"><i class="bi bi-clock text-primary me-1"></i> {{ $dest->getFormattedOpeningHours() }}</span>
                                <span class="text-success fw-bold text-nowrap"><i class="bi bi-ticket-perforated me-1"></i> {{ $dest->getFormattedTicketPrice() }}</span>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('destinations.show', $dest->slug) }}" class="btn btn-outline-success btn-sm w-100 fw-semibold">
                                    {{ __('messages.view_details') }}
                                </a>
                                <a href="{{ route('planner.index') }}?add_id={{ $dest->id }}" class="btn btn-success btn-sm px-3" title="Add to Smart Trip Planner">
                                    <i class="bi bi-calendar2-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                    <h5 class="fw-bold text-dark">No destinations found matching your filters</h5>
                    <p class="text-muted small">Try adjusting your keyword or reset filters to browse all attractions.</p>
                    <a href="{{ route('destinations.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-4">Reset Filters</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination (9 items per page) -->
        <div class="d-flex justify-content-center">
            {{ $destinations->links('pagination::bootstrap-5') }}
        </div>

    </div>

@endsection
