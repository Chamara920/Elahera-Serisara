@extends('layouts.app')

@section('title', 'Travel Guide & Guidelines - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="mb-5 text-center" style="max-width: 800px; margin: 0 auto;">
            <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill mb-2 fw-bold">Essential Travel Advice</span>
            <h1 class="display-5 fw-bold text-dark mb-2">Elahera Corridor Travel Guide</h1>
            <p class="text-secondary fs-6">Everything you need to know before visiting the historic irrigation marvels, canal trails, and nature zones.</p>
        </div>

        <div class="row g-4 mb-5">
            <!-- Pillar 1: Best Times & Weather -->
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 border-0 shadow-sm">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-inline-flex mb-3" style="width: 52px; height: 52px;">
                        <i class="bi bi-sun-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Climate & Best Seasons</h5>
                    <p class="small text-secondary mb-3 leading-relaxed">
                        The Elahera region enjoys bright tropical weather for most of the year. The prime season for outdoor canal walks and camping is from May to September. Northeast monsoon rains peak between October and January.
                    </p>
                    <ul class="small text-muted ps-3 mb-0">
                        <li><strong>Sunny Months:</strong> May &ndash; September</li>
                        <li><strong>Monsoon Inflow:</strong> October &ndash; January</li>
                        <li><strong>Water Level High:</strong> November &ndash; February</li>
                    </ul>
                </div>
            </div>

            <!-- Pillar 2: Safety & Etiquette -->
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 border-0 shadow-sm">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 d-inline-flex mb-3" style="width: 52px; height: 52px;">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Canal & River Safety</h5>
                    <p class="small text-secondary mb-3 leading-relaxed">
                        The Elahera irrigation canal and Amban River have strong currents when sluice gates are open. Never swim alone or in deep intake culverts. Watch for slippery granite stones near cascades.
                    </p>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>Avoid bathing near sluice discharge tunnels.</li>
                        <li>Watch for wild elephant crossings near forest fringes after 4:00 PM.</li>
                        <li>Carry mosquito / leech protection in forested valleys.</li>
                    </ul>
                </div>
            </div>

            <!-- Pillar 3: Culture & Conservation -->
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 border-0 shadow-sm">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-inline-flex mb-3" style="width: 52px; height: 52px;">
                        <i class="bi bi-tree-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Heritage & Eco Conduct</h5>
                    <p class="small text-secondary mb-3 leading-relaxed">
                        The ancient canal bunds and forest waterways sustain countless farming villages and wildlife species. Help preserve this pristine heritage by leaving zero plastic or trash behind.
                    </p>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>Take all polythene and food wrappers back with you.</li>
                        <li>Dress modestly when visiting temple grounds.</li>
                        <li>Support local family-run produce stalls and home-stays.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contributor Guidelines Box -->
        <div class="card card-custom border-0 shadow-sm p-4 p-md-5 mb-5 bg-light">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="fw-bold text-dark mb-2">Our Community Contributor Guidelines</h3>
                    <p class="text-secondary small mb-3 leading-relaxed">
                        Elahera Sirisara thrives on firsthand insights from passionate travelers and locals. When publishing blog posts or submitting events:
                    </p>
                    <ul class="small text-secondary mb-0">
                        <li>Provide accurate travel timings and realistic stay durations.</li>
                        <li>Mention specific landmarks or bus numbers to help public transit travelers.</li>
                        <li>All photos must be your original photography or royalty-free.</li>
                    </ul>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('blog.create') }}" class="btn btn-primary-custom px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-pencil-square me-1"></i> Start Contributing
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection
