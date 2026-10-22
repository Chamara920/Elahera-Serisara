@extends('layouts.app')

@section('title', 'Smart Day-Trip Planner - ' . __('messages.brand_name'))

@section('content')

    <!-- Sticky Color Time-Meter Bar -->
    <div id="stickyTimeMeterBar" class="sticky-time-meter">
        <div class="container-xl">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-dark rounded-pill px-2.5 py-1 text-white">
                        <i class="bi bi-clock me-1"></i> <span id="timeWindowDisplay">08:00 AM - 06:00 PM (10 hrs)</span>
                    </span>
                    <span class="text-secondary small fw-semibold d-none d-md-inline" id="totalUsedDisplay">Used: 0 mins</span>
                </div>
                <!-- Mini Legend -->
                <div class="d-flex flex-wrap align-items-center gap-3 small fw-semibold" style="font-size: 0.78rem;">
                    <span class="d-flex align-items-center gap-1"><span class="badge bg-primary rounded-circle p-1"> </span> Travel: <span id="legendTravel">0m</span></span>
                    <span class="d-flex align-items-center gap-1"><span class="badge bg-success rounded-circle p-1"> </span> Stay: <span id="legendStay">0m</span></span>
                    <span class="d-flex align-items-center gap-1"><span class="badge bg-warning rounded-circle p-1"> </span> Meal: <span id="legendMeal">45m</span></span>
                    <span class="d-flex align-items-center gap-1"><span class="badge rounded-circle p-1" style="background:#8b5cf6;"> </span> Return: <span id="legendReturn">0m</span></span>
                    <span class="d-flex align-items-center gap-1 text-muted"><span class="badge bg-secondary rounded-circle p-1"> </span> Free: <span id="legendFree">600m</span></span>
                </div>
            </div>

            <!-- Visual Progress Meter -->
            <div class="progress" style="height: 12px; border-radius: 6px; background-color: #e2e8f0; overflow: hidden;" id="timeProgressBar">
                <div id="meterTravel" class="progress-bar bg-primary" role="progressbar" style="width: 0%;" title="Travel Time"></div>
                <div id="meterStay" class="progress-bar bg-success" role="progressbar" style="width: 0%;" title="Stay Duration"></div>
                <div id="meterMeal" class="progress-bar bg-warning" role="progressbar" style="width: 7.5%;" title="Meal Break"></div>
                <div id="meterReturn" class="progress-bar" role="progressbar" style="width: 0%; background-color: #8b5cf6;" title="Return Journey"></div>
                <div id="meterFree" class="progress-bar bg-secondary bg-opacity-50" role="progressbar" style="width: 92.5%;" title="Free / Available Time"></div>
            </div>
        </div>
    </div>


    <div class="container-xl py-4">

        <!-- Trip Configuration Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2.5 py-1 rounded-pill small mb-1">
                    <i class="bi bi-database-check me-1"></i> {{ __('messages.database_driven_transit') }}
                </span>
                <h4 class="fw-bold text-dark mb-0">{{ __('messages.smart_trip_planner') }}</h4>
                <p class="text-secondary small mb-0">
                    {{ __('messages.planner_page_subtitle') }}
                </p>
            </div>
            <div class="text-muted small mt-2 mt-md-0">
                <i class="bi bi-shield-check text-success me-1"></i> {{ __('messages.verified_regional_corridor') }}
            </div>
        </div>

        <!-- Trip Configuration Bar -->
        <div class="card card-custom border-0 shadow-sm p-4 mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-calendar-event text-success me-1"></i> {{ __('messages.trip_date_label') }}
                    </label>
                    <input type="date" id="cfgDate" class="form-control form-control-sm" value="{{ $tripDate }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-geo-alt-fill text-success me-1"></i> {{ __('messages.start_point_label') }}
                    </label>
                    <select id="cfgStartLocation" class="form-select form-select-sm">
                        <option value="Bakamuna Town" {{ $startLocation == 'Bakamuna Town' ? 'selected' : '' }}>Bakamuna Town (Hub)</option>
                        <option value="Elahera Anicut" {{ $startLocation == 'Elahera Anicut' ? 'selected' : '' }}>Elahera Anicut</option>
                        <option value="Matale" {{ $startLocation == 'Matale' ? 'selected' : '' }}>Matale</option>
                        <option value="Polonnaruwa" {{ $startLocation == 'Polonnaruwa' ? 'selected' : '' }}>Polonnaruwa</option>
                        <option value="Dambulla" {{ $startLocation == 'Dambulla' ? 'selected' : '' }}>Dambulla</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-play-circle text-primary me-1"></i> {{ __('messages.start_time_label') }}
                    </label>
                    <input type="time" id="cfgStartTime" class="form-control form-control-sm" value="{{ $startTime }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-stop-circle text-danger me-1"></i> {{ __('messages.end_time_label') }}
                    </label>
                    <input type="time" id="cfgEndTime" class="form-control form-control-sm" value="{{ $endTime }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-car-front text-info me-1"></i> {{ __('messages.transport_label') }}
                    </label>
                    <select id="cfgTransportMode" class="form-select form-select-sm">
                        <option value="Car/Van" {{ $transportMode == 'Car/Van' ? 'selected' : '' }}>{{ __('messages.car_van') }}</option>
                        <option value="Motorbike/Tuk" {{ $transportMode == 'Motorbike/Tuk' ? 'selected' : '' }}>{{ __('messages.motorbike_tuk') }}</option>
                        <option value="Public Bus" {{ $transportMode == 'Public Bus' ? 'selected' : '' }}>{{ __('messages.public_bus') }}</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" id="cfgMealBreak" {{ $mealBreak ? 'checked' : '' }}>
                        <label class="form-check-label small fw-bold text-secondary" for="cfgMealBreak">
                            {{ __('messages.meal_break') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>


        <!-- Main Grid: 2/3 Destinations Grid + 1/3 Live Timeline & Route -->
        <div class="row g-4">
            
            <!-- 2/3 Width: Destination Selection Grid & Category Filters -->
            <div class="col-lg-8">
                <!-- Filter Pills -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        {{ __('messages.select_destinations_to_add') }}
                    </h5>
                    <div class="d-flex flex-wrap gap-1" id="categoryFilterContainer">
                        <button type="button" class="btn btn-sm btn-outline-success active rounded-pill px-3 py-1 filter-cat-btn" data-category="all">
                            {{ __('messages.all_categories') }}
                        </button>
                        @foreach($categories as $cat)
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 filter-cat-btn" data-category="{{ $cat }}">
                                {{ $cat }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Destinations Cards Grid -->
                <div class="row g-3 row-cols-1 row-cols-md-2" id="plannerDestinationsGrid">
                    @foreach($destinations as $dest)
                        <div class="col destination-planner-card" data-category="{{ $dest->category }}" data-id="{{ $dest->id }}">
                            <div class="card card-custom h-100 position-relative">
                                <div class="position-relative overflow-hidden" style="height: 160px;">
                                    <img src="{{ $dest->getFeaturedImageUrl() }}" class="card-img-top w-100 h-100 object-fit-cover" alt="{{ $dest->getLocalizedTitle() }}">
                                    <span class="position-absolute top-0 start-0 m-2 badge-category shadow-sm" style="font-size: 0.72rem;">
                                        {{ $dest->getLocalizedCategory() }}
                                    </span>
                                    <span class="position-absolute top-0 end-0 m-2 badge-rating shadow-sm" style="font-size: 0.72rem;">
                                        <i class="bi bi-star-fill text-warning"></i> {{ $dest->averageRating() }}
                                    </span>
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <h6 class="fw-bold mb-1 text-dark">
                                        {{ $dest->getLocalizedTitle() }}
                                    </h6>

                                    @if($dest->needsSinhalaNotice())
                                        <div class="text-warning-emphasis bg-warning bg-opacity-10 border border-warning-subtle rounded px-2 py-0.5 mb-2" style="font-size: 0.72rem;">
                                            <i class="bi bi-info-circle me-1"></i> {{ __('messages.sinhala_fallback_notice') }}
                                        </div>
                                    @endif

                                    <!-- Quick Guide Highlights Box -->
                                    <div class="bg-light p-2 rounded-2 mb-2 small text-secondary" style="font-size: 0.76rem;">
                                        @if($dest->safety_alerts_en)
                                            <div class="text-truncate text-danger mb-1">
                                                <strong>⚠️ Alert:</strong> {{ $dest->getLocalizedSafetyAlerts() }}
                                            </div>
                                        @endif
                                        <div class="d-flex justify-content-between text-muted mb-1">
                                            <span><i class="bi bi-signpost-2"></i> {{ $dest->distance_km }} km</span>
                                            <span><i class="bi bi-stopwatch"></i> Stay: {{ $dest->stay_duration_minutes }}m</span>
                                            <span><i class="bi bi-car-front"></i> Drive: {{ $dest->travel_time_minutes }}m</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center text-muted border-top pt-1 mt-1" style="font-size: 0.72rem;">
                                            <span class="text-truncate me-1" title="{{ $dest->getFormattedOpeningHours() }}">
                                                <i class="bi bi-clock text-primary me-1"></i> {{ $dest->getFormattedOpeningHours() }}
                                            </span>
                                            <span class="text-success fw-bold text-nowrap">
                                                <i class="bi bi-ticket-perforated me-1"></i> {{ $dest->getFormattedTicketPrice() }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-auto d-flex gap-2">
                                        <!-- Read Full Guide Modal Trigger -->
                                        <button type="button" class="btn btn-outline-secondary btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#guideModal{{ $dest->id }}">
                                            <i class="bi bi-book me-1"></i> {{ __('messages.read_full_guide') }}
                                        </button>
                                        <!-- Add to Itinerary Button -->
                                        <button type="button" class="btn btn-primary-custom btn-sm px-3 add-to-trip-btn" 
                                            data-id="{{ $dest->id }}"
                                            data-title="{{ $dest->getLocalizedTitle() }}"
                                            data-stay="{{ $dest->stay_duration_minutes }}"
                                            data-travel="{{ $dest->travel_time_minutes }}"
                                            data-lat="{{ $dest->latitude }}"
                                            data-lng="{{ $dest->longitude }}"
                                            data-open="{{ $dest->opening_time ?: '06:00' }}"
                                            data-close="{{ $dest->closing_time ?: '18:00' }}"
                                            data-always-open="{{ $dest->is_always_open ? '1' : '0' }}"
                                            data-closed-days="{{ json_encode($dest->closed_days ?? []) }}"
                                            data-closure-notes="{{ $dest->closure_notes ?? '' }}"
                                            data-best-months="{{ json_encode($dest->best_months ?? []) }}"
                                            data-moderate-months="{{ json_encode($dest->moderate_months ?? []) }}"
                                            data-not-rec-months="{{ json_encode($dest->not_recommended_months ?? []) }}"
                                            data-price-min="{{ $dest->ticket_price_min ?? 0 }}"
                                            data-price-max="{{ $dest->ticket_price_max ?? 0 }}"
                                            data-price-range="{{ $dest->getFormattedTicketPrice() }}">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Read Full Guide Large Popup Modal -->
                        <div class="modal fade" id="guideModal{{ $dest->id }}" tabindex="-1" aria-labelledby="guideModalLabel{{ $dest->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title fw-bold" id="guideModalLabel{{ $dest->id }}">
                                            <i class="bi bi-geo-alt-fill me-2"></i> {{ $dest->getLocalizedTitle() }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="position-relative mb-3 rounded-3 overflow-hidden" style="max-height: 280px;">
                                            <img src="{{ $dest->getFeaturedImageUrl() }}" class="w-100 h-100 object-fit-cover" alt="{{ $dest->getLocalizedTitle() }}">
                                        </div>

                                        @if($dest->needsSinhalaNotice())
                                            <div class="alert alert-warning d-flex align-items-center mb-3">
                                                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                                                <div>{{ __('messages.sinhala_fallback_notice') }}</div>
                                            </div>
                                        @endif

                                        <h6 class="fw-bold text-success">About & History</h6>
                                        <p class="text-secondary small leading-relaxed mb-4">
                                            {{ $dest->getLocalizedDescription() }}
                                        </p>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded-3 h-100">
                                                    <h6 class="fw-bold text-danger mb-1"><i class="bi bi-shield-exclamation me-1"></i> Safety Alerts</h6>
                                                    <p class="small text-muted mb-0">{{ $dest->getLocalizedSafetyAlerts() ?: 'General caution advised.' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded-3 h-100">
                                                    <h6 class="fw-bold text-warning-emphasis mb-1"><i class="bi bi-backpack me-1"></i> Packing List</h6>
                                                    <p class="small text-muted mb-0">{{ $dest->getLocalizedPackingList() ?: 'Water, sunscreen, comfortable shoes.' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded-3 h-100">
                                                    <h6 class="fw-bold text-info-emphasis mb-1"><i class="bi bi-bus-front me-1"></i> Public Transport</h6>
                                                    <p class="small text-muted mb-0">{{ $dest->nearest_bus_stop ?: 'Bakamuna Stand' }} ({{ $dest->bus_route_numbers ?: 'Route 422' }})</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Visiting Hours, Tickets & Seasonality Box -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded-3 h-100">
                                                    <h6 class="fw-bold text-success mb-2"><i class="bi bi-clock-history me-1"></i> Visiting Hours & Tickets</h6>
                                                    <div class="small mb-1"><strong>Hours:</strong> {{ $dest->getFormattedOpeningHours() }}</div>
                                                    <div class="small mb-1 text-danger"><strong>Closed:</strong> {{ !empty($dest->closed_days) ? implode(', ', $dest->closed_days) : 'Open Daily' }}</div>
                                                    @if($dest->closure_notes)
                                                        <div class="small text-muted mb-1"><em>{{ $dest->closure_notes }}</em></div>
                                                    @endif
                                                    <div class="small text-success fw-bold"><strong>Entrance Fee:</strong> {{ $dest->getFormattedTicketPrice() }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded-3 h-100">
                                                    <h6 class="fw-bold text-info-emphasis mb-2"><i class="bi bi-calendar3 me-1"></i> 12-Month Seasonality</h6>
                                                    <div class="small mb-1"><span class="badge bg-success me-1">Best</span> {{ !empty($dest->best_months) ? implode(', ', $dest->best_months) : 'All Year' }}</div>
                                                    @if(!empty($dest->moderate_months))
                                                        <div class="small mb-1"><span class="badge bg-warning text-dark me-1">Moderate</span> {{ implode(', ', $dest->moderate_months) }}</div>
                                                    @endif
                                                    @if(!empty($dest->not_recommended_months))
                                                        <div class="small text-danger"><span class="badge bg-danger me-1">Not Recommended</span> {{ implode(', ', $dest->not_recommended_months) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                            <a href="{{ $dest->getGoogleMapsUrl() }}" target="_blank" class="btn btn-outline-success btn-sm">
                                                <i class="bi bi-google me-1"></i> Open in Google Maps
                                            </a>
                                            <button type="button" class="btn btn-success btn-sm px-4 add-to-trip-btn"
                                                data-bs-dismiss="modal"
                                                data-id="{{ $dest->id }}"
                                                data-title="{{ $dest->getLocalizedTitle() }}"
                                                data-stay="{{ $dest->stay_duration_minutes }}"
                                                data-travel="{{ $dest->travel_time_minutes }}"
                                                data-lat="{{ $dest->latitude }}"
                                                data-lng="{{ $dest->longitude }}"
                                                data-open="{{ $dest->opening_time ?: '06:00' }}"
                                                data-close="{{ $dest->closing_time ?: '18:00' }}"
                                                data-always-open="{{ $dest->is_always_open ? '1' : '0' }}"
                                                data-closed-days="{{ json_encode($dest->closed_days ?? []) }}"
                                                data-closure-notes="{{ $dest->closure_notes ?? '' }}"
                                                data-best-months="{{ json_encode($dest->best_months ?? []) }}"
                                                data-moderate-months="{{ json_encode($dest->moderate_months ?? []) }}"
                                                data-not-rec-months="{{ json_encode($dest->not_recommended_months ?? []) }}"
                                                data-price-min="{{ $dest->ticket_price_min ?? 0 }}"
                                                data-price-max="{{ $dest->ticket_price_max ?? 0 }}"
                                                data-price-range="{{ $dest->getFormattedTicketPrice() }}">
                                                <i class="bi bi-plus-lg me-1"></i> Add to Trip
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <!-- 1/3 Width: Live Timeline Sidebar, Interactive Map & Save Trip -->
            <div class="col-lg-4">
                <div class="card card-custom border-0 shadow-sm sticky-top" style="top: 140px; z-index: 1010;">
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-card-checklist text-success me-2"></i> Live Day-Trip Timeline
                        </h6>
                        <span class="badge bg-success rounded-pill" id="stopsCounter">0 Stops</span>
                    </div>

                    <div class="card-body p-3">
                        <!-- Empty Notice -->
                        <div id="timelineEmptyNotice" class="text-center py-4 text-muted">
                            <i class="bi bi-compass fs-1 text-secondary opacity-50 d-block mb-2"></i>
                            <p class="small mb-1">Your timeline is empty.</p>
                            <span class="small text-muted">Click <strong>[+ Add]</strong> on any destination to build your day trip!</span>
                        </div>

                        <!-- Timeline List Container -->
                        <div id="timelineStopsList" class="d-flex flex-column gap-2 mb-3" style="max-height: 380px; overflow-y: auto;">
                            <!-- Dynamically generated with JS -->
                        </div>

                        <!-- Interactive Map Preview for Planned Route -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-bold text-secondary">Route Preview</span>
                                <span class="small text-muted" id="routeDistanceDisplay">0 km total</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-1.5 bg-light rounded-2 border">
                                <span class="small fw-semibold text-secondary" style="font-size: 0.76rem;"><i class="bi bi-ticket-perforated text-success me-1"></i> Total Est. Tickets:</span>
                                <span class="small fw-bold text-success" id="totalTicketFeesDisplay">Free</span>
                            </div>
                            <div id="plannerLeafletMap" style="height: 180px; border-radius: 10px; border: 1px solid #e2e8f0;"></div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <!-- Open in Google Maps -->
                            <a href="#" id="btnOpenGoogleMaps" target="_blank" class="btn btn-outline-success btn-sm fw-semibold disabled">
                                <i class="bi bi-map-fill me-1"></i> Open Route in Google Maps
                            </a>

                            <!-- Save Trip to My Account (Registered Community Members) -->
                            <button type="button" id="btnSaveTrip" class="btn btn-primary-custom btn-sm fw-bold">
                                <i class="bi bi-bookmark-plus-fill me-1"></i> Save Trip to My Account
                            </button>

                            <!-- Print / PDF Export -->
                            <button type="button" id="btnPrintTrip" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-printer me-1"></i> Print / Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // State variables
        let tripStops = [];
        let leafletMap = null;
        let routeMarkers = [];
        let routePolyline = null;

        // Initialize Mini Leaflet Map
        function initLeafletMap() {
            leafletMap = L.map('plannerLeafletMap').setView([7.9500, 80.8200], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(leafletMap);
        }
        initLeafletMap();

        // Check if query string requested an immediate add
        const urlParams = new URLSearchParams(window.location.search);
        const autoAddId = urlParams.get('add_id');
        if (autoAddId) {
            const addBtn = document.querySelector(`.add-to-trip-btn[data-id="${autoAddId}"]`);
            if (addBtn) {
                addBtn.click();
            }
        }

        // Category Filter Pills
        const catButtons = document.querySelectorAll('.filter-cat-btn');
        const destCards = document.querySelectorAll('.destination-planner-card');

        catButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                catButtons.forEach(b => b.classList.remove('active', 'btn-outline-success'));
                catButtons.forEach(b => b.classList.add('btn-outline-secondary'));
                this.classList.remove('btn-outline-secondary');
                this.classList.add('active', 'btn-outline-success');

                const chosenCat = this.getAttribute('data-category');
                destCards.forEach(card => {
                    if (chosenCat === 'all' || card.getAttribute('data-category') === chosenCat) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Add to Trip Button Handler
        document.querySelectorAll('.add-to-trip-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = parseInt(this.getAttribute('data-id'));
                const title = this.getAttribute('data-title');
                const stay = parseInt(this.getAttribute('data-stay')) || 60;
                const travel = parseInt(this.getAttribute('data-travel')) || 20;
                const lat = parseFloat(this.getAttribute('data-lat'));
                const lng = parseFloat(this.getAttribute('data-lng'));

                const openTime = this.getAttribute('data-open') || '06:00';
                const closeTime = this.getAttribute('data-close') || '18:00';
                const alwaysOpen = this.getAttribute('data-always-open') === '1';
                let closedDays = [];
                try { closedDays = JSON.parse(this.getAttribute('data-closed-days') || '[]'); } catch(e){}
                const closureNotes = this.getAttribute('data-closure-notes') || '';
                let bestMonths = [];
                try { bestMonths = JSON.parse(this.getAttribute('data-best-months') || '[]'); } catch(e){}
                let moderateMonths = [];
                try { moderateMonths = JSON.parse(this.getAttribute('data-moderate-months') || '[]'); } catch(e){}
                let notRecMonths = [];
                try { notRecMonths = JSON.parse(this.getAttribute('data-not-rec-months') || '[]'); } catch(e){}
                const priceMin = parseFloat(this.getAttribute('data-price-min')) || 0;
                const priceMax = parseFloat(this.getAttribute('data-price-max')) || 0;
                const priceRange = this.getAttribute('data-price-range') || 'Free Entry';

                // Prevent duplicates
                if (tripStops.some(s => s.id === id)) {
                    alert('This destination is already added to your day trip!');
                    return;
                }

                tripStops.push({
                    id: id,
                    title: title,
                    stay: stay,
                    travel: travel,
                    lat: lat,
                    lng: lng,
                    open: openTime,
                    close: closeTime,
                    alwaysOpen: alwaysOpen,
                    closedDays: closedDays,
                    closureNotes: closureNotes,
                    bestMonths: bestMonths,
                    moderateMonths: moderateMonths,
                    notRecMonths: notRecMonths,
                    priceMin: priceMin,
                    priceMax: priceMax,
                    priceRange: priceRange
                });

                renderTimeline();
            });
        });

        // Configuration Change listeners
        ['cfgDate', 'cfgStartTime', 'cfgEndTime', 'cfgTransportMode', 'cfgMealBreak'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', renderTimeline);
        });

        // Render Timeline & Recalculate Time Meter
        function renderTimeline() {
            const listEl = document.getElementById('timelineStopsList');
            const emptyEl = document.getElementById('timelineEmptyNotice');
            const counterEl = document.getElementById('stopsCounter');
            const googleMapsBtn = document.getElementById('btnOpenGoogleMaps');

            counterEl.textContent = `${tripStops.length} Stop${tripStops.length === 1 ? '' : 's'}`;

            if (tripStops.length === 0) {
                emptyEl.style.display = 'block';
                listEl.innerHTML = '';
                googleMapsBtn.classList.add('disabled');
                updateMeter(0, 0, 0, 0, 600);
                clearMapRoute();
                const ticketDisplayEl = document.getElementById('totalTicketFeesDisplay');
                if (ticketDisplayEl) ticketDisplayEl.textContent = 'Free';
                return;
            }

            emptyEl.style.display = 'none';
            googleMapsBtn.classList.remove('disabled');

            // Parse timing constraints
            const startTimeStr = document.getElementById('cfgStartTime').value || '08:00';
            const endTimeStr = document.getElementById('cfgEndTime').value || '18:00';
            const hasMealBreak = document.getElementById('cfgMealBreak').checked;
            const transportMode = document.getElementById('cfgTransportMode').value;
            const tripDateInput = document.getElementById('cfgDate').value;

            // Date analysis for Closed Days & Seasonality
            let tripDayName = '';
            let tripMonthName = '';
            if (tripDateInput) {
                const dateParts = tripDateInput.split('-').map(Number);
                const dt = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                tripDayName = dayNames[dt.getDay()];
                tripMonthName = monthNames[dt.getMonth()];
            }

            // Transport multiplier
            let travelMultiplier = 1.0;
            if (transportMode === 'Motorbike/Tuk') travelMultiplier = 1.2;
            if (transportMode === 'Public Bus') travelMultiplier = 1.5;

            // Calculate total time budget in minutes
            const startParts = startTimeStr.split(':').map(Number);
            const endParts = endTimeStr.split(':').map(Number);
            const totalBudgetMinutes = (endParts[0] * 60 + endParts[1]) - (startParts[0] * 60 + startParts[1]);

            let currentMinutes = startParts[0] * 60 + startParts[1];
            let totalTravel = 0;
            let totalStay = 0;
            let totalTicketMin = 0;
            let totalTicketMax = 0;
            const mealMinutes = hasMealBreak ? 45 : 0;

            listEl.innerHTML = '';

            tripStops.forEach((stop, idx) => {
                const adjTravel = Math.round(stop.travel * travelMultiplier);
                totalTravel += adjTravel;
                currentMinutes += adjTravel;

                const arrMinutesVal = currentMinutes;
                const arrivalTime = formatTimeMinutes(currentMinutes);
                totalStay += stop.stay;
                currentMinutes += stop.stay;
                const depMinutesVal = currentMinutes;
                const departureTime = formatTimeMinutes(currentMinutes);

                totalTicketMin += (stop.priceMin || 0);
                totalTicketMax += (stop.priceMax || 0);

                // 1. Check Operating Hours
                let hoursWarningHtml = '';
                if (!stop.alwaysOpen && stop.open && stop.close) {
                    const openParts = stop.open.split(':').map(Number);
                    const closeParts = stop.close.split(':').map(Number);
                    const openMin = openParts[0] * 60 + openParts[1];
                    const closeMin = closeParts[0] * 60 + closeParts[1];

                    if (arrMinutesVal < openMin || depMinutesVal > closeMin) {
                        hoursWarningHtml = `
                            <div class="alert alert-danger py-1 px-2 my-1 border-0 rounded-2 small d-flex align-items-center gap-1.5" style="font-size: 0.70rem; line-height: 1.3;">
                                <i class="bi bi-clock-history text-danger fs-6"></i>
                                <span><strong>Hours Alert:</strong> Open ${stop.open} - ${stop.close}. Planned visit (${arrivalTime} - ${departureTime}) falls outside hours.</span>
                            </div>
                        `;
                    }
                }

                // 2. Check Closed Days
                let closedDayWarningHtml = '';
                if (stop.closedDays && tripDayName && stop.closedDays.includes(tripDayName)) {
                    closedDayWarningHtml = `
                        <div class="alert alert-warning py-1 px-2 my-1 border-0 rounded-2 small d-flex align-items-center gap-1.5" style="font-size: 0.70rem; line-height: 1.3;">
                            <i class="bi bi-calendar-x text-warning fs-6"></i>
                            <span><strong>Closed Day Warning:</strong> Usually closed on <strong>${tripDayName}s</strong>${stop.closureNotes ? ' (' + stop.closureNotes + ')' : ''}.</span>
                        </div>
                    `;
                }

                // 3. Seasonal Badging
                let seasonBadgeHtml = '';
                if (tripMonthName) {
                    if (stop.bestMonths && stop.bestMonths.includes(tripMonthName)) {
                        seasonBadgeHtml = `<span class="badge bg-success bg-opacity-10 text-success border border-success-subtle" style="font-size: 0.68rem;"><i class="bi bi-star-fill text-warning me-1"></i> Best Season (${tripMonthName})</span>`;
                    } else if (stop.notRecMonths && stop.notRecMonths.includes(tripMonthName)) {
                        seasonBadgeHtml = `<span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle" style="font-size: 0.68rem;"><i class="bi bi-cloud-rain-heavy-fill text-danger me-1"></i> Not Recommended (${tripMonthName})</span>`;
                    } else if (stop.moderateMonths && stop.moderateMonths.includes(tripMonthName)) {
                        seasonBadgeHtml = `<span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning-subtle" style="font-size: 0.68rem;"><i class="bi bi-cloud-sun text-warning me-1"></i> Moderate Season (${tripMonthName})</span>`;
                    }
                }

                // Insert meal break after 2nd stop if requested
                let mealBreakHtml = '';
                if (hasMealBreak && idx === 1) {
                    currentMinutes += 45;
                    mealBreakHtml = `
                        <div class="p-2 rounded-2 bg-warning bg-opacity-20 border border-warning text-center small text-warning-emphasis fw-bold">
                            <i class="bi bi-cup-hot-fill me-1"></i> Lunch & Refreshment Break (45 mins)
                        </div>
                    `;
                }

                const item = document.createElement('div');
                item.className = 'p-2.5 rounded-3 bg-light border d-flex flex-column gap-1';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-success rounded-pill" style="font-size: 0.72rem;">Stop ${idx + 1}</span>
                            ${seasonBadgeHtml}
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-secondary py-0 px-1.5 duration-minus-btn" data-index="${idx}" title="Decrease Stay (-15m)">-</button>
                            <span class="badge bg-white text-dark border px-2 d-flex align-items-center" style="font-size: 0.75rem;">${stop.stay}m</span>
                            <button type="button" class="btn btn-outline-secondary py-0 px-1.5 duration-plus-btn" data-index="${idx}" title="Increase Stay (+15m)">+</button>
                            <button type="button" class="btn btn-outline-danger py-0 px-1.5 remove-stop-btn ms-1" data-index="${idx}" title="Remove Stop">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="fw-bold small text-dark text-truncate">${stop.title}</div>
                    ${hoursWarningHtml}
                    ${closedDayWarningHtml}
                    <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.73rem;">
                        <span><i class="bi bi-car-front text-primary"></i> ${adjTravel}m drive</span>
                        <span><i class="bi bi-clock text-success"></i> ${arrivalTime} - ${departureTime}</span>
                        <span class="text-success fw-semibold"><i class="bi bi-ticket-perforated"></i> ${stop.priceRange}</span>
                    </div>
                `;

                listEl.appendChild(item);
                if (mealBreakHtml) {
                    const mbEl = document.createElement('div');
                    mbEl.innerHTML = mealBreakHtml;
                    listEl.appendChild(mbEl.firstElementChild);
                }
            });

            // Update Total Estimated Entrance Fees Display
            const ticketDisplayEl = document.getElementById('totalTicketFeesDisplay');
            if (ticketDisplayEl) {
                if (totalTicketMin === 0 && totalTicketMax === 0) {
                    ticketDisplayEl.textContent = 'Free Entry';
                } else if (totalTicketMin === totalTicketMax) {
                    ticketDisplayEl.textContent = 'LKR ' + Math.round(totalTicketMin).toLocaleString();
                } else {
                    ticketDisplayEl.textContent = 'LKR ' + Math.round(totalTicketMin).toLocaleString() + ' - ' + Math.round(totalTicketMax).toLocaleString();
                }
            }

                listEl.appendChild(item);
                if (mealBreakHtml) {
                    const mbEl = document.createElement('div');
                    mbEl.innerHTML = mealBreakHtml;
                    listEl.appendChild(mbEl.firstElementChild);
                }
            });

            // Return journey estimation (equal to last stop drive back)
            const returnTravel = Math.round((tripStops[tripStops.length - 1].travel || 25) * travelMultiplier);
            const totalUsed = totalTravel + totalStay + mealMinutes + returnTravel;
            const freeTime = Math.max(0, totalBudgetMinutes - totalUsed);

            // Update Time Meter Bar
            updateMeter(totalTravel, totalStay, mealMinutes, returnTravel, freeTime, totalBudgetMinutes);

            // Update Google Maps Link
            buildGoogleMapsUrl();

            // Update Map Route Preview
            updateMapRoute();

            // Bind Timeline Item Buttons (+, -, remove)
            document.querySelectorAll('.duration-plus-btn').forEach(b => {
                b.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-index'));
                    tripStops[idx].stay += 15;
                    renderTimeline();
                });
            });
            document.querySelectorAll('.duration-minus-btn').forEach(b => {
                b.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-index'));
                    if (tripStops[idx].stay > 15) {
                        tripStops[idx].stay -= 15;
                        renderTimeline();
                    }
                });
            });
            document.querySelectorAll('.remove-stop-btn').forEach(b => {
                b.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-index'));
                    tripStops.splice(idx, 1);
                    renderTimeline();
                });
            });
        }

        // Format minutes to 12-hour AM/PM string
        function formatTimeMinutes(minutes) {
            let h = Math.floor(minutes / 60) % 24;
            const m = minutes % 60;
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12; // 0 becomes 12
            const mStr = m < 10 ? '0' + m : m;
            return `${h}:${mStr} ${ampm}`;
        }

        // Update progress percentages on Sticky Time Meter
        function updateMeter(travel, stay, meal, returnTime, free, total = 600) {
            if (total <= 0) total = 600;
            const travelPct = Math.min(100, (travel / total) * 100);
            const stayPct = Math.min(100, (stay / total) * 100);
            const mealPct = Math.min(100, (meal / total) * 100);
            const returnPct = Math.min(100, (returnTime / total) * 100);
            const freePct = Math.max(0, 100 - (travelPct + stayPct + mealPct + returnPct));

            document.getElementById('meterTravel').style.width = travelPct + '%';
            document.getElementById('meterStay').style.width = stayPct + '%';
            document.getElementById('meterMeal').style.width = mealPct + '%';
            document.getElementById('meterReturn').style.width = returnPct + '%';
            document.getElementById('meterFree').style.width = freePct + '%';

            document.getElementById('legendTravel').textContent = travel + 'm';
            document.getElementById('legendStay').textContent = stay + 'm';
            document.getElementById('legendMeal').textContent = meal + 'm';
            document.getElementById('legendReturn').textContent = returnTime + 'm';
            document.getElementById('legendFree').textContent = free + 'm';

            document.getElementById('totalUsedDisplay').textContent = `Total: ${travel + stay + meal + returnTime}m / ${total}m`;
        }

        // Build Google Maps Route URL with Origin & Waypoints
        function buildGoogleMapsUrl() {
            if (tripStops.length === 0) return;
            const origin = encodeURIComponent(document.getElementById('cfgStartLocation').value || 'Bakamuna');
            const destStop = tripStops[tripStops.length - 1];
            const destination = `${destStop.lat},${destStop.lng}`;

            let waypoints = '';
            if (tripStops.length > 1) {
                const wpCoords = tripStops.slice(0, -1).map(s => `${s.lat},${s.lng}`).join('|');
                waypoints = `&waypoints=${encodeURIComponent(wpCoords)}`;
            }

            const gUrl = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}${waypoints}`;
            document.getElementById('btnOpenGoogleMaps').href = gUrl;
        }

        // Update Leaflet Route & Markers
        function updateMapRoute() {
            clearMapRoute();
            if (tripStops.length === 0) return;

            const latlngs = [];
            tripStops.forEach((stop, i) => {
                const pt = [stop.lat, stop.lng];
                latlngs.push(pt);

                const marker = L.circleMarker(pt, {
                    radius: 9,
                    fillColor: '#059669',
                    color: '#ffffff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.9
                }).addTo(leafletMap).bindPopup(`<strong>#${i + 1} ${stop.title}</strong>`);
                routeMarkers.push(marker);
            });

            routePolyline = L.polyline(latlngs, { color: '#059669', weight: 4, dashArray: '6, 6' }).addTo(leafletMap);
            leafletMap.fitBounds(routePolyline.getBounds(), { padding: [20, 20] });
        }

        function clearMapRoute() {
            routeMarkers.forEach(m => leafletMap.removeLayer(m));
            routeMarkers = [];
            if (routePolyline) {
                leafletMap.removeLayer(routePolyline);
                routePolyline = null;
            }
        }

        // Save Trip to My Account Button
        document.getElementById('btnSaveTrip').addEventListener('click', function () {
            if (tripStops.length === 0) {
                alert('Please add at least one destination to save a trip plan!');
                return;
            }

            const tripTitle = prompt('Enter a title for this trip plan:', 'Elahera 1-Day Highlights');
            if (!tripTitle) return;

            const payload = {
                title: tripTitle,
                trip_date: document.getElementById('cfgDate').value,
                start_location: document.getElementById('cfgStartLocation').value,
                start_time: document.getElementById('cfgStartTime').value,
                end_time: document.getElementById('cfgEndTime').value,
                transport_mode: document.getElementById('cfgTransportMode').value,
                meal_break: document.getElementById('cfgMealBreak').checked,
                stops: tripStops,
                total_duration_minutes: 600,
                google_maps_url: document.getElementById('btnOpenGoogleMaps').href
            };

            fetch('{{ route("planner.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = data.view_url;
                } else {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Error saving trip.');
                    }
                }
            })
            .catch(err => {
                alert('Could not save trip. Please ensure you are logged in.');
            });
        });

        // Print / Export PDF button
        document.getElementById('btnPrintTrip').addEventListener('click', function () {
            if (tripStops.length === 0) {
                alert('Add destinations first to print your itinerary.');
                return;
            }
            const date = document.getElementById('cfgDate').value;
            const startLoc = document.getElementById('cfgStartLocation').value;
            const startTime = document.getElementById('cfgStartTime').value;
            const transMode = document.getElementById('cfgTransportMode').value;
            const stopsJson = encodeURIComponent(JSON.stringify(tripStops));

            const printUrl = `{{ route("planner.print") }}?date=${date}&start_location=${encodeURIComponent(startLoc)}&start_time=${startTime}&transport_mode=${encodeURIComponent(transMode)}&stops=${stopsJson}`;
            window.open(printUrl, '_blank');
        });
    });
</script>
@endpush
