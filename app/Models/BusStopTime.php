<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BusStopTime
 *
 * Represents an arrival and departure schedule entry of a bus trip at a specific bus stop.
 */
class BusStopTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_trip_id',
        'bus_stop_id',
        'stop_sequence',
        'arrival_time',
        'departure_time',
        'arrival_minutes',
        'departure_minutes',
        'remarks',
    ];

    protected $casts = [
        'stop_sequence' => 'integer',
        'arrival_minutes' => 'integer',
        'departure_minutes' => 'integer',
    ];

    /**
     * Parent bus trip.
     */
    public function busTrip(): BelongsTo
    {
        return $this->belongsTo(BusTrip::class);
    }

    /**
     * Associated bus stop halt.
     */
    public function busStop(): BelongsTo
    {
        return $this->belongsTo(BusStop::class);
    }
}
