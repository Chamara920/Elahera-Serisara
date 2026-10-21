@extends('layouts.app')

@section('title', 'Emergency Contacts & Helplines - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="mb-4">
            <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill mb-2">24/7 Assistance</span>
            <h1 class="display-6 fw-bold text-dark mb-1">Emergency Contacts & Medical Helplines</h1>
            <p class="text-secondary small mb-0">Official phone numbers for hospitals, police, wildlife assistance, and ambulance services in the Elahera / Bakamuna region.</p>
        </div>

        <!-- Quick 24/7 National Emergency Numbers -->
        <div class="row g-3 mb-5">
            <div class="col-md-3">
                <div class="card card-custom border-danger border-2 p-3 text-center bg-danger bg-opacity-10 shadow-sm">
                    <div class="fs-1 fw-bold text-danger">1990</div>
                    <div class="fw-bold text-dark small">Suwa Seriya Ambulance</div>
                    <div class="small text-muted" style="font-size: 0.75rem;">Free Medical Emergency 24/7</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom border-primary border-2 p-3 text-center bg-primary bg-opacity-10 shadow-sm">
                    <div class="fs-1 fw-bold text-primary">119</div>
                    <div class="fw-bold text-dark small">Police Emergency</div>
                    <div class="small text-muted" style="font-size: 0.75rem;">Islandwide Police Hotline</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom border-warning border-2 p-3 text-center bg-warning bg-opacity-10 shadow-sm">
                    <div class="fs-1 fw-bold text-warning-emphasis">1992</div>
                    <div class="fw-bold text-dark small">Wildlife Rescue (DWC)</div>
                    <div class="small text-muted" style="font-size: 0.75rem;">Department of Wildlife Conservation</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom border-success border-2 p-3 text-center bg-success bg-opacity-10 shadow-sm">
                    <div class="fs-1 fw-bold text-success">117</div>
                    <div class="fw-bold text-dark small">Disaster Management</div>
                    <div class="small text-muted" style="font-size: 0.75rem;">Flood / Flash Rain Alerts</div>
                </div>
            </div>
        </div>

        <!-- Regional Directory Table -->
        <div class="card card-custom border-0 shadow-sm p-4 p-md-5">
            <h4 class="fw-bold text-dark mb-3">Local Regional Contacts Directory</h4>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Institution / Service</th>
                            <th>Category</th>
                            <th>Primary Contact</th>
                            <th>Alternative Helpline</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td class="fw-bold text-dark">
                                    <i class="bi bi-building me-1 text-secondary"></i> {{ $contact->getLocalizedName() }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $contact->category }}</span>
                                </td>
                                <td class="fw-bold text-success">
                                    <a href="tel:{{ $contact->phone_primary }}" class="text-success text-decoration-none">
                                        <i class="bi bi-telephone-fill me-1"></i> {{ $contact->phone_primary }}
                                    </a>
                                </td>
                                <td class="small text-muted">
                                    {{ $contact->phone_secondary ?: '-' }}
                                </td>
                                <td class="small text-secondary">
                                    {{ $contact->location ?: 'Bakamuna / Elahera' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No emergency contacts registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
