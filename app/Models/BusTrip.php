<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class BusTrip
 *
 * Represents an individual scheduled bus trip / run (e.g., 06:30 AM departure)
 * operating on a specific bus route with sequential stops and service days.
 */
class BusTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_route_id',
        'trip_code',
        'trip_name_en',
        'trip_name_si',
        'direction',
        'service_days',
        'origin_departure_time',
        'origin_departure_minutes',
        'destination_arrival_time',
        'destination_arrival_minutes',
        'is_active',
        'remarks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'origin_departure_minutes' => 'integer',
        'destination_arrival_minutes' => 'integer',
    ];

    /**
     * Get localized trip title.
     */
    public function getLocalizedTripName(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'si' && ! empty($this->trip_name_si)) {
            return $this->trip_name_si;
        }

        return $this->trip_name_en;
    }

    /**
     * Parent bus route.
     */
    public function busRoute(): BelongsTo
    {
        return $this->belongsTo(BusRoute::class);
    }

    /**
     * Sequential stop times for this trip.
     */
    public function stopTimes(): HasMany
    {
        return $this->hasMany(BusStopTime::class)->orderBy('stop_sequence');
    }
}
