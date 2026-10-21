<?php

// Script to generate high quality scenic SVGs for Elahera Sirisara

function createSvgImage($path, $title, $subtitle, $color1 = '#0f5132', $color2 = '#198754', $category = 'ELAHERA SIRISARA') {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="100%" height="100%">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$color1};stop-opacity:1" />
      <stop offset="50%" style="stop-color:{$color2};stop-opacity:1" />
      <stop offset="100%" style="stop-color:#072b1e;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="overlay" x1="0%" y1="100%" x2="0%" y2="0%">
      <stop offset="0%" style="stop-color:#000000;stop-opacity:0.85" />
      <stop offset="50%" style="stop-color:#000000;stop-opacity:0.3" />
      <stop offset="100%" style="stop-color:#000000;stop-opacity:0.1" />
    </linearGradient>
    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
      <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
    </pattern>
  </defs>
  
  <!-- Background gradient -->
  <rect width="100%" height="100%" fill="url(#grad)" />
  
  <!-- Subtle pattern grid -->
  <rect width="100%" height="100%" fill="url(#grid)" />
  
  <!-- Decorative scenic hills & water curves -->
  <path d="M 0 500 Q 300 420 600 480 T 1200 460 L 1200 800 L 0 800 Z" fill="rgba(0,0,0,0.25)" />
  <path d="M 0 580 Q 350 510 700 560 T 1200 530 L 1200 800 L 0 800 Z" fill="rgba(0,0,0,0.35)" />
  <path d="M 0 650 Q 400 600 800 640 T 1200 610 L 1200 800 L 0 800 Z" fill="rgba(13, 110, 253, 0.25)" />
  
  <!-- Dark vignette overlay for text legibility -->
  <rect width="100%" height="100%" fill="url(#overlay)" />
  
  <!-- Content -->
  <g transform="translate(80, 520)">
    <!-- Badge / Category -->
    <rect x="0" y="0" width="220" height="34" rx="17" fill="rgba(255, 193, 7, 0.9)" />
    <text x="110" y="22" font-family="system-ui, -apple-system, sans-serif" font-size="13" font-weight="bold" fill="#000000" text-anchor="middle" letter-spacing="1">{$category}</text>
    
    <!-- Title -->
    <text x="0" y="75" font-family="system-ui, -apple-system, sans-serif" font-size="38" font-weight="800" fill="#ffffff">{$title}</text>
    
    <!-- Subtitle -->
    <text x="0" y="115" font-family="system-ui, -apple-system, sans-serif" font-size="18" fill="rgba(255,255,255,0.85)">{$subtitle}</text>
  </g>
  
  <!-- Top branding watermark -->
  <g transform="translate(80, 80)">
    <circle cx="20" cy="20" r="18" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.4)" stroke-width="2"/>
    <text x="50" y="26" font-family="system-ui, -apple-system, sans-serif" font-size="18" font-weight="700" fill="#ffffff" letter-spacing="1">ELAHERA SIRISARA</text>
  </g>
</svg>
SVG;
    
    file_put_contents($path, $svg);
    // Also save as .jpg for matching URLs
    $jpgPath = preg_replace('/\.svg$/', '.jpg', $path);
    if ($jpgPath !== $path) {
        file_put_contents($jpgPath, $svg);
    }
}

// Generate Sliders
createSvgImage(__DIR__ . '/../public/images/sliders/slider1.svg', 'Elahera Sirisara', 'Discover ancient irrigation wonders & tranquil wilderness', '#064e3b', '#047857', 'EXPLORE ELAHERA');
createSvgImage(__DIR__ . '/../public/images/sliders/slider2.svg', 'Ancient Elahera Anicut', 'Marvel of King Mahasen connecting ancient civilizations', '#1e3a8a', '#0284c7', 'HERITAGE WONDER');
createSvgImage(__DIR__ . '/../public/images/sliders/slider3.svg', 'Angamedilla Wilderness', 'Lush forests, wild elephant corridors & scenic waterways', '#14532d', '#15803d', 'NATURE & WILDLIFE');

// Generate Destinations
createSvgImage(__DIR__ . '/../public/images/destinations/anicut.svg', 'Elahera Ancient Anicut', 'Origin of the historic Yodha Ela canal built by King Mahasen', '#065f46', '#059669', 'HISTORICAL ANICUT');
createSvgImage(__DIR__ . '/../public/images/destinations/bakamuna_park.svg', 'Bakamuna River Eco Park', 'Peaceful Kumbuk riverside retreat in Bakamuna town center', '#0f766e', '#14b8a6', 'ECO RIVER PARK');
createSvgImage(__DIR__ . '/../public/images/destinations/angamedilla.svg', 'Angamedilla National Park', 'Pristine wildlife sanctuary protecting Parakrama Samudra catchment', '#166534', '#22c55e', 'NATIONAL PARK');
createSvgImage(__DIR__ . '/../public/images/destinations/moragahakanda.svg', 'Moragahakanda Dam Viewpoint', 'Panoramic reservoir vistas with Knuckles mountain range backdrop', '#1e40af', '#3b82f6', 'PANORAMA VIEWPOINT');
createSvgImage(__DIR__ . '/../public/images/destinations/heeratiya.svg', 'Heeratiya Oya Cascades', 'Multi-tiered natural rock pools and refreshing forest falls', '#0e7490', '#06b6d4', 'NATURAL CASCADES');
createSvgImage(__DIR__ . '/../public/images/destinations/canal.svg', 'Bowatenna - Elahera Headworks', 'Where mountain waters feed the fertile dry-zone agricultural plains', '#047857', '#10b981', 'CANAL FEADER');

