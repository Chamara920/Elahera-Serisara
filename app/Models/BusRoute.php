<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class BusRoute
 *
 * Represents bus route information, timetable schedules, and halts in Bakamuna/Elahera hub.
 */
class BusRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_number',
        'origin',
        'destination',
        'service_type', // Normal, Semi-Luxury, Express
        'origin_departure_time',
        'via_cities',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function halts(): HasMany
    {
        return $this->hasMany(BusRouteHalt::class)->orderBy('stop_order');
    }
}
