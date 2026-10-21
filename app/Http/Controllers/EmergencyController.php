<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;

/**
 * Class EmergencyController
 *
 * Emergency hotline directory for tourists in Elahera, Bakamuna, and Polonnaruwa district.
 */
class EmergencyController extends Controller
{
    public function index()
    {
        $contacts = EmergencyContact::orderBy('sort_order')->get();
        return view('emergency.index', compact('contacts'));
    }
}
