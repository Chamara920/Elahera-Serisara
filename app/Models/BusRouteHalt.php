<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BusRouteHalt
 *
 * Intermediate halts for bus routes, arrival/departure times, and connection to tourist spots.
 */
class BusRouteHalt extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_route_id',
        'bus_stop_id',
        'stop_name',
        'arrival_time',
        'departure_time',
        'destination_id',
        'nearest_spot_name',
        'stop_order',
        'remarks',
    ];

    protected $casts = [
        'stop_order' => 'integer',
    ];

    public function busRoute(): BelongsTo
    {
        return $this->belongsTo(BusRoute::class);
    }

    public function busStop(): BelongsTo
    {
        return $this->belongsTo(BusStop::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
