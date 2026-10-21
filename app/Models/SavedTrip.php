<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SavedTrip
 *
 * Itinerary planned and saved by registered community members.
 */
class SavedTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'trip_date',
        'start_location',
        'start_time',
        'end_time',
        'transport_mode',
        'meal_break',
        'stops_data',
        'total_duration_minutes',
        'google_maps_url',
    ];

    protected $casts = [
        'trip_date' => 'date',
        'meal_break' => 'boolean',
        'stops_data' => 'array',
        'total_duration_minutes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
