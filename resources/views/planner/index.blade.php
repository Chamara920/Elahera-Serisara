@extends('layouts.app')

@section('title', 'Smart Day-Trip Planner - ' . __('messages.brand_name'))

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

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
                    <span class="d-flex align-items-center gap-1" id="legendWaitWrapper" style="display: none !important;"><span class="badge rounded-circle p-1" style="background:#f97316;"> </span> Wait: <span id="legendWait">0m</span></span>
                    <span class="d-flex align-items-center gap-1"><span class="badge bg-success rounded-circle p-1"> </span> Stay: <span id="legendStay">0m</span></span>
                    <span class="d-flex align-items-center gap-1"><span class="badge bg-warning rounded-circle p-1"> </span> Meal: <span id="legendMeal">45m</span></span>
                    <span class="d-flex align-items-center gap-1"><span class="badge rounded-circle p-1" style="background:#8b5cf6;"> </span> Return: <span id="legendReturn">0m</span></span>
                    <span class="d-flex align-items-center gap-1 text-muted"><span class="badge bg-secondary rounded-circle p-1"> </span> Free: <span id="legendFree">600m</span></span>
                </div>
            </div>

            <!-- Visual Progress Meter -->
            <div class="progress" style="height: 12px; border-radius: 6px; background-color: #e2e8f0; overflow: hidden;" id="timeProgressBar">
                <div id="meterTravel" class="progress-bar bg-primary" role="progressbar" style="width: 0%;" title="Travel Time"></div>
                <div id="meterWait" class="progress-bar" role="progressbar" style="width: 0%; background-color: #f97316; display: none;" title="Transit Waiting Time"></div>
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
        <div class="card card-custom border-0 shadow-sm p-4 mb-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-calendar-event text-success me-1"></i> {{ __('messages.trip_date_label') }}
                    </label>
                    <input type="date" id="cfgDate" class="form-control form-control-sm" value="{{ $tripDate }}">
                </div>

                <!-- Normal Start Point (Car / Van / Tuk) -->
                <div class="col-md-2" id="normalStartLocationCol">
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

                <!-- Public Bus Transit Start Stop (Public Bus Mode) -->
                <div class="col-md-3" id="busStartStopCol" style="display: none;">
                    <label class="form-label small fw-bold text-danger mb-1 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-bus-front-fill me-1"></i> {{ app()->getLocale() === 'si' ? 'ආරම්භක බස් නැවතුම' : 'Start Bus Stop' }}</span>
                        <button type="button" class="btn btn-link p-0 text-primary text-decoration-none fw-semibold" style="font-size: 0.73rem;" data-bs-toggle="modal" data-bs-target="#transitBusStopsMapModal">
                            <i class="bi bi-map-fill me-1"></i> {{ app()->getLocale() === 'si' ? 'සිතියමෙන් තෝරන්න' : 'Pick on Map' }}
                        </button>
                    </label>
                    <select id="cfgBusStartStop" class="form-select form-select-sm border-danger border-opacity-50 bg-danger bg-opacity-10 fw-bold text-dark">
                        @foreach($busStops as $bs)
                            <option value="{{ $bs->id }}" data-lat="{{ $bs->latitude }}" data-lng="{{ $bs->longitude }}" data-code="{{ $bs->code }}" {{ ($defaultBusStop && $defaultBusStop->id == $bs->id) ? 'selected' : '' }}>
                                {{ $bs->getLocalizedName() }} {{ $bs->is_hub ? '★ (Hub)' : '' }}
                            </option>
                        @endforeach
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
                    <select id="cfgTransportMode" class="form-select form-select-sm fw-bold">
                        <option value="Car/Van" {{ $transportMode == 'Car/Van' ? 'selected' : '' }}>{{ __('messages.car_van') }}</option>
                        <option value="Motorbike/Tuk" {{ $transportMode == 'Motorbike/Tuk' ? 'selected' : '' }}>{{ __('messages.motorbike_tuk') }}</option>
                        <option value="Public Bus" {{ $transportMode == 'Public Bus' ? 'selected' : '' }}>{{ __('messages.public_bus') }} (Timetable)</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" id="cfgMealBreak" {{ $mealBreak ? 'checked' : '' }}>
                        <label class="form-check-label small fw-bold text-secondary" for="cfgMealBreak">
                            {{ __('messages.meal_break') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Transit Notification Banner for Public Bus -->
        <div id="publicBusTransitBanner" class="alert alert-danger bg-danger bg-opacity-10 border-danger-subtle py-2.5 px-3 mb-4 rounded-3 d-flex flex-wrap align-items-center justify-content-between gap-2 shadow-sm" style="display: none !important;">
            <div class="d-flex align-items-center gap-2.5">
                <div class="p-2 bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                    <i class="bi bi-bus-front-fill fs-6"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-danger-emphasis small">
                        {{ app()->getLocale() === 'si' ? 'කාලසටහන් බස් ගමනාගමන එන්ජිම (Timetable-Aware Transit Mode)' : 'Timetable Transit Engine Active' }}
                    </h6>
                    <span class="text-secondary small" style="font-size: 0.77rem;">
                        {{ app()->getLocale() === 'si' ? 'ප්‍රධාන බස් මාර්ග 5 සහ නැවතුම්පොළවල් 13 ක සැබෑ කාලසටහන් මත බස් මාරුවීම් (Transfers) සහ විකල්ප මාර්ග සසඳා පෙන්වයි.' : 'Direct bus routes, interchange connections, and departure timing alternatives across 5 registered routes.' }}
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger rounded-pill px-2.5 py-1 text-white small">
                    <i class="bi bi-check-circle-fill me-1"></i> Verified Timetables
                </span>
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
                                            data-distance="{{ $dest->distance_km ?? 0 }}"
                                            data-bus-halt="{{ $dest->nearest_bus_stop ?? '' }}"
                                            data-bus-halt-id="{{ $dest->nearest_bus_stop_id ?? '' }}"
                                            data-bus-halt-name="{{ $dest->nearestBusStop ? (app()->getLocale() === 'si' ? $dest->nearestBusStop->name_si : $dest->nearestBusStop->name_en) : ($dest->nearest_bus_stop ?? '') }}"
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
                                        <div class="text-secondary small leading-relaxed mb-4 rich-text-content">
                                            {!! Str::contains($dest->getLocalizedDescription(), ['<p>', '<br>', '<div>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>', '<h6>', '<ul>', '<ol>', '<strong>', '<em>', '<li>', '<blockquote>']) 
                                                ? $dest->getLocalizedDescription() 
                                                : nl2br(e($dest->getLocalizedDescription())) !!}
                                        </div>

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
                                                data-distance="{{ $dest->distance_km ?? 0 }}"
                                                data-bus-halt="{{ $dest->nearest_bus_stop ?? '' }}"
                                                data-bus-halt-id="{{ $dest->nearest_bus_stop_id ?? '' }}"
                                                data-bus-halt-name="{{ $dest->nearestBusStop ? (app()->getLocale() === 'si' ? $dest->nearestBusStop->name_si : $dest->nearestBusStop->name_en) : ($dest->nearest_bus_stop ?? '') }}"
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
                <div class="card card-custom border-0 shadow-sm sticky-top" style="top: 72px; z-index: 1010;">
                    <div class="card-header bg-white border-bottom p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-card-checklist text-success me-1"></i> Live Day-Trip Timeline
                            </h6>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <button type="button" id="btnOptimizeSequence" class="btn btn-sm btn-outline-success fw-bold py-0.5 px-2" style="font-size: 0.72rem; display: none;" title="{{ app()->getLocale() === 'si' ? 'බස් කාලසටහන අනුව අවම බලාසිටීමේ කාලයක් සහිත ප්‍රශස්ත අනුපිලිවෙල සකසන්න' : 'Auto-order destinations for optimal bus schedule' }}">
                                <i class="bi bi-stars text-warning me-1"></i> {{ app()->getLocale() === 'si' ? 'ප්‍රශස්ත අනුපිලිවෙල' : 'Optimize Order' }}
                            </button>
                            <span class="badge bg-success rounded-pill" id="stopsCounter">0 Stops</span>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <!-- Dynamic Transit Notice Alert -->
                        <div id="transitNoticeAlert" class="alert alert-success py-1.5 px-2.5 mb-2 small rounded-2 d-flex align-items-center gap-1.5" style="display: none; font-size: 0.74rem;">
                            <i class="bi bi-stars text-warning flex-shrink-0"></i>
                            <span id="transitNoticeText"></span>
                        </div>

                        <!-- Empty Notice -->
                        <div id="timelineEmptyNotice" class="text-center py-4 text-muted">
                            <i class="bi bi-compass fs-1 text-secondary opacity-50 d-block mb-2"></i>
                            <p class="small mb-1">Your timeline is empty.</p>
                            <span class="small text-muted">Click <strong>[+ Add]</strong> on any destination to build your day trip!</span>
                        </div>

                        <!-- Timeline List Container -->
                        <div id="timelineStopsList" class="d-flex flex-column gap-2 mb-3" style="max-height: 420px; overflow-y: auto;">
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

    <!-- Bus Stops Map Picker Modal -->
    <div class="modal fade" id="transitBusStopsMapModal" tabindex="-1" aria-labelledby="transitBusStopsMapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white py-2.5 px-3">
                    <h6 class="modal-title fw-bold" id="transitBusStopsMapModalLabel">
                        <i class="bi bi-bus-front-fill me-2"></i> {{ app()->getLocale() === 'si' ? 'කලාපීය බස් නැවතුම්පොළ සිතියම (Bus Stops Picker)' : 'Regional Bus Stops Map Picker' }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <p class="text-secondary small mb-2">
                        {{ app()->getLocale() === 'si' ? 'ඔබගේ චාරිකාව ආරම්භ කරන බස් නැවතුම්පොළ හෝ මාරුවීමේ හන්දිය සිතියම මත ක්ලික් කර තෝරාගන්න.' : 'Click on any bus stop or interchange hub marker on the map to set it as your starting bus terminal.' }}
                    </p>
                    <div id="busStopsPickerMap" style="height: 380px; border-radius: 8px; border: 1px solid #e2e8f0;"></div>
                    <div class="d-flex flex-wrap align-items-center gap-3 small mt-2 text-muted" style="font-size: 0.74rem;">
                        <span><span class="badge rounded-circle p-1" style="background: #dc2626;"> </span> <strong>ප්‍රධාන හන්දි / Transit Hubs</strong> (Bakamuna, Polathuwadiya, Elahera, Naula, Dambulla)</span>
                        <span><span class="badge rounded-circle p-1" style="background: #0284c7;"> </span> සාමාන්‍ය නැවතුම් (Standard Halts)</span>
                    </div>
                </div>
                <div class="modal-footer p-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // State variables
        let tripStops = [];
        let leafletMap = null;
        let routeMarkers = [];
        let routePolyline = null;
        let currentTotalUsedMinutes = 0;
        const isSi = '{{ app()->getLocale() }}' === 'si';
        const busStopsList = @json($busStops ?? []);
        let selectedAlternatives = {};
        let transitPickerMap = null;
        let transitPickerMarkers = [];

        // Regional Hub Coordinates for Starting Points
        const START_COORDINATES = {
            'Bakamuna Town': { lat: 7.9300, lng: 80.8200 },
            'Elahera Anicut': { lat: 7.7470, lng: 80.7780 },
            'Matale': { lat: 7.4675, lng: 80.6234 },
            'Polonnaruwa': { lat: 7.9403, lng: 81.0188 },
            'Dambulla': { lat: 7.8731, lng: 80.6517 }
        };

        // Precise Haversine Formula with Sri Lanka Rural Road Tortuosity (Winding) Index
        function calculateHaversineDistanceKm(lat1, lon1, lat2, lon2) {
            if (!lat1 || !lon1 || !lat2 || !lon2) return 5;
            const R = 6371; // Earth radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const straightDist = R * c;
            // Rural winding road multiplier (1.28 for canal roads, bends, and river crossings)
            return Math.max(0.5, Math.round(straightDist * 1.28 * 10) / 10);
        }

        // Realistic Leg Travel Time based on transport mode & rural corridor average speeds
        function calculateLegTravelMinutes(distanceKm, transportMode) {
            // Speed estimations for Elahera corridor:
            // Car/Van: ~38 km/h
            // Motorbike/Tuk: ~28 km/h
            // Public Bus (with frequent stops & passenger pickup): ~22 km/h
            let avgSpeed = 38;
            if (transportMode === 'Motorbike/Tuk') avgSpeed = 28;
            if (transportMode === 'Public Bus') avgSpeed = 22;

            const mins = Math.round((distanceKm / avgSpeed) * 60);
            return Math.max(5, mins); // Minimum 5 mins for any transfer
        }

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

        // Handle Transport Mode Switching UI
        function handleTransportModeToggle() {
            const mode = document.getElementById('cfgTransportMode').value;
            const isBus = mode === 'Public Bus';
            const busCol = document.getElementById('busStartStopCol');
            const normCol = document.getElementById('normalStartLocationCol');
            const busBanner = document.getElementById('publicBusTransitBanner');
            const optBtn = document.getElementById('btnOptimizeSequence');
            const legendWait = document.getElementById('legendWaitWrapper');

            if (busCol) busCol.style.display = isBus ? 'block' : 'none';
            if (normCol) normCol.style.display = isBus ? 'none' : 'block';
            if (busBanner) busBanner.style.setProperty('display', isBus ? 'flex' : 'none', 'important');
            if (optBtn) optBtn.style.display = (isBus && tripStops.length > 1) ? 'inline-block' : 'none';
            if (legendWait) legendWait.style.display = isBus ? 'flex' : 'none';

            if (!isBus) {
                const meterWait = document.getElementById('meterWait');
                if (meterWait) meterWait.style.width = '0%';
                const alertEl = document.getElementById('transitNoticeAlert');
                if (alertEl) alertEl.style.display = 'none';
            }
        }
        handleTransportModeToggle();

        // Add to Trip Button Handler
        document.querySelectorAll('.add-to-trip-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = parseInt(this.getAttribute('data-id'));
                const title = this.getAttribute('data-title');
                const distance = parseFloat(this.getAttribute('data-distance')) || 0;
                const busHalt = this.getAttribute('data-bus-halt') || '';
                const busHaltId = parseInt(this.getAttribute('data-bus-halt-id')) || null;
                const busHaltName = this.getAttribute('data-bus-halt-name') || busHalt;
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
                    alert(isSi ? 'මෙම ගමනාන්තය දැනටමත් චාරිකා සැලැස්මට ඇතුළත් කර ඇත!' : 'This destination is already added to your day trip!');
                    return;
                }

                tripStops.push({
                    id: id,
                    title: title,
                    distance: distance,
                    busHalt: busHalt,
                    busHaltId: busHaltId,
                    busHaltName: busHaltName,
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
        ['cfgDate', 'cfgStartTime', 'cfgEndTime', 'cfgStartLocation', 'cfgTransportMode', 'cfgMealBreak', 'cfgBusStartStop'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', function () {
                    if (id === 'cfgTransportMode' || id === 'cfgBusStartStop') {
                        selectedAlternatives = {};
                    }
                    handleTransportModeToggle();
                    renderTimeline();
                });
            }
        });

        // Main Router for Timeline Rendering
        function renderTimeline() {
            handleTransportModeToggle();
            const listEl = document.getElementById('timelineStopsList');
            const emptyEl = document.getElementById('timelineEmptyNotice');
            const counterEl = document.getElementById('stopsCounter');
            const googleMapsBtn = document.getElementById('btnOpenGoogleMaps');

            counterEl.textContent = `${tripStops.length} ${isSi ? 'නැවතුම්' : (tripStops.length === 1 ? 'Stop' : 'Stops')}`;

            if (tripStops.length === 0) {
                emptyEl.style.display = 'block';
                listEl.innerHTML = '';
                googleMapsBtn.classList.add('disabled');
                updateMeter(0, 0, 0, 0, 600, 600, 0);
                clearMapRoute();
                const ticketDisplayEl = document.getElementById('totalTicketFeesDisplay');
                if (ticketDisplayEl) ticketDisplayEl.textContent = 'Free';
                const distEl = document.getElementById('routeDistanceDisplay');
                if (distEl) distEl.textContent = '0 km total';
                const noticeAlert = document.getElementById('transitNoticeAlert');
                if (noticeAlert) noticeAlert.style.display = 'none';
                return;
            }

            emptyEl.style.display = 'none';
            googleMapsBtn.classList.remove('disabled');

            const transportMode = document.getElementById('cfgTransportMode').value;
            if (transportMode === 'Public Bus') {
                renderTransitTimeline();
            } else {
                renderStandardTimeline();
            }
        }

        // 1. Render Public Bus Transit Timetable & Interchange Timeline
        function renderTransitTimeline() {
            const listEl = document.getElementById('timelineStopsList');
            const startTimeStr = document.getElementById('cfgStartTime').value || '08:00';
            const endTimeStr = document.getElementById('cfgEndTime').value || '18:00';
            const startBusStopId = parseInt(document.getElementById('cfgBusStartStop').value);

            // Calculate total time budget in minutes
            const startParts = startTimeStr.split(':').map(Number);
            const endParts = endTimeStr.split(':').map(Number);
            const totalBudgetMinutes = Math.max(120, (endParts[0] * 60 + endParts[1]) - (startParts[0] * 60 + startParts[1]));

            // Loading indicator while routing engine runs
            listEl.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <div class="spinner-border text-danger spinner-border-sm me-2" role="status"></div>
                    <span class="small">${isSi ? 'බස් කාලසටහන් සහ හන්දි මාරුවීම් ගණනය කරමින්...' : 'Calculating timetable & interchange connections...'}</span>
                </div>
            `;

            const payload = {
                start_stop_id: startBusStopId,
                start_time: startTimeStr,
                service_day: 'all',
                stops: tripStops.map(s => ({
                    destination_id: s.id,
                    stay_minutes: s.stay
                })),
                chosen_alternatives: selectedAlternatives
            };

            fetch('{{ route("planner.calculate-transit") }}', {
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
                if (!data.success || !data.plan) {
                    listEl.innerHTML = `<div class="alert alert-danger py-2 px-3 small">${data.message || 'Error calculating bus route.'}</div>`;
                    return;
                }

                const plan = data.plan;
                listEl.innerHTML = '';

                // Feasibility Warning Notice (Missed Bus, Overnight, Impossible Transfer)
                if (plan.has_missed_connections) {
                    const warnDiv = document.createElement('div');
                    warnDiv.className = 'alert alert-danger py-1.5 px-2.5 mb-2 rounded-2 small d-flex align-items-center gap-1.5 shadow-sm';
                    warnDiv.style.fontSize = '0.73rem';
                    warnDiv.innerHTML = `
                        <i class="bi bi-exclamation-triangle-fill text-danger flex-shrink-0 fs-6"></i>
                        <span>${isSi ? 'සමහර බස් සම්බන්ධතා කාලසටහන් සීමාවන් ඉක්මවා යාමේ අවදානමක් ඇත. කරුණාකර වේලාවන් පරීක්ෂා කරන්න.' : 'Some transit legs exceed timetable limits or have limited bus frequency. Please verify times.'}</span>
                    `;
                    listEl.appendChild(warnDiv);
                }

                let totalTicketMin = 0;
                let totalTicketMax = 0;

                // Render each leg in the transit timeline
                plan.legs.forEach((leg, idx) => {
                    const stop = tripStops[idx];
                    if (!stop) return;

                    totalTicketMin += (stop.priceMin || 0);
                    totalTicketMax += (stop.priceMax || 0);

                    const conn = leg.connection;
                    const isDirect = conn ? (conn.type === 'direct' || conn.transfers_count === 0) : false;

                    // Sequence Up/Down button controls
                    const upDisabled = idx === 0 ? 'disabled' : '';
                    const downDisabled = idx === tripStops.length - 1 ? 'disabled' : '';

                    // Connection description markup
                    let connHtml = '';
                    if (conn && conn.legs && conn.legs.length > 0) {
                        const leg1 = conn.legs[0];
                        if (isDirect) {
                            connHtml = `
                                <div class="bg-white p-2 rounded-2 border border-danger border-opacity-25 mt-1 small">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-danger rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">
                                            <i class="bi bi-lightning-charge-fill me-1"></i> Direct Route ${leg1.route_number}
                                        </span>
                                        <span class="text-danger fw-bold" style="font-size: 0.72rem;">
                                            ⏱️ ${conn.total_duration_minutes}m (${conn.travel_minutes}m ride)
                                        </span>
                                    </div>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">
                                        ${leg1.trip_name || (isSi ? leg1.route_name_si : leg1.route_name_en)}
                                    </div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.71rem;">
                                        <span><i class="bi bi-box-arrow-right text-danger"></i> Dep: <strong>${leg1.departure_time}</strong> (${isSi ? leg1.board_stop_name_si : leg1.board_stop_name_en})</span>
                                        <span><i class="bi bi-box-arrow-in-right text-success"></i> Arr: <strong>${leg1.arrival_time}</strong> (${isSi ? leg1.alight_stop_name_si : leg1.alight_stop_name_en})</span>
                                    </div>
                                </div>
                            `;
                        } else if (conn.legs.length > 1) {
                            const leg2 = conn.legs[1];
                            connHtml = `
                                <div class="bg-white p-2 rounded-2 border border-warning border-opacity-50 mt-1 small">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">
                                            <i class="bi bi-arrow-repeat me-1"></i> 1-Transfer via ${conn.via_hub_name || 'Hub'}
                                        </span>
                                        <span class="text-danger fw-bold" style="font-size: 0.72rem;">
                                            ⏱️ ${conn.total_duration_minutes}m Total
                                        </span>
                                    </div>
                                    <!-- Leg 1 -->
                                    <div class="text-truncate text-secondary mb-0.5" style="font-size: 0.71rem;">
                                        <strong>1️⃣ Route ${leg1.route_number}:</strong> ${leg1.departure_time} ${isSi ? leg1.board_stop_name_si : leg1.board_stop_name_en} ➔ ${leg1.arrival_time} ${isSi ? leg1.alight_stop_name_si : leg1.alight_stop_name_en}
                                    </div>
                                    <!-- Transfer Hub Wait Box -->
                                    <div class="bg-warning bg-opacity-10 border border-warning-subtle rounded px-2 py-0.5 my-1 d-flex justify-content-between align-items-center" style="font-size: 0.69rem;">
                                        <span class="text-warning-emphasis">
                                            <i class="bi bi-signpost-split-fill me-1"></i> ${isSi ? 'බස් මාරුවන හන්දිය' : 'Transfer Hub'}: <strong>${conn.via_hub_name || 'Interchange Hub'}</strong>
                                        </span>
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-hourglass-split me-1"></i> Wait: ${leg2.transfer_wait_at_board_minutes || conn.waiting_minutes}m
                                        </span>
                                    </div>
                                    <!-- Leg 2 -->
                                    <div class="text-truncate text-secondary" style="font-size: 0.71rem;">
                                        <strong>2️⃣ Route ${leg2.route_number}:</strong> ${leg2.departure_time} ${isSi ? leg2.board_stop_name_si : leg2.board_stop_name_en} ➔ ${leg2.arrival_time} ${isSi ? leg2.alight_stop_name_si : leg2.alight_stop_name_en}
                                    </div>
                                </div>
                            `;
                        }
                    } else if (leg.warning_message) {
                        connHtml = `<div class="alert alert-warning py-1 px-2 my-1 rounded-2 small" style="font-size: 0.71rem;">${leg.warning_message}</div>`;
                    }

                    // Alternatives Switcher Pills (If multiple route options exist)
                    let alternativesHtml = '';
                    if (leg.available_alternatives && leg.available_alternatives.length > 1) {
                        const altButtons = leg.available_alternatives.map((alt, aIdx) => {
                            const isSelected = (leg.selected_alternative_index === aIdx);
                            const altLabel = alt.summary_badge || (alt.type === 'direct' ? 'Route ' + alt.legs[0].route_number : 'Via ' + alt.via_hub_name);
                            return `
                                <button type="button" class="btn btn-xs rounded-pill switch-alt-btn py-0 px-2 ${isSelected ? 'btn-danger text-white shadow-sm fw-bold' : 'btn-outline-danger'}" 
                                    data-leg-idx="${idx}" 
                                    data-alt-idx="${aIdx}" 
                                    style="font-size: 0.68rem;">
                                    ${isSelected ? '<i class="bi bi-check-circle-fill me-1"></i>' : ''}${alt.type === 'direct' ? '⚡ Direct' : '🔄 Transfer'} - ${altLabel} (${alt.total_duration_minutes}m)
                                </button>
                            `;
                        }).join('');

                        alternativesHtml = `
                            <div class="mt-2 pt-1.5 border-top">
                                <span class="small fw-bold text-secondary d-block mb-1" style="font-size: 0.70rem;">
                                    <i class="bi bi-sign-split me-1 text-danger"></i> ${isSi ? 'විකල්ප බස් මාර්ග (Route Alternatives):' : 'Available Route Alternatives:'}
                                </span>
                                <div class="d-flex flex-wrap gap-1">
                                    ${altButtons}
                                </div>
                            </div>
                        `;
                    }

                    const card = document.createElement('div');
                    card.className = 'p-2.5 rounded-3 bg-light border border-danger border-opacity-25 d-flex flex-column gap-1.5';
                    card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.72rem;">Stop ${idx + 1}</span>
                                <div class="btn-group btn-group-sm ms-1">
                                    <button type="button" class="btn btn-outline-secondary py-0 px-1.5 move-stop-up-btn" data-index="${idx}" ${upDisabled} title="${isSi ? 'ඉහළට ගෙනයන්න' : 'Move Up'}">
                                        ▲
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary py-0 px-1.5 move-stop-down-btn" data-index="${idx}" ${downDisabled} title="${isSi ? 'පහළට ගෙනයන්න' : 'Move Down'}">
                                        ▼
                                    </button>
                                </div>
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

                        <!-- Destination Title & Nearest Halt -->
                        <div class="d-flex justify-content-between align-items-baseline">
                            <div class="fw-bold small text-dark text-truncate">${stop.title}</div>
                            <span class="text-success fw-semibold small" style="font-size: 0.73rem;"><i class="bi bi-ticket-perforated"></i> ${stop.priceRange}</span>
                        </div>
                        <div class="small text-danger bg-danger bg-opacity-10 rounded px-2 py-0.5 d-flex align-items-center justify-content-between" style="font-size: 0.70rem;">
                            <span><i class="bi bi-bus-front-fill me-1"></i> ${isSi ? 'ලඟම බස් නැවතුම' : 'Nearest Halt'}: <strong>${leg.to_stop_name}</strong></span>
                            <span class="text-muted"><i class="bi bi-person-walking"></i> ~5m walk</span>
                        </div>

                        <!-- Transit Connection Box -->
                        ${connHtml}

                        <!-- Arrival & Stay Duration Breakdown -->
                        <div class="d-flex justify-content-between align-items-center text-muted border-top pt-1 mt-0.5" style="font-size: 0.72rem;">
                            <span><i class="bi bi-clock-history text-primary"></i> ${isSi ? 'පැමිණීම' : 'Arrive'}: <strong>${leg.arrival_time || 'N/A'}</strong></span>
                            <span><i class="bi bi-stopwatch text-success"></i> ${isSi ? 'නැරඹීම' : 'Stay'}: <strong>${stop.stay}m</strong></span>
                            <span><i class="bi bi-arrow-right-circle text-danger"></i> ${isSi ? 'පිටත්වීම' : 'Leaves'}: <strong>${leg.stay_end_time || 'N/A'}</strong></span>
                        </div>

                        <!-- Alternatives Pills -->
                        ${alternativesHtml}
                    `;

                    listEl.appendChild(card);
                });

                // Return Journey Card to Origin
                if (plan.return_leg && plan.return_leg.connection) {
                    const retConn = plan.return_leg.connection;
                    const retDiv = document.createElement('div');
                    retDiv.className = 'p-2.5 rounded-3 bg-white border border-danger shadow-sm mt-1';
                    retDiv.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-danger rounded-pill px-2 py-0.5" style="font-size: 0.70rem;">
                                <i class="bi bi-arrow-return-left me-1"></i> ${isSi ? 'ආපසු ගමන (Return Journey)' : 'Return Transit'}
                            </span>
                            <span class="small fw-bold text-danger" style="font-size: 0.74rem;">
                                ${isSi ? 'ආරම්භක නැවතුමට පැමිණීම' : 'Back at Origin'}: ${plan.finish_time}
                            </span>
                        </div>
                        <div class="small text-muted" style="font-size: 0.71rem;">
                            ${retConn.type === 'direct' 
                                ? `<i class="bi bi-bus-front text-danger me-1"></i> Route <strong>${retConn.legs[0].route_number}</strong> from ${plan.return_leg.from_stop_name} to ${plan.return_leg.to_stop_name} (${retConn.total_duration_minutes}m)`
                                : `<i class="bi bi-arrow-repeat text-warning me-1"></i> 1-Transfer via <strong>${retConn.via_hub_name}</strong> to ${plan.return_leg.to_stop_name} (${retConn.total_duration_minutes}m)`
                            }
                        </div>
                    `;
                    listEl.appendChild(retDiv);
                }

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

                // Update Distance Display
                const distEl = document.getElementById('routeDistanceDisplay');
                if (distEl) {
                    distEl.textContent = `${tripStops.length * 14} km est. bus network`;
                }

                // Update Sticky Time Meter
                const transitMins = plan.total_travel_minutes || 0;
                const waitMins = plan.total_waiting_minutes || 0;
                const stayMins = plan.total_stay_minutes || 0;
                const returnDuration = (plan.return_leg && plan.return_leg.connection) ? plan.return_leg.connection.total_duration_minutes : 0;
                const totalUsed = plan.total_used_minutes || (transitMins + waitMins + stayMins + returnDuration);
                currentTotalUsedMinutes = totalUsed;
                const freeMins = Math.max(0, totalBudgetMinutes - totalUsed);

                updateMeter(transitMins, stayMins, 0, returnDuration, freeMins, totalBudgetMinutes, waitMins);

                // Update Google Maps Link with transit mode
                buildGoogleMapsUrl();

                // Update Map Route Preview for Transit Mode
                updateTransitMapRoute(plan.legs);

                // Bind Interactive Buttons
                bindTimelineItemButtons();
            })
            .catch(err => {
                console.error('Transit calculation error:', err);
                listEl.innerHTML = `<div class="alert alert-danger py-2 px-3 small">Could not connect to bus schedule service. Falling back to road calculation.</div>`;
            });
        }

        // 2. Render Standard Driving Timeline (Car, Van, Motorbike, Tuk)
        function renderStandardTimeline() {
            const listEl = document.getElementById('timelineStopsList');
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

            // Calculate total time budget in minutes
            const startParts = startTimeStr.split(':').map(Number);
            const endParts = endTimeStr.split(':').map(Number);
            const totalBudgetMinutes = Math.max(120, (endParts[0] * 60 + endParts[1]) - (startParts[0] * 60 + startParts[1]));

            let currentMinutes = startParts[0] * 60 + startParts[1];
            let totalTravel = 0;
            let totalStay = 0;
            let totalDistance = 0;
            let totalTicketMin = 0;
            let totalTicketMax = 0;
            const mealMinutes = hasMealBreak ? 45 : 0;

            const startLocName = document.getElementById('cfgStartLocation').value || 'Bakamuna Town';
            const startPoint = START_COORDINATES[startLocName] || START_COORDINATES['Bakamuna Town'];
            let prevPoint = startPoint;
            let prevName = startLocName.replace(' Town', '');

            listEl.innerHTML = '';

            tripStops.forEach((stop, idx) => {
                const legDistance = calculateHaversineDistanceKm(prevPoint.lat, prevPoint.lng, stop.lat, stop.lng);
                const legTravel = calculateLegTravelMinutes(legDistance, transportMode);

                totalDistance += legDistance;
                totalTravel += legTravel;
                currentMinutes += legTravel;

                const arrMinutesVal = currentMinutes;
                const arrivalTime = formatTimeMinutes(currentMinutes);
                totalStay += stop.stay;
                currentMinutes += stop.stay;
                const depMinutesVal = currentMinutes;
                const departureTime = formatTimeMinutes(currentMinutes);

                totalTicketMin += (stop.priceMin || 0);
                totalTicketMax += (stop.priceMax || 0);

                // Operating Hours Check
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

                // Closed Days Check
                let closedDayWarningHtml = '';
                if (stop.closedDays && tripDayName && stop.closedDays.includes(tripDayName)) {
                    closedDayWarningHtml = `
                        <div class="alert alert-warning py-1 px-2 my-1 border-0 rounded-2 small d-flex align-items-center gap-1.5" style="font-size: 0.70rem; line-height: 1.3;">
                            <i class="bi bi-calendar-x text-warning fs-6"></i>
                            <span><strong>Closed Day Warning:</strong> Usually closed on <strong>${tripDayName}s</strong>${stop.closureNotes ? ' (' + stop.closureNotes + ')' : ''}.</span>
                        </div>
                    `;
                }

                // Seasonal Badging
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

                // Meal Break after 2nd stop
                let mealBreakHtml = '';
                if (hasMealBreak && idx === 1) {
                    currentMinutes += 45;
                    mealBreakHtml = `
                        <div class="p-2 rounded-2 bg-warning bg-opacity-20 border border-warning text-center small text-warning-emphasis fw-bold">
                            <i class="bi bi-cup-hot-fill me-1"></i> Lunch & Refreshment Break (45 mins)
                        </div>
                    `;
                }

                const upDisabled = idx === 0 ? 'disabled' : '';
                const downDisabled = idx === tripStops.length - 1 ? 'disabled' : '';

                const item = document.createElement('div');
                item.className = 'p-2.5 rounded-3 bg-light border d-flex flex-column gap-1';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-success rounded-pill" style="font-size: 0.72rem;">Stop ${idx + 1}</span>
                            <div class="btn-group btn-group-sm ms-1">
                                <button type="button" class="btn btn-outline-secondary py-0 px-1.5 move-stop-up-btn" data-index="${idx}" ${upDisabled} title="${isSi ? 'ඉහළට ගෙනයන්න' : 'Move Up'}">
                                    ▲
                                </button>
                                <button type="button" class="btn btn-outline-secondary py-0 px-1.5 move-stop-down-btn" data-index="${idx}" ${downDisabled} title="${isSi ? 'පහළට ගෙනයන්න' : 'Move Down'}">
                                    ▼
                                </button>
                            </div>
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
                        <span><i class="bi bi-signpost-2 text-primary"></i> ${prevName} සිට ${legDistance} km (${legTravel}m)</span>
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

                prevPoint = { lat: stop.lat, lng: stop.lng };
                prevName = stop.title;
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

            // Return journey estimation (from last stop back to origin)
            const returnDistance = calculateHaversineDistanceKm(prevPoint.lat, prevPoint.lng, startPoint.lat, startPoint.lng);
            const returnTravel = calculateLegTravelMinutes(returnDistance, transportMode);
            totalDistance += returnDistance;
            const totalUsed = totalTravel + totalStay + mealMinutes + returnTravel;
            currentTotalUsedMinutes = totalUsed;
            const freeTime = Math.max(0, totalBudgetMinutes - totalUsed);

            // Update Total Estimated Route Distance Display
            const distEl = document.getElementById('routeDistanceDisplay');
            if (distEl) {
                distEl.textContent = `${Math.round(totalDistance * 10) / 10} km total`;
            }

            // Update Time Meter Bar
            updateMeter(totalTravel, totalStay, mealMinutes, returnTravel, freeTime, totalBudgetMinutes, 0);

            // Update Google Maps Link
            buildGoogleMapsUrl();

            // Update Map Route Preview
            updateDrivingMapRoute();

            // Bind Timeline Item Buttons (+, -, remove, move)
            bindTimelineItemButtons();
        }

        // Bind Timeline Action Buttons (Stay +/- , Remove, Move Up/Down, Alternative Switcher)
        function bindTimelineItemButtons() {
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
                    selectedAlternatives = {};
                    renderTimeline();
                });
            });

            document.querySelectorAll('.move-stop-up-btn').forEach(b => {
                b.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-index'));
                    if (idx > 0) {
                        const temp = tripStops[idx];
                        tripStops[idx] = tripStops[idx - 1];
                        tripStops[idx - 1] = temp;
                        selectedAlternatives = {};
                        renderTimeline();
                    }
                });
            });

            document.querySelectorAll('.move-stop-down-btn').forEach(b => {
                b.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-index'));
                    if (idx < tripStops.length - 1) {
                        const temp = tripStops[idx];
                        tripStops[idx] = tripStops[idx + 1];
                        tripStops[idx + 1] = temp;
                        selectedAlternatives = {};
                        renderTimeline();
                    }
                });
            });

            document.querySelectorAll('.switch-alt-btn').forEach(b => {
                b.addEventListener('click', function () {
                    const legIdx = parseInt(this.getAttribute('data-leg-idx'));
                    const altIdx = parseInt(this.getAttribute('data-alt-idx'));
                    selectedAlternatives[legIdx] = altIdx;
                    renderTransitTimeline();
                });
            });
        }

        // Optimize Destination Sequence Algorithm Trigger
        const btnOptimize = document.getElementById('btnOptimizeSequence');
        if (btnOptimize) {
            btnOptimize.addEventListener('click', function () {
                if (tripStops.length < 2) return;
                const originalHtml = this.innerHTML;
                this.disabled = true;
                this.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> ${isSi ? 'සකසමින්...' : 'Optimizing...'}`;

                const startBusStopId = parseInt(document.getElementById('cfgBusStartStop').value);
                const startTime = document.getElementById('cfgStartTime').value || '08:00';

                fetch('{{ route("planner.optimize-transit-sequence") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        start_stop_id: startBusStopId,
                        start_time: startTime,
                        service_day: 'all',
                        stops: tripStops.map(s => ({
                            destination_id: s.id,
                            stay_minutes: s.stay
                        }))
                    })
                })
                .then(res => res.json())
                .then(data => {
                    btnOptimize.disabled = false;
                    btnOptimize.innerHTML = originalHtml;

                    if (data.success && data.optimization) {
                        const opt = data.optimization;
                        const noticeAlert = document.getElementById('transitNoticeAlert');
                        const noticeText = document.getElementById('transitNoticeText');

                        if (opt.is_reordered) {
                            const newTripStops = [];
                            opt.optimized_stops.forEach(item => {
                                const stop = tripStops.find(s => s.id === item.destination_id);
                                if (stop) newTripStops.push(stop);
                            });
                            tripStops = newTripStops;
                            selectedAlternatives = {};

                            if (noticeAlert && noticeText) {
                                noticeText.innerHTML = `<strong>${isSi ? '⚡ කාලය වඩාත් කළමනාකරණය වන ප්‍රශස්ත අනුපිලිවෙලට සැකසිණි!' : 'Optimized sequence applied!'}</strong> ${isSi ? 'ඉතිරි වූ කාලය: ' : 'Time saved: '} <strong>${opt.time_saved_minutes} ${isSi ? 'මිනිත්තු' : 'mins'}</strong>.`;
                                noticeAlert.style.display = 'flex';
                            }
                            renderTimeline();
                        } else {
                            if (noticeAlert && noticeText) {
                                noticeText.innerHTML = isSi 
                                    ? 'දැනටමත් තෝරාගෙන ඇත්තේ බස් කාලසටහනට අනුව වඩාත්ම කාර්යක්ෂම අනුපිලිවෙලයි.' 
                                    : 'Current destination order is already the most time-optimal sequence.';
                                noticeAlert.style.display = 'flex';
                            }
                        }
                    }
                })
                .catch(err => {
                    btnOptimize.disabled = false;
                    btnOptimize.innerHTML = originalHtml;
                    console.error('Error optimizing sequence:', err);
                });
            });
        }

        // Format minutes to 12-hour AM/PM string
        function formatTimeMinutes(minutes) {
            let h = Math.floor(minutes / 60) % 24;
            const m = minutes % 60;
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12;
            const mStr = m < 10 ? '0' + m : m;
            return `${h}:${mStr} ${ampm}`;
        }

        // Update progress percentages on Sticky Time Meter (Including Wait Time)
        function updateMeter(travel, stay, meal, returnTime, free, total = 600, wait = 0) {
            if (total <= 0) total = 600;
            const travelPct = Math.min(100, (travel / total) * 100);
            const waitPct = Math.min(100, (wait / total) * 100);
            const stayPct = Math.min(100, (stay / total) * 100);
            const mealPct = Math.min(100, (meal / total) * 100);
            const returnPct = Math.min(100, (returnTime / total) * 100);
            const freePct = Math.max(0, 100 - (travelPct + waitPct + stayPct + mealPct + returnPct));

            document.getElementById('meterTravel').style.width = travelPct + '%';
            const meterWaitEl = document.getElementById('meterWait');
            if (meterWaitEl) meterWaitEl.style.width = waitPct + '%';
            document.getElementById('meterStay').style.width = stayPct + '%';
            document.getElementById('meterMeal').style.width = mealPct + '%';
            document.getElementById('meterReturn').style.width = returnPct + '%';
            document.getElementById('meterFree').style.width = freePct + '%';

            document.getElementById('legendTravel').textContent = travel + 'm';
            const legWaitEl = document.getElementById('legendWait');
            if (legWaitEl) legWaitEl.textContent = wait + 'm';
            document.getElementById('legendStay').textContent = stay + 'm';
            document.getElementById('legendMeal').textContent = meal + 'm';
            document.getElementById('legendReturn').textContent = returnTime + 'm';
            document.getElementById('legendFree').textContent = free + 'm';

            document.getElementById('totalUsedDisplay').textContent = `Total: ${travel + wait + stay + meal + returnTime}m / ${total}m`;

            const timeWindowEl = document.getElementById('timeWindowDisplay');
            if (timeWindowEl) {
                const sStr = document.getElementById('cfgStartTime').value || '08:00';
                const eStr = document.getElementById('cfgEndTime').value || '18:00';
                const sMin = parseInt(sStr.split(':')[0], 10) * 60 + parseInt(sStr.split(':')[1], 10);
                const eMin = parseInt(eStr.split(':')[0], 10) * 60 + parseInt(eStr.split(':')[1], 10);
                const diffHrs = Math.max(0, Math.round(((eMin - sMin) / 60) * 10) / 10);
                timeWindowEl.textContent = `${formatTimeMinutes(sMin)} - ${formatTimeMinutes(eMin)} (${diffHrs} hrs)`;
            }
        }

        // Build Google Maps Route URL with Origin & Waypoints
        function buildGoogleMapsUrl() {
            const googleMapsBtn = document.getElementById('btnOpenGoogleMaps');
            if (!googleMapsBtn) return;

            if (tripStops.length === 0) {
                googleMapsBtn.classList.add('disabled');
                return;
            }
            googleMapsBtn.classList.remove('disabled');

            const transportMode = document.getElementById('cfgTransportMode').value;
            let startLocName = document.getElementById('cfgStartLocation').value || 'Bakamuna';

            if (transportMode === 'Public Bus') {
                const sel = document.getElementById('cfgBusStartStop');
                if (sel && sel.options[sel.selectedIndex]) {
                    startLocName = sel.options[sel.selectedIndex].text.replace('★ (Hub)', '').trim();
                }
            }

            const origin = encodeURIComponent(startLocName);
            const destStop = tripStops[tripStops.length - 1];
            const destination = `${destStop.lat},${destStop.lng}`;

            let waypoints = '';
            if (tripStops.length > 1) {
                const wpCoords = tripStops.slice(0, -1).map(s => `${s.lat},${s.lng}`).join('|');
                waypoints = `&waypoints=${encodeURIComponent(wpCoords)}`;
            }

            const travelModeParam = (transportMode === 'Public Bus') ? '&travelmode=transit' : '&travelmode=driving';
            const gUrl = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}${waypoints}${travelModeParam}`;
            googleMapsBtn.href = gUrl;

            if (transportMode === 'Public Bus') {
                googleMapsBtn.className = 'btn btn-danger btn-sm fw-semibold shadow-sm text-white d-flex align-items-center justify-content-center gap-1.5';
                googleMapsBtn.innerHTML = `<i class="bi bi-bus-front-fill fs-6"></i> ${isSi ? 'Google Maps බස් මාර්ගය බලන්න (Transit Mode)' : 'Open Bus Route in Google Maps (Transit)'}`;
                googleMapsBtn.title = isSi ? 'ශ්‍රී ලංකා බස් මාර්ග සහ කාලසටහන් Google Maps හරහා බලන්න' : 'View Sri Lanka transit and bus timetables on Google Maps';
            } else {
                googleMapsBtn.className = 'btn btn-outline-success btn-sm fw-semibold d-flex align-items-center justify-content-center gap-1.5';
                googleMapsBtn.innerHTML = `<i class="bi bi-map-fill fs-6"></i> ${isSi ? 'Google Maps හි මාර්ගය බලන්න' : 'Open Route in Google Maps'}`;
                googleMapsBtn.title = isSi ? 'Google Maps වෙතින් සම්පුර්ණ මාර්ගය බලන්න' : 'Open full driving route on Google Maps';
            }
        }

        // Map: Update Public Bus Route Preview
        function updateTransitMapRoute(itinerary) {
            clearMapRoute();
            if (tripStops.length === 0) return;

            const busSel = document.getElementById('cfgBusStartStop');
            const selectedOpt = busSel ? busSel.options[busSel.selectedIndex] : null;
            const startLat = selectedOpt ? parseFloat(selectedOpt.getAttribute('data-lat')) : 7.9300;
            const startLng = selectedOpt ? parseFloat(selectedOpt.getAttribute('data-lng')) : 80.8200;
            const startName = selectedOpt ? selectedOpt.text.replace('★ (Hub)', '').trim() : 'Start Bus Terminal';

            // Start Bus Terminal Marker (Red Bus Icon style)
            const startMarker = L.circleMarker([startLat, startLng], {
                radius: 10,
                fillColor: '#dc2626',
                color: '#ffffff',
                weight: 2.5,
                opacity: 1,
                fillOpacity: 0.95
            }).addTo(leafletMap).bindPopup(`<strong>🚩 ${isSi ? 'ආරම්භක නැවතුම' : 'Start Terminal'}: ${startName}</strong>`);
            routeMarkers.push(startMarker);

            const latlngs = [[startLat, startLng]];

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
                }).addTo(leafletMap).bindPopup(`<strong>#${i + 1} ${stop.title}</strong><br><small class="text-danger"><i class="bi bi-bus-front"></i> ${stop.busHaltName || stop.busHalt}</small>`);
                routeMarkers.push(marker);
            });

            // Draw Transit Line
            routePolyline = L.polyline(latlngs, {
                color: '#dc2626',
                weight: 4,
                dashArray: '8, 8',
                opacity: 0.85
            }).addTo(leafletMap);
            leafletMap.fitBounds(routePolyline.getBounds(), { padding: [25, 25] });
        }

        // Map: Update Driving Route & Markers (with Start Location Hub & OSRM Real Road Geometry)
        function updateDrivingMapRoute() {
            clearMapRoute();
            if (tripStops.length === 0) return;

            const startLocName = document.getElementById('cfgStartLocation').value || 'Bakamuna Town';
            const startPoint = START_COORDINATES[startLocName] || START_COORDINATES['Bakamuna Town'];

            const startMarker = L.circleMarker([startPoint.lat, startPoint.lng], {
                radius: 10,
                fillColor: '#2563eb',
                color: '#ffffff',
                weight: 2.5,
                opacity: 1,
                fillOpacity: 0.95
            }).addTo(leafletMap).bindPopup(`<strong>🚩 ${isSi ? 'ආරම්භක ස්ථානය' : 'Start Point'}: ${startLocName}</strong>`);
            routeMarkers.push(startMarker);

            const latlngs = [[startPoint.lat, startPoint.lng]];
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
            leafletMap.fitBounds(routePolyline.getBounds(), { padding: [25, 25] });

            const osrmWaypoints = [
                `${startPoint.lng},${startPoint.lat}`,
                ...tripStops.map(s => `${s.lng},${s.lat}`)
            ].join(';');

            const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${osrmWaypoints}?overview=full&geometries=geojson`;
            fetch(osrmUrl)
                .then(res => res.json())
                .then(data => {
                    if (data && data.code === 'Ok' && data.routes && data.routes[0]) {
                        if (routePolyline) {
                            leafletMap.removeLayer(routePolyline);
                        }
                        routePolyline = L.geoJSON(data.routes[0].geometry, {
                            style: {
                                color: '#059669',
                                weight: 5,
                                opacity: 0.85
                            }
                        }).addTo(leafletMap);
                        leafletMap.fitBounds(routePolyline.getBounds(), { padding: [25, 25] });
                    }
                })
                .catch(err => {
                    console.log('OSRM road geometry query note:', err.message);
                });
        }

        function clearMapRoute() {
            routeMarkers.forEach(m => leafletMap.removeLayer(m));
            routeMarkers = [];
            if (routePolyline) {
                leafletMap.removeLayer(routePolyline);
                routePolyline = null;
            }
        }

        // Bus Stops Map Modal Picker (Leaflet Picker Map)
        const busStopsModalEl = document.getElementById('transitBusStopsMapModal');
        if (busStopsModalEl) {
            busStopsModalEl.addEventListener('shown.bs.modal', function () {
                if (!transitPickerMap) {
                    transitPickerMap = L.map('busStopsPickerMap').setView([7.8500, 80.7800], 10);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(transitPickerMap);

                    busStopsList.forEach(stop => {
                        const isHub = !!stop.is_hub;
                        const marker = L.circleMarker([stop.latitude, stop.longitude], {
                            radius: isHub ? 9 : 6,
                            fillColor: isHub ? '#dc2626' : '#0284c7',
                            color: '#ffffff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.95
                        }).addTo(transitPickerMap);

                        const stopName = isSi ? (stop.name_si || stop.name_en) : (stop.name_en || stop.name_si);
                        const popupContent = `
                            <div class="p-1" style="min-width: 170px;">
                                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.85rem;">
                                    <i class="bi bi-bus-front text-danger me-1"></i> ${stopName}
                                </h6>
                                <div class="mb-2">
                                    <span class="badge ${isHub ? 'bg-danger' : 'bg-primary'} rounded-pill" style="font-size: 0.65rem;">
                                        ${isHub ? '★ Transit Interchange Hub' : 'Bus Stop'}
                                    </span>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger w-100 py-1 select-start-bus-stop-btn" data-id="${stop.id}" style="font-size: 0.72rem;">
                                    🚩 ${isSi ? 'ආරම්භක නැවතුම ලෙස තෝරන්න' : 'Select as Start Terminal'}
                                </button>
                            </div>
                        `;
                        marker.bindPopup(popupContent);
                        transitPickerMarkers.push(marker);
                    });

                    document.getElementById('busStopsPickerMap').addEventListener('click', function (e) {
                        const btn = e.target.closest('.select-start-bus-stop-btn');
                        if (btn) {
                            const stopId = btn.getAttribute('data-id');
                            const selectEl = document.getElementById('cfgBusStartStop');
                            if (selectEl) {
                                selectEl.value = stopId;
                            }
                            selectedAlternatives = {};
                            const bsModal = bootstrap.Modal.getInstance(busStopsModalEl);
                            if (bsModal) bsModal.hide();
                            renderTimeline();
                        }
                    });
                }
                transitPickerMap.invalidateSize();
            });
        }

        // Save Trip to My Account Button
        document.getElementById('btnSaveTrip').addEventListener('click', function () {
            if (tripStops.length === 0) {
                alert(isSi ? 'කරුණාකර චාරිකාව සුරැකීමට පෙර අවම වශයෙන් එක් ගමනාන්තයක් හෝ එකතු කරන්න!' : 'Please add at least one destination to save a trip plan!');
                return;
            }

            const tripTitle = prompt(isSi ? 'මෙම චාරිකා සැලැස්ම සඳහා නමක් ලබාදෙන්න:' : 'Enter a title for this trip plan:', isSi ? 'ඇළහැර එක්දින සංචාරය' : 'Elahera 1-Day Highlights');
            if (!tripTitle) return;

            const mode = document.getElementById('cfgTransportMode').value;
            let startLoc = document.getElementById('cfgStartLocation').value;
            if (mode === 'Public Bus') {
                const sel = document.getElementById('cfgBusStartStop');
                if (sel && sel.options[sel.selectedIndex]) {
                    startLoc = sel.options[sel.selectedIndex].text.replace('★ (Hub)', '').trim();
                }
            }

            const payload = {
                title: tripTitle,
                trip_date: document.getElementById('cfgDate').value,
                start_location: startLoc,
                start_time: document.getElementById('cfgStartTime').value,
                end_time: document.getElementById('cfgEndTime').value,
                transport_mode: mode,
                meal_break: document.getElementById('cfgMealBreak').checked,
                stops: tripStops,
                total_duration_minutes: currentTotalUsedMinutes || 600,
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
                alert(isSi ? 'මුද්‍රණය කිරීමට පෙර ගමනාන්ත එකතු කරන්න.' : 'Add destinations first to print your itinerary.');
                return;
            }
            const date = document.getElementById('cfgDate').value;
            const mode = document.getElementById('cfgTransportMode').value;
            let startLoc = document.getElementById('cfgStartLocation').value;
            if (mode === 'Public Bus') {
                const sel = document.getElementById('cfgBusStartStop');
                if (sel && sel.options[sel.selectedIndex]) {
                    startLoc = sel.options[sel.selectedIndex].text.replace('★ (Hub)', '').trim();
                }
            }
            const startTime = document.getElementById('cfgStartTime').value;
            const stopsJson = encodeURIComponent(JSON.stringify(tripStops));

            const printUrl = `{{ route("planner.print") }}?date=${date}&start_location=${encodeURIComponent(startLoc)}&start_time=${startTime}&transport_mode=${encodeURIComponent(mode)}&stops=${stopsJson}`;
            window.open(printUrl, '_blank');
        });
    });
</script>
@endpush
