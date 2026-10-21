@extends('layouts.app')

@section('title', 'Public Transport & Bus Timetable - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <!-- Page Title & Header -->
        <div class="mb-4">
            <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill mb-2">Transit Schedule</span>
            <h1 class="display-6 fw-bold text-dark mb-1">Elahera & Bakamuna Bus Timetable</h1>
            <p class="text-secondary small mb-0">Direct routes, connecting halts, and master schedules across the Mahaweli irrigation corridor.</p>
        </div>

        <!-- 1. Mandatory Disclaimer Notice -->
        <div class="alert alert-warning border-warning-subtle shadow-sm p-3 mb-4 d-flex align-items-start gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning flex-shrink-0 mt-1"></i>
            <div>
                <h6 class="fw-bold mb-1 text-warning-emphasis">විශේෂ නිවේදන සටහන (Disclaimer Notice)</h6>
                <p class="small text-secondary mb-0 leading-relaxed">
                    {{ __('messages.bus_disclaimer') }}
                </p>
            </div>
        </div>


        <!-- 2. Top Section: Route Finder Search Engine (From -> To) -->
        <div class="card card-custom border-0 shadow-sm p-4 mb-5" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-top: 4px solid var(--primary-emerald) !important;">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-search text-success fs-5"></i>
                <h4 class="fw-bold text-dark mb-0">Bus Route Finder</h4>
            </div>

            <form action="{{ route('transport.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Departure Point (From)
                        </label>
                        <select name="from" class="form-select bg-white shadow-sm" required>
                            <option value="">Select departure halt...</option>
                            @foreach($haltsList as $h)
                                <option value="{{ $h }}" {{ $fromStop == $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="bi bi-flag-fill text-success me-1"></i> Destination Point (To)
                        </label>
                        <select name="to" class="form-select bg-white shadow-sm" required>
                            <option value="">Select destination halt...</option>
                            @foreach($haltsList as $h)
                                <option value="{{ $h }}" {{ $toStop == $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary-custom w-100 fw-bold shadow-sm">
                            <i class="bi bi-binoculars me-1"></i> Search Buses
                        </button>
                    </div>
                </div>
            </form>

            <!-- Search Results if query performed -->
            @if($fromStop && $toStop)
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold text-dark mb-3">
                        Results from <span class="text-success">{{ $fromStop }}</span> to <span class="text-success">{{ $toStop }}</span>
                    </h6>

                    @if($foundRoutes->count() > 0)
                        <div class="row g-3">
                            @foreach($foundRoutes as $r)
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3 shadow-sm h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-danger fs-6 fw-bold">Route {{ $r->route_number }}</span>
                                            <span class="badge bg-secondary">{{ $r->service_type }}</span>
                                        </div>
                                        <div class="fw-bold fs-6 text-dark mb-1">{{ $r->origin }} &rarr; {{ $r->destination }}</div>
                                        <div class="small text-muted mb-2">
                                            <i class="bi bi-clock me-1"></i> Departs origin at: <strong>{{ $r->origin_departure_time }}</strong>
                                        </div>
                                        <div class="small text-secondary mb-2">
                                            <i class="bi bi-signpost-split me-1"></i> Via: {{ $r->via_cities ?: 'Direct route' }}
                                        </div>

                                        <!-- Halts on this route -->
                                        <div class="p-2 bg-light rounded text-muted small" style="font-size: 0.76rem;">
                                            <strong>Key Stops:</strong> 
                                            @foreach($r->halts as $halt)
                                                <span class="{{ stripos($halt->stop_name, $fromStop) !== false || stripos($halt->stop_name, $toStop) !== false ? 'text-success fw-bold' : '' }}">
                                                    {{ $halt->stop_name }} ({{ $halt->departure_time ?: $halt->arrival_time }})
                                                </span>
                                                @if(!$loop->last) &bull; @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 bg-white rounded border text-center text-muted small">
                            No direct scheduled bus found for this halt pair. Try selecting Bakamuna Town Terminal as your transit hub.
                        </div>
                    @endif
                </div>
            @endif
        </div>


        <!-- 3. Stop / Destination Specific Filter -->
        <div class="card card-custom border-0 shadow-sm p-4 mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="bi bi-filter-circle text-primary me-2"></i> Stop & Attraction Timetable Filter
                    </h4>
                    <p class="small text-muted mb-0">Select a specific bus stop or tourist destination to see chronological bus arrivals and departures.</p>
                </div>
            </div>

            <form action="{{ route('transport.index') }}" method="GET" class="mb-4">
                <div class="row g-2 align-items-center">
                    <div class="col-md-9">
                        <select name="filter_stop" class="form-select bg-light" onchange="this.form.submit()">
                            <option value="">-- Choose Bus Stop or Tourist Destination --</option>
                            <optgroup label="Major Bus Halts">
                                @foreach($haltsList as $h)
                                    <option value="{{ $h }}" {{ $selectedStop == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Tourist Destinations">
                                @foreach($destinations as $d)
                                    <option value="{{ $d->title_en }}" {{ $selectedStop == $d->title_en ? 'selected' : '' }}>
                                        📍 {{ $d->title_en }} ({{ $d->nearest_bus_stop }})
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-success w-100 fw-bold">
                            View Halts Schedule
                        </button>
                    </div>
                </div>
            </form>

            @if($selectedStop)
                <h6 class="fw-bold text-success mb-3">
                    Scheduled Buses passing through: <span class="text-dark">{{ $selectedStop }}</span>
                </h6>

                @if($stopTimetable->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Estimated Time</th>
                                    <th>Route #</th>
                                    <th>Route Path</th>
                                    <th>Service Type</th>
                                    <th>Halt Specifics</th>
                                    <th>Tourist Connection</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stopTimetable as $halt)
                                    <tr>
                                        <td class="fw-bold text-success fs-6">
                                            <i class="bi bi-clock me-1"></i> {{ $halt->departure_time ?: $halt->arrival_time }}
                                        </td>
                                        <td><span class="badge bg-danger fs-7">Route {{ $halt->busRoute->route_number }}</span></td>
                                        <td>
                                            <strong>{{ $halt->busRoute->origin }} &rarr; {{ $halt->busRoute->destination }}</strong>
                                            <div class="small text-muted">{{ $halt->busRoute->via_cities }}</div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $halt->busRoute->service_type }}</span></td>
                                        <td class="small">{{ $halt->stop_name }}</td>
                                        <td class="small">
                                            @if($halt->destination)
                                                <a href="{{ route('destinations.show', $halt->destination->slug) }}" class="text-decoration-none text-success fw-bold">
                                                    <i class="bi bi-geo-alt-fill"></i> {{ $halt->destination->title_en }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ $halt->nearest_spot_name ?: 'Hub transit' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-light border small text-muted text-center">
                        No halts currently registered specifically for "{{ $selectedStop }}". Check the master Bakamuna timetable below.
                    </div>
                @endif
            @endif
        </div>


        <!-- 4. Bakamuna Hub Master Timetable Table (Chronological 05:30 AM to Night) -->
        <div class="card card-custom border-0 shadow-sm p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="bi bi-clock-history text-success me-2"></i> Bakamuna Hub Master Bus Timetable
                    </h3>
                    <p class="small text-muted mb-0">Daily regular departures arranged chronologically from dawn to late evening.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle border">
                    <thead class="table-success">
                        <tr>
                            <th style="width: 130px;">Time</th>
                            <th style="width: 100px;">Route #</th>
                            <th>Origin &rarr; Destination</th>
                            <th>Via (Key Junctions)</th>
                            <th style="width: 130px;">Service</th>
                            <th>Admin Notice / Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allRoutes as $route)
                            <tr>
                                <td class="fw-bold text-success fs-6">
                                    <i class="bi bi-clock me-1"></i> {{ $route->origin_departure_time }}
                                </td>
                                <td>
                                    <span class="badge bg-danger fs-7 fw-bold">{{ $route->route_number }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-6">{{ $route->origin }} &rarr; {{ $route->destination }}</div>
                                    <div class="small text-muted">{{ $route->halts->count() }} registered halts</div>
                                </td>
                                <td class="small text-secondary">
                                    {{ $route->via_cities ?: 'Direct corridor' }}
                                </td>
                                <td>
                                    <span class="badge {{ $route->service_type === 'Express' ? 'bg-danger' : ($route->service_type === 'Semi-Luxury' ? 'bg-warning text-dark' : 'bg-primary') }}">
                                        {{ $route->service_type }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ $route->remarks ?: 'Daily normal operation' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No bus routes registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 p-3 bg-light rounded text-muted small border">
                <i class="bi bi-info-circle me-1 text-success"></i>
                <strong>Administrator Control:</strong> System administrators can update departure times, add temporary holiday notices, or register new halts directly via the Filament Admin Panel.
            </div>
        </div>

    </div>

@endsection
