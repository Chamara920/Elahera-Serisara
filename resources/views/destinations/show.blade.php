@extends('layouts.app')

@section('title', $destination->getLocalizedTitle() . ' - ' . __('messages.brand_name'))

@section('content')

    <!-- 1. Hero Banner & Header -->
    <div class="position-relative text-white" style="height: 440px; background-color: #064e3b; overflow: hidden;">
        <img src="{{ $destination->getFeaturedImageUrl() }}" class="w-100 h-100 object-fit-cover opacity-60 position-absolute top-0 start-0" alt="{{ $destination->getLocalizedTitle() }}">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.3) 0%, rgba(15, 23, 42, 0.9) 100%);"></div>

        <div class="container-xl position-relative h-100 d-flex flex-column justify-content-end pb-5">
            <div class="mb-2">
                <a href="{{ route('destinations.index') }}" class="text-white text-decoration-none small opacity-75 hover-underline">
                    <i class="bi bi-arrow-left me-1"></i> Back to Destinations
                </a>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="badge bg-success px-3 py-1.5 rounded-pill fs-7">
                    <i class="bi bi-tag-fill me-1"></i> {{ $destination->getLocalizedCategory() }}
                </span>
                <span class="badge bg-dark bg-opacity-75 px-3 py-1.5 rounded-pill fs-7">
                    <i class="bi bi-compass me-1"></i> {{ $destination->route_zone }}
                </span>
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fs-7 fw-bold">
                    <i class="bi bi-star-fill text-dark me-1"></i> {{ $destination->averageRating() }} ({{ $destination->reviewsCount() }} reviews)
                </span>
            </div>

            <h1 class="display-4 fw-extrabold text-white mb-2" style="font-weight: 800;">
                {{ $destination->getLocalizedTitle() }}
            </h1>

            @if(!empty($destination->title_si) && app()->getLocale() !== 'si')
                <div class="fs-5 text-light opacity-80 mb-3">{{ $destination->title_si }}</div>
            @endif

            <p class="lead text-light opacity-90 fs-6 mb-0" style="max-width: 800px;">
                {{ $destination->getLocalizedShortDescription() }}
            </p>
        </div>
    </div>


    <div class="container-xl py-5">

        <!-- 2. Quick Stats Bar -->
        <div class="card card-custom border-0 shadow-sm p-3 mb-5" style="margin-top: -30px; position: relative; z-index: 10;">
            <div class="row g-3 text-center align-items-center">
                <div class="col-6 col-md-3 border-end">
                    <div class="text-muted small text-uppercase fw-semibold">Distance from Town</div>
                    <div class="fs-4 fw-bold text-success">{{ $destination->distance_km }} <span class="fs-6 fw-normal text-muted">km</span></div>
                </div>
                <div class="col-6 col-md-3 border-end-md">
                    <div class="text-muted small text-uppercase fw-semibold">Drive Time</div>
                    <div class="fs-4 fw-bold text-primary">{{ $destination->travel_time_minutes }} <span class="fs-6 fw-normal text-muted">mins</span></div>
                </div>
                <div class="col-6 col-md-3 border-end">
                    <div class="text-muted small text-uppercase fw-semibold">Recommended Stay</div>
                    <div class="fs-4 fw-bold text-warning-emphasis">{{ $destination->stay_duration_minutes }} <span class="fs-6 fw-normal text-muted">mins</span></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-muted small text-uppercase fw-semibold">Nearest Bus Stop</div>
                    <div class="fs-6 fw-bold text-dark text-truncate px-2">{{ $destination->nearest_bus_stop ?: 'Bakamuna Stand' }}</div>
                </div>
            </div>
        </div>


        <!-- Sinhala Fallback Notice Banner -->
        @if($destination->needsSinhalaNotice())
            <div class="sinhala-fallback-banner shadow-sm">
                <i class="bi bi-info-circle-fill fs-4 text-warning"></i>
                <div>
                    <strong>දැනුම්දීමයි:</strong> {{ __('messages.sinhala_fallback_notice') }}
                </div>
            </div>
        @endif


        <div class="row g-4">
            
            <!-- Left 2/3 Content: Description, Gallery, Safety & Reviews -->
            <div class="col-lg-8">
                
                <!-- Detailed Description & History -->
                <div class="card card-custom border-0 shadow-sm p-4 p-md-5 mb-4">
                    <h4 class="fw-bold text-dark mb-3">
                        <i class="bi bi-book-half text-success me-2"></i> About & Historical Significance
                    </h4>
                    <div class="text-secondary leading-relaxed fs-6 mb-4" style="line-height: 1.8;">
                        {!! nl2br(e($destination->getLocalizedDescription())) !!}
                    </div>

                    <!-- Gallery Thumbnails (if any) -->
                    @php $galleryUrls = $destination->getGalleryImageUrls(); @endphp
                    @if(!empty($galleryUrls))
                        <h5 class="fw-bold text-dark mb-3">Photo Gallery</h5>
                        <div class="row g-2 mb-4">
                            @foreach($galleryUrls as $gUrl)
                                <div class="col-6 col-md-4">
                                    <a href="{{ $gUrl }}" target="_blank" class="d-block overflow-hidden rounded-3 shadow-sm border">
                                        <img src="{{ $gUrl }}" class="rounded-3 w-100 object-fit-cover hover-scale" style="height: 140px; transition: transform 0.25s ease;" alt="Gallery photo">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Quick Guide Highlights Box -->
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-lightbulb-fill text-warning me-2"></i> Quick Guide & Safety Highlights
                    </h5>
                    <div class="row g-3">
                        <!-- Safety Alerts -->
                        <div class="col-md-4">
                            <div class="p-3 bg-danger bg-opacity-10 border border-danger-subtle rounded-3 h-100">
                                <div class="fw-bold text-danger mb-1">
                                    <i class="bi bi-shield-exclamation me-1"></i> Safety Alerts
                                </div>
                                <p class="small text-danger-emphasis mb-0">
                                    {{ $destination->getLocalizedSafetyAlerts() ?: 'Follow marked trails and stay clear of rapid currents.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Packing List -->
                        <div class="col-md-4">
                            <div class="p-3 bg-warning bg-opacity-10 border border-warning-subtle rounded-3 h-100">
                                <div class="fw-bold text-warning-emphasis mb-1">
                                    <i class="bi bi-backpack-fill me-1"></i> Packing List
                                </div>
                                <p class="small text-secondary mb-0">
                                    {{ $destination->getLocalizedPackingList() ?: 'Water bottle, sun protection, comfortable walking footwear.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Dress Code -->
                        <div class="col-md-4">
                            <div class="p-3 bg-success bg-opacity-10 border border-success-subtle rounded-3 h-100">
                                <div class="fw-bold text-success mb-1">
                                    <i class="bi bi-person-standing me-1"></i> Dress Code
                                </div>
                                <p class="small text-secondary mb-0">
                                    {{ $destination->getLocalizedDressCode() ?: 'Light breathable attire; modest clothing for sacred places.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews and Rating Section -->
                <div class="card card-custom border-0 shadow-sm p-4 p-md-5 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                        <div>
                            <h4 class="fw-bold text-dark mb-0">Traveler Reviews & Ratings</h4>
                            <span class="small text-muted">{{ $reviews->count() }} community reviews</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-3 fw-bold text-success">{{ $destination->averageRating() }}</span>
                            <div class="text-warning small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        @forelse($reviews as $rev)
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-dark">{{ $rev->user->name }}</span>
                                        @if(auth()->check() && auth()->user()->isAdmin())
                                            <form action="{{ route('destinations.reviews.destroy', $rev->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user comment and rating?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.72rem;" title="Delete this review & rating">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="text-warning small">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="bi bi-star{{ $s <= $rev->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="small text-secondary mb-1">{{ $rev->comment }}</p>
                                <div class="small text-muted" style="font-size: 0.75rem;">
                                    {{ $rev->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small mb-0">No reviews yet. Be the first registered member to rate this spot!</p>
                        @endforelse
                    </div>

                    <!-- Review Form for Authenticated Community Members -->
                    @auth
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-dark mb-2">Leave a Review & Rating</h6>
                            <form action="{{ route('destinations.review', $destination->id) }}" method="POST">
                                @csrf
                                <div class="row g-2 mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Rating</label>
                                        <select name="rating" class="form-select form-select-sm" required>
                                            <option value="5" selected>⭐⭐⭐⭐⭐ (5 - Exceptional)</option>
                                            <option value="4">⭐⭐⭐⭐ (4 - Very Good)</option>
                                            <option value="3">⭐⭐⭐ (3 - Average)</option>
                                            <option value="2">⭐⭐ (2 - Below Average)</option>
                                            <option value="1">⭐ (1 - Disappointing)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label small fw-semibold">Your Experience</label>
                                        <input type="text" name="comment" class="form-control form-control-sm" placeholder="Write a short helpful tip for other travelers..." required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary-custom btn-sm">
                                    <i class="bi bi-chat-left-text me-1"></i> Post Review
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-light border small text-center mb-0">
                            <i class="bi bi-lock me-1 text-muted"></i>
                            <a href="{{ route('login') }}" class="fw-bold text-success text-decoration-none">Log in</a> as a Community Member to post your review and rating.
                        </div>
                    @endauth
                </div>

            </div>


            <!-- Right 1/3 Sidebar: Visiting Schedule, Tickets, Map & Transport -->
            <div class="col-lg-4">
                
                <!-- Visiting Hours, Tickets & Seasonality Widget -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-clock-history text-success me-2"></i> Visiting Schedule & Fees
                    </h5>
                    <ul class="list-unstyled small mb-3">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-clock me-1 text-primary"></i> Operating Hours:</span>
                            <span class="fw-bold text-dark">{{ $destination->getFormattedOpeningHours() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-calendar-x me-1 text-danger"></i> Closed Days:</span>
                            <span class="fw-semibold text-danger">{{ !empty($destination->closed_days) ? implode(', ', $destination->closed_days) : 'None (Open Everyday)' }}</span>
                        </li>
                        @if($destination->closure_notes)
                            <li class="py-1.5 text-danger small border-bottom">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $destination->closure_notes }}
                            </li>
                        @endif
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-ticket-perforated me-1 text-success"></i> Entrance Fee:</span>
                            <span class="fw-bold text-success">{{ $destination->getFormattedTicketPrice() }}</span>
                        </li>
                    </ul>

                    <!-- 12-Month Seasonality Assessment -->
                    <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-calendar3 me-1 text-info"></i> Best Seasons to Visit</h6>
                    <div class="d-flex flex-column gap-1.5 small mb-1">
                        @if(!empty($destination->best_months))
                            <div class="d-flex align-items-start gap-1">
                                <span class="badge bg-success text-white me-1 flex-shrink-0">★ Best</span>
                                <span class="text-secondary" style="font-size: 0.78rem;">{{ implode(', ', $destination->best_months) }}</span>
                            </div>
                        @else
                            <div class="text-muted" style="font-size: 0.78rem;">Suitable year-round.</div>
                        @endif
                        @if(!empty($destination->moderate_months))
                            <div class="d-flex align-items-start gap-1">
                                <span class="badge bg-warning text-dark me-1 flex-shrink-0">Moderate</span>
                                <span class="text-secondary" style="font-size: 0.78rem;">{{ implode(', ', $destination->moderate_months) }}</span>
                            </div>
                        @endif
                        @if(!empty($destination->not_recommended_months))
                            <div class="d-flex align-items-start gap-1">
                                <span class="badge bg-danger text-white me-1 flex-shrink-0">⚠️ Not Recommended</span>
                                <span class="text-secondary" style="font-size: 0.78rem;">{{ implode(', ', $destination->not_recommended_months) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Map Location & Directions Widget -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-map-fill text-success me-2"></i> Map & Directions
                    </h5>
                    
                    <div id="destSingleMap" style="height: 220px; border-radius: 10px; border: 1px solid #e2e8f0;" class="mb-3"></div>

                    <div class="small text-muted mb-3">
                        <div><strong>Latitude:</strong> {{ $destination->latitude }}</div>
                        <div><strong>Longitude:</strong> {{ $destination->longitude }}</div>
                    </div>

                    <!-- [ 📍 Google Maps මඟින් යන්න ] Name Specified by User -->
                    <a href="{{ $destination->getGoogleMapsUrl() }}" target="_blank" class="btn btn-success w-100 fw-bold py-2 shadow-sm mb-2">
                        <i class="bi bi-geo-alt-fill me-1"></i> 📍 Google Maps මඟින් යන්න
                    </a>

                    <a href="{{ route('planner.index') }}?add_id={{ $destination->id }}" class="btn btn-outline-success w-100 fw-semibold btn-sm">
                        <i class="bi bi-calendar-plus me-1"></i> Add to Smart Trip Planner
                    </a>
                </div>

                <!-- Transport & Access Info -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-bus-front text-danger me-2"></i> Transport & Access
                    </h5>
                    <ul class="list-unstyled small mb-3">
                        <li class="mb-2">
                            <strong>Nearest Halt:</strong> {{ $destination->nearest_bus_stop ?: 'Elahera Junction' }}
                        </li>
                        <li class="mb-2">
                            <strong>Connecting Bus Routes:</strong> {{ $destination->bus_route_numbers ?: 'Route 422, Route 45/1' }}
                        </li>
                        <li class="mb-0 text-muted">
                            Daily buses operate from 05:30 AM to 06:45 PM along Bakamuna hub corridors.
                        </li>
                    </ul>
                    <a href="{{ route('transport.index') }}?filter_stop={{ urlencode($destination->title_en) }}" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
                        <i class="bi bi-clock-history me-1"></i> Check Bus Timetable Here
                    </a>
                </div>

                <!-- Nearby Recommendations (2-3 Cards) -->
                @if($nearbyDestinations->count() > 0)
                    <div class="card card-custom border-0 shadow-sm p-4">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-signpost-split text-primary me-2"></i> Nearby in this Zone
                        </h5>

                        <div class="d-flex flex-column gap-3">
                            @foreach($nearbyDestinations as $near)
                                <div class="d-flex gap-3 align-items-center">
                                    <img src="{{ $near->getFeaturedImageUrl() }}" class="rounded-3 object-fit-cover flex-shrink-0" width="70" height="70" alt="{{ $near->getLocalizedTitle() }}">
                                    <div>
                                        <h6 class="fw-bold mb-1" style="font-size: 0.88rem;">
                                            <a href="{{ route('destinations.show', $near->slug) }}" class="text-dark text-decoration-none hover-success">
                                                {{ $near->getLocalizedTitle() }}
                                            </a>
                                        </h6>
                                        <div class="small text-muted" style="font-size: 0.75rem;">
                                            {{ $near->distance_km }} km • {{ $near->stay_duration_minutes }}m stay
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $destination->latitude }};
        const lng = {{ $destination->longitude }};
        const title = "{{ addslashes($destination->getLocalizedTitle()) }}";

        const map = L.map('destSingleMap').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup(`<strong>${title}</strong><br><a href="{{ $destination->getGoogleMapsUrl() }}" target="_blank" class="small text-success">Open in Google Maps</a>`)
            .openPopup();
    });
</script>
@endpush
