@extends('layouts.app')

@section('title', 'My Saved Trips - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="badge bg-success text-white px-3 py-1.5 rounded-pill mb-2">Member Dashboard</span>
                <h1 class="display-6 fw-bold text-dark mb-1">My Saved Trips</h1>
                <p class="text-secondary small mb-0">Custom itineraries generated using Smart Trip Planner and stored in your account.</p>
            </div>
            <div>
                <a href="{{ route('planner.index') }}" class="btn btn-primary-custom fw-bold shadow-sm px-4">
                    <i class="bi bi-plus-circle me-1"></i> Plan a New Trip
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($trips as $trip)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-custom h-100 border-0 shadow-sm p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-light text-success border small fw-semibold">
                                <i class="bi bi-car-front me-1"></i> {{ $trip->transport_mode }}
                            </span>
                            <span class="small text-muted" style="font-size: 0.75rem;">
                                {{ $trip->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <h5 class="fw-bold text-dark mb-2">{{ $trip->title }}</h5>

                        <div class="small text-muted mb-3">
                            <div><i class="bi bi-calendar3 me-1 text-success"></i> Date: <strong>{{ $trip->trip_date ? $trip->trip_date->format('M d, Y') : 'Open Date' }}</strong></div>
                            <div><i class="bi bi-geo-alt me-1 text-danger"></i> From: <strong>{{ $trip->start_location }}</strong> ({{ $trip->start_time }})</div>
                        </div>

                        <!-- Stops Preview -->
                        <div class="p-2.5 bg-light rounded-3 mb-3 flex-grow-1">
                            <div class="small fw-bold text-secondary mb-1">Stops Included:</div>
                            <ul class="list-unstyled small mb-0 ps-1">
                                @if(is_array($trip->stops_data))
                                    @foreach($trip->stops_data as $i => $stop)
                                        <li class="text-truncate mb-1">
                                            <span class="badge bg-success rounded-circle p-1 me-1" style="font-size: 0.6rem;">{{ $i + 1 }}</span>
                                            {{ $stop['title'] ?? 'Stop ' . ($i + 1) }} ({{ $stop['stay'] ?? 60 }}m)
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="d-flex gap-2 pt-2 border-top">
                            @if($trip->google_maps_url)
                                <a href="{{ $trip->google_maps_url }}" target="_blank" class="btn btn-outline-success btn-sm w-100 fw-bold">
                                    <i class="bi bi-map me-1"></i> Google Maps
                                </a>
                            @endif

                            <form action="{{ route('user.delete-trip', $trip->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this saved trip plan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete Saved Trip">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-bookmark-x fs-1 text-muted d-block mb-2"></i>
                    <h5 class="fw-bold">No saved trips yet</h5>
                    <p class="small text-muted mb-3">Use the Smart Trip Planner to craft and save your 1-day Elahera adventure.</p>
                    <a href="{{ route('planner.index') }}" class="btn btn-success btn-sm px-4">Open Trip Planner</a>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $trips->links('pagination::bootstrap-5') }}
        </div>

    </div>

@endsection
