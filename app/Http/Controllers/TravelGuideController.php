<?php

namespace App\Http\Controllers;

use App\Models\Destination;

/**
 * Class TravelGuideController
 *
 * Provides travel guidelines, route planning hints, weather and etiquette tips for Elahera.
 */
class TravelGuideController extends Controller
{
    public function index()
    {
        $destinations = Destination::where('is_active', true)->take(6)->get();

        return view('guide.index', compact('destinations'));
    }
}
