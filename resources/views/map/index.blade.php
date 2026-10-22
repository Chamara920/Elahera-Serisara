@extends('layouts.app')

@section('title', 'Interactive GPS Map - ' . __('messages.brand_name'))

@section('content')

    <div class="container-fluid px-0">
        <div class="row g-0" style="height: calc(100vh - 130px); min-height: 550px;">
            
            <!-- Left Sidebar (Destinations List & Search) -->
            <div class="col-md-4 col-lg-3 bg-white border-end d-flex flex-column h-100 shadow-sm" style="z-index: 1000;">
                <div class="p-3 border-bottom bg-light">
                    <h5 class="fw-bold mb-2 text-dark">
                        <i class="bi bi-map-fill text-success me-1"></i> Interactive Map
                    </h5>
                    <!-- Search input -->
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="mapSearchInput" class="form-control border-start-0 bg-white" placeholder="Search spot name...">
                    </div>
                    <!-- Category dropdown filter -->
                    <select id="mapCategorySelect" class="form-select form-select-sm">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Destination List Items -->
                <div class="flex-grow-1 overflow-y-auto p-2 d-flex flex-column gap-2" id="mapSidebarList">
                    @foreach($destinations as $dest)
                        <div class="p-2.5 rounded-3 bg-light border map-item-card cursor-pointer" 
                            data-id="{{ $dest->id }}"
                            data-title="{{ $dest->getLocalizedTitle() }}"
                            data-cat="{{ $dest->category }}"
                            data-lat="{{ $dest->latitude }}"
                            data-lng="{{ $dest->longitude }}"
                            style="cursor: pointer; transition: all 0.2s;">
                            <div class="d-flex gap-2.5 align-items-center">
                                <img src="{{ $dest->getFeaturedImageUrl() }}" class="rounded-2 object-fit-cover flex-shrink-0" width="54" height="54" alt="{{ $dest->getLocalizedTitle() }}">
                                <div class="overflow-hidden">
                                    <span class="badge bg-white text-success border small mb-1" style="font-size: 0.68rem;">{{ $dest->getLocalizedCategory() }}</span>
                                    <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.85rem;">{{ $dest->getLocalizedTitle() }}</h6>
                                    <div class="text-muted small" style="font-size: 0.73rem;">{{ $dest->distance_km }} km • {{ $dest->stay_duration_minutes }}m stay</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Leaflet Map Container -->
            <div class="col-md-8 col-lg-9 h-100 position-relative">
                <div id="fullLeafletMap" class="w-100 h-100"></div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Leaflet Map centered on Elahera / Bakamuna hub
        const map = L.map('fullLeafletMap').setView([7.9300, 80.8200], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const destinations = @json($destinations);
        const markers = {};

        // Custom green pin icon
        const customPinIcon = L.divIcon({
            className: 'custom-div-pin',
            html: `<div style="background-color: #059669; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; border: 2px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.3);"><i class="bi bi-geo-alt-fill" style="transform: rotate(45deg); color: #ffffff; font-size: 14px;"></i></div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -32]
        });

        destinations.forEach(dest => {
            const popupContent = `
                <div style="max-width: 260px;">
                    <img src="${dest.featured_image_url || '/images/destinations/anicut.svg'}" style="width: 100%; height: 110px; object-fit: cover; border-radius: 6px; margin-bottom: 8px;">
                    <span style="font-size: 11px; background: #ecfdf5; color: #059669; padding: 2px 8px; border-radius: 12px; font-weight: bold;">${dest.category}</span>
                    <h6 style="font-weight: bold; margin: 6px 0 4px 0; color: #0f172a;">${dest.title_en}</h6>
                    <p style="font-size: 12px; color: #64748b; margin-bottom: 10px;">${dest.short_description_en ? dest.short_description_en.substring(0, 90) + '...' : ''}</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${dest.latitude},${dest.longitude}" target="_blank" class="btn btn-success btn-sm w-100 fw-bold text-white text-decoration-none d-block text-center py-1.5" style="border-radius: 6px; font-size: 12px;">
                        📍 Google Maps මඟින් යන්න
                    </a>
                </div>
            `;

            const marker = L.marker([dest.latitude, dest.longitude], { icon: customPinIcon }).addTo(map)
                .bindPopup(popupContent);

            markers[dest.id] = marker;
        });

        // Click sidebar card to zoom to marker
        document.querySelectorAll('.map-item-card').forEach(card => {
            card.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const lat = parseFloat(this.getAttribute('data-lat'));
                const lng = parseFloat(this.getAttribute('data-lng'));

                map.flyTo([lat, lng], 14, { duration: 1.2 });
                if (markers[id]) {
                    markers[id].openPopup();
                }

                document.querySelectorAll('.map-item-card').forEach(c => c.classList.remove('border-success', 'bg-white'));
                this.classList.add('border-success', 'bg-white');
            });
        });

        // Sidebar Search & Filter
        const searchInput = document.getElementById('mapSearchInput');
        const catSelect = document.getElementById('mapCategorySelect');

        function filterSidebar() {
            const query = searchInput.value.toLowerCase();
            const cat = catSelect.value;

            document.querySelectorAll('.map-item-card').forEach(card => {
                const title = card.getAttribute('data-title').toLowerCase();
                const cardCat = card.getAttribute('data-cat');
                const matchesText = title.includes(query);
                const matchesCat = (cat === 'all' || cardCat === cat);

                if (matchesText && matchesCat) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterSidebar);
        catSelect.addEventListener('change', filterSidebar);
    });
</script>
@endpush