// Gallery images
createSvgImage(__DIR__ . '/../public/images/destinations/anicut1.svg', 'Anicut Stone Masonry', 'Ancient granite blocks engineered centuries ago', '#065f46', '#047857', 'ANCIENT MASONRY');
createSvgImage(__DIR__ . '/../public/images/destinations/anicut2.svg', 'Yodha Ela Canal Flow', 'Water feeding Minneriya and Kantale', '#047857', '#059669', 'CANAL FLOW');
createSvgImage(__DIR__ . '/../public/images/destinations/bakamuna1.svg', 'Giant Kumbuk Trees', 'Shaded banks along Amban River', '#0f766e', '#14b8a6', 'RIVER SHADE');
createSvgImage(__DIR__ . '/../public/images/destinations/bakamuna2.svg', 'Natural River Pools', 'Gentle waters safe for cooling off', '#0f766e', '#0d9488', 'RIVER POOLS');
createSvgImage(__DIR__ . '/../public/images/destinations/anga1.svg', 'Elephant Herds at Dusk', 'Wild elephants feeding in Angamedilla grassland', '#166534', '#15803d', 'ELEPHANT CORRIDOR');
createSvgImage(__DIR__ . '/../public/images/destinations/anga2.svg', 'Ancient Sluice Gates', 'Historic stone gate feeding the reservoir', '#166534', '#22c55e', 'SLUICE GATE');
createSvgImage(__DIR__ . '/../public/images/destinations/mora1.svg', 'Moragahakanda Sunset', 'Golden hour reflection over the reservoir waters', '#1e40af', '#3b82f6', 'SUNSET VISTA');
createSvgImage(__DIR__ . '/../public/images/destinations/mora2.svg', 'Mountain Ridges View', 'Knuckles range rising in the horizon', '#1e3a8a', '#1d4ed8', 'MOUNTAIN RIDGE');
createSvgImage(__DIR__ . '/../public/images/destinations/heera1.svg', 'Upper Cascade Pools', 'Jacuzzi-like natural stone baths', '#0e7490', '#06b6d4', 'FOREST STREAM');
createSvgImage(__DIR__ . '/../public/images/destinations/canal1.svg', 'Paddy Fields & Canal Bund', 'Emerald green fields along the waterway', '#047857', '#10b981', 'RURAL SERENITY');

// Blog Images
createSvgImage(__DIR__ . '/../public/images/blog/blog1.svg', 'Walking King Mahasen’s Steps', 'Exploring the 40km Elahera Yodha Ela canal', '#854d0e', '#ca8a04', 'COMMUNITY BLOG');
createSvgImage(__DIR__ . '/../public/images/blog/blog2.svg', 'Birdwatching at Dawn in Bakamuna', 'Spotting kingfishers and sea eagles', '#065f46', '#10b981', 'BIRD WATCHING');
createSvgImage(__DIR__ . '/../public/images/blog/blog3.svg', 'Solo Camping by Forest Cascades', 'Unwinding under starry skies at Heeratiya', '#1e3a8a', '#2563eb', 'CAMPING GUIDE');

// News Images
createSvgImage(__DIR__ . '/../public/images/news/news1.svg', 'Water Release Schedule Announced', 'Seasonal water management for Yala cultivation', '#1e40af', '#3b82f6', 'LATEST NEWS');
createSvgImage(__DIR__ . '/../public/images/news/event1.svg', 'Elahera Heritage Bicycle Tour', 'Scenic ride along the shaded canal bunds', '#15803d', '#22c55e', 'UPCOMING EVENT');

// Quick Access Cards (Cards 1-5 for Homepage)
createSvgImage(__DIR__ . '/../public/images/cards/card_destinations.svg', 'Destinations', 'Historic sites, waterfalls and scenic viewpoints', '#065f46', '#059669', 'EXPLORE');
createSvgImage(__DIR__ . '/../public/images/cards/card_map.svg', 'Interactive Map', 'Leaflet GPS navigation and Google Maps directions', '#1e40af', '#3b82f6', 'MAP');
createSvgImage(__DIR__ . '/../public/images/cards/card_guide.svg', 'Travel Guide', 'Safety alerts, dress codes & packing checklists', '#854d0e', '#ca8a04', 'GUIDE');
createSvgImage(__DIR__ . '/../public/images/cards/card_transport.svg', 'Public Transport', 'Master bus timetables & route finder from 05:30 AM', '#b91c1c', '#ef4444', 'BUSES');
createSvgImage(__DIR__ . '/../public/images/cards/card_emergency.svg', 'Emergency Info', 'Police, hospitals, wildlife rescue & helplines', '#991b1b', '#dc2626', 'HELP');

echo "All images generated successfully!\n";
