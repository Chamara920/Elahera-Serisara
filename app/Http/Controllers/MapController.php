<?php

namespace App\Http\Controllers;

use App\Models\Destination;

/**
 * Class MapController
 *
 * Full-screen OpenStreetMap & Leaflet.js interactive explorer with search sidebar.
 */
class MapController extends Controller
{
    public function index()
    {
        $destinations = Destination::where('is_active', true)
            ->orderBy('title_en')
            ->get();

        $categories = Destination::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('map.index', compact('destinations', 'categories'));
    }
}
