<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class BusStop
 *
 * Represents a physical bus halt or regional transit hub with GPS coordinates,
 * multilingual names, and transfer hub flag.
 */
class BusStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_si',
        'code',
        'connecting_routes',
        'latitude',
        'longitude',
        'is_hub',
        'location_description_en',
        'location_description_si',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_hub' => 'boolean',
    ];

    /**
     * Get localized halt name.
     */
    public function getLocalizedName(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'si' && ! empty($this->name_si)) {
            return $this->name_si;
        }

        return $this->name_en;
    }

    /**
     * Get localized location description.
     */
    public function getLocalizedDescription(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'si' && ! empty($this->location_description_si)) {
            return $this->location_description_si;
        }

        return $this->location_description_en;
    }

    /**
     * Associated stop times in bus trips.
     */
    public function stopTimes(): HasMany
    {
        return $this->hasMany(BusStopTime::class);
    }

    /**
     * Tourist destinations nearest to this bus stop.
     */
    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class, 'nearest_bus_stop_id');
    }

    /**
     * Legacy bus route halts that map to this stop.
     */
    public function routeHalts(): HasMany
    {
        return $this->hasMany(BusRouteHalt::class);
    }
}
