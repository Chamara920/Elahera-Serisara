@extends('layouts.app')

@section('title', 'Interactive GPS Map - ' . __('messages.brand_name'))

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@section('content')

    <div class="container-fluid px-0">
        <div class="row g-0" style="height: calc(100vh - 130px); min-height: 550px;">
            
            <!-- Left Sidebar (Destinations List & Search) -->
            <div class="col-md-4 col-lg-3 bg-white border-end d-flex flex-column h-100 shadow-sm" style="z-index: 1000;">
                <div class="p-3 border-bottom bg-light">
                    <h5 class="fw-bold mb-2 text-dark">
                        <i class="bi bi-map-fill text-success me-1"></i> {{ __('messages.interactive_map') }}
                    </h5>
                    <!-- Search input -->
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="mapSearchInput" class="form-control border-start-0 bg-white" placeholder="{{ __('messages.search_placeholder') }}">
                    </div>
                    <!-- Category dropdown filter -->
                    <select id="mapCategorySelect" class="form-select form-select-sm">
                        <option value="all">{{ app()->getLocale() === 'si' ? 'සියලුම වර්ගීකරණ' : 'All Categories' }}</option>
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
                                    <div class="text-muted small" style="font-size: 0.73rem;">{{ $dest->distance_km }} km • {{ $dest->stay_duration_minutes }}{{ app()->getLocale() === 'si' ? ' විනාඩි' : 'm stay' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Leaflet Map Container -->
            <div class="col-md-8 col-lg-9 h-100 position-relative overflow-hidden">
                <div id="fullLeafletMap" class="w-100 h-100"></div>

                <!-- Floating Bottom Selected Destination Card (Does not cover or get cut off by screen boundaries) -->
                <div id="mapBottomDetailCard" class="position-absolute bottom-0 start-50 translate-middle-x mb-3 px-3 w-100 d-none" style="max-width: 680px; z-index: 1050; pointer-events: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="pointer-events: auto; background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(14px); border: 1.5px solid rgba(6, 78, 59, 0.18) !important; box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.25) !important;">
                        <div class="card-body p-3 p-md-3.5 position-relative">
                            <!-- Close Button -->
                            <button type="button" id="closeBottomCardBtn" class="btn-close position-absolute top-0 end-0 m-2.5 shadow-none" aria-label="Close" style="font-size: 0.8rem;"></button>
                            
                            <div class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center">
                                <!-- Thumbnail Image with Badge -->
                                <div class="position-relative flex-shrink-0" style="width: 100%; max-width: 135px; height: 105px;">
                                    <img id="bottomCardImg" src="" class="w-100 h-100 object-fit-cover rounded-3 shadow-sm" alt="">
                                    <span id="bottomCardCategory" class="badge bg-success position-absolute top-0 start-0 m-1.5 shadow-sm fw-bold" style="font-size: 0.65rem;"></span>
                                </div>

                                <!-- Info Details -->
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex align-items-center justify-content-between pe-4 mb-1">
                                        <h6 id="bottomCardTitle" class="fw-bold text-dark mb-0 text-truncate" style="font-size: 1.05rem;"></h6>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5 text-muted small" style="font-size: 0.78rem;">
                                        <span id="bottomCardDistance"><i class="bi bi-geo-alt-fill text-danger me-1"></i><span class="val"></span></span>
                                        <span>•</span>
                                        <span id="bottomCardStay"><i class="bi bi-clock-history text-primary me-1"></i><span class="val"></span></span>
                                    </div>

                                    <p id="bottomCardDesc" class="text-secondary small mb-2" style="font-size: 0.82rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"></p>

                                    <!-- Actions -->
                                    <div class="d-flex flex-wrap gap-2 pt-1">
                                        <a id="bottomCardDetailsBtn" href="#" class="btn btn-sm btn-primary-custom rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.8rem;">
                                            <i class="bi bi-info-circle me-1"></i> {{ __('messages.view_details') }}
                                        </a>
                                        <a id="bottomCardGmapBtn" href="#" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.8rem;">
                                            <i class="bi bi-cursor-fill me-1"></i> Google Maps Navigation
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Leaflet Map centered on Elahera / Bakamuna hub
        const map = L.map('fullLeafletMap').setView([7.9300, 80.8200], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const destinations = @json($destinations);
        const markers = {};
        const isSi = '{{ app()->getLocale() }}' === 'si';

        const bottomCard = document.getElementById('mapBottomDetailCard');
        const closeBottomCardBtn = document.getElementById('closeBottomCardBtn');
        const bottomCardImg = document.getElementById('bottomCardImg');
        const bottomCardCategory = document.getElementById('bottomCardCategory');
        const bottomCardTitle = document.getElementById('bottomCardTitle');
        const bottomCardDistanceVal = document.querySelector('#bottomCardDistance .val');
        const bottomCardStayVal = document.querySelector('#bottomCardStay .val');
        const bottomCardDesc = document.getElementById('bottomCardDesc');
        const bottomCardDetailsBtn = document.getElementById('bottomCardDetailsBtn');
        const bottomCardGmapBtn = document.getElementById('bottomCardGmapBtn');

        // Custom green pin icon
        function getPinIcon(isActive = false) {
            const bg = isActive ? '#047857' : '#059669';
            const size = isActive ? 36 : 30;
            const border = isActive ? '3px solid #fef08a' : '2px solid #ffffff';
            const shadow = isActive ? '0 6px 14px rgba(0,0,0,0.45)' : '0 4px 10px rgba(0,0,0,0.3)';

            return L.divIcon({
                className: 'custom-div-pin',
                html: `<div style="background-color: ${bg}; width: ${size}px; height: ${size}px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; border: ${border}; box-shadow: ${shadow}; transition: all 0.2s;"><i class="bi bi-geo-alt-fill" style="transform: rotate(45deg); color: #ffffff; font-size: ${size > 30 ? 16 : 14}px;"></i></div>`,
                iconSize: [size, size],
                iconAnchor: [size / 2, size],
                tooltipAnchor: [0, -size]
            });
        }

        let activeDestId = null;

        function selectDestination(dest) {
            if (!dest) return;

            // Reset previously active marker
            if (activeDestId && markers[activeDestId]) {
                markers[activeDestId].setIcon(getPinIcon(false));
            }
            activeDestId = dest.id;
            if (markers[dest.id]) {
                markers[dest.id].setIcon(getPinIcon(true));
            }

            // Populate the bottom detail card
            const title = (isSi && dest.title_si) ? dest.title_si : dest.title_en;
            const category = (isSi && dest.category_si) ? dest.category_si : (dest.category || 'Spot');
            const desc = (isSi && dest.short_description_si) ? dest.short_description_si : (dest.short_description_en || '');

            bottomCardImg.src = dest.featured_image_url || '/images/destinations/anicut.svg';
            bottomCardImg.alt = title;
            bottomCardCategory.textContent = category;
            bottomCardTitle.textContent = title;
            bottomCardDistanceVal.textContent = dest.distance_km ? `${dest.distance_km} km` : (isSi ? 'ඇළහැර කලාපය' : 'Elahera Corridor');
            bottomCardStayVal.textContent = dest.stay_duration_minutes ? `${dest.stay_duration_minutes} ${isSi ? 'විනාඩි' : 'mins'}` : (isSi ? 'නිර්දේශිත' : 'Recommended');
            bottomCardDesc.textContent = desc;

            bottomCardDetailsBtn.href = '/destinations/' + (dest.slug || dest.id);
            bottomCardGmapBtn.href = `https://www.google.com/maps/dir/?api=1&destination=${dest.latitude},${dest.longitude}`;

            // Reveal the bottom card completely
            bottomCard.classList.remove('d-none');

            // Pan map so marker is positioned nicely in the upper portion above the bottom card
            const offsetLat = parseFloat(dest.latitude) - 0.007;
            map.flyTo([offsetLat, parseFloat(dest.longitude)], 14, { duration: 0.8 });

            // Highlight sidebar item
            document.querySelectorAll('.map-item-card').forEach(c => c.classList.remove('border-success', 'bg-white', 'shadow-sm'));
            const activeSidebarCard = document.querySelector(`.map-item-card[data-id="${dest.id}"]`);
            if (activeSidebarCard) {
                activeSidebarCard.classList.add('border-success', 'bg-white', 'shadow-sm');
                activeSidebarCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        // Create markers with tooltips and click-to-bottom-card interaction
        destinations.forEach(dest => {
            const title = (isSi && dest.title_si) ? dest.title_si : dest.title_en;
            const marker = L.marker([dest.latitude, dest.longitude], { icon: getPinIcon(false) }).addTo(map);

            marker.bindTooltip(title, {
                direction: 'top',
                offset: [0, -28],
                className: 'fw-bold px-2 py-1 shadow-sm'
            });

            marker.on('click', function () {
                selectDestination(dest);
            });

            markers[dest.id] = marker;
        });

        // Close bottom card event
        if (closeBottomCardBtn) {
            closeBottomCardBtn.addEventListener('click', function () {
                bottomCard.classList.add('d-none');
                if (activeDestId && markers[activeDestId]) {
                    markers[activeDestId].setIcon(getPinIcon(false));
                    activeDestId = null;
                }
            });
        }

        // Map background click closes bottom card
        map.on('click', function () {
            bottomCard.classList.add('d-none');
            if (activeDestId && markers[activeDestId]) {
                markers[activeDestId].setIcon(getPinIcon(false));
                activeDestId = null;
            }
        });

        // Click sidebar card to select destination
        document.querySelectorAll('.map-item-card').forEach(card => {
            card.addEventListener('click', function () {
                const id = parseInt(this.getAttribute('data-id'), 10);
                const dest = destinations.find(d => d.id === id);
                if (dest) {
                    selectDestination(dest);
                }
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
