@extends('layouts.app')

@section('title', 'About Us - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="badge bg-success text-white px-3 py-1.5 rounded-pill mb-2">Our Vision & Heritage</span>
                <h1 class="display-5 fw-bold text-dark mb-3">Elahera Sirisara</h1>
                <div class="fs-5 text-success fw-bold text-uppercase mb-3">Discover • Explore • Experience</div>
                <p class="text-secondary leading-relaxed fs-6 mb-3">
                    <strong>Elahera Sirisara</strong> is an independent digital tourism platform dedicated to spotlighting the ancient hydraulic wonders, tranquil rural canals, and rich biodiversity of the Elahera-Bakamuna region in the North Central province of Sri Lanka.
                </p>
                <p class="text-secondary leading-relaxed fs-6 mb-4">
                    From the monumental ancient stone anicut constructed across the Amban Ganga by King Mahasen in the 3rd century AD to the serene elephant sanctuaries of Angamedilla and the scenic vistas of Moragahakanda, our platform equips travelers with real-time bus schedules, interactive maps, and smart day-trip planning tools.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('planner.index') }}" class="btn btn-primary-custom fw-bold shadow-sm px-4">
                        <i class="bi bi-compass me-1"></i> Smart Trip Planner
                    </a>
                    <a href="{{ route('destinations.index') }}" class="btn btn-outline-success fw-bold px-4">
                        Explore Destinations
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card card-custom border-0 shadow overflow-hidden">
                    <img src="{{ asset('images/sliders/slider1.svg') }}" class="w-100 object-fit-cover" style="height: 380px;" alt="Elahera Sirisara">
                </div>
            </div>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-3 shadow-sm border h-100">
                    <i class="bi bi-compass fs-1 text-success mb-2 d-block"></i>
                    <h5 class="fw-bold text-dark">Self-Guided Exploration</h5>
                    <p class="small text-muted mb-0">Calculated drive times, stay durations, and Google Maps GPS waypoints for seamless 1-day tours.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-3 shadow-sm border h-100">
                    <i class="bi bi-bus-front fs-1 text-danger mb-2 d-block"></i>
                    <h5 class="fw-bold text-dark">Verified Bus Schedules</h5>
                    <p class="small text-muted mb-0">Detailed stop-by-stop timetables from early dawn (05:30 AM) to night departing the Bakamuna transit hub.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-3 shadow-sm border h-100">
                    <i class="bi bi-people-fill fs-1 text-primary mb-2 d-block"></i>
                    <h5 class="fw-bold text-dark">Community Driven</h5>
                    <p class="small text-muted mb-0">Moderated community blogs, traveler forums, and firsthand recommendations from local guides.</p>
                </div>
            </div>
        </div>

    </div>

@endsection
