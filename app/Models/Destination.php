<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Class Destination
 *
 * Represents a tourist destination in Elahera Sirisara region.
 * Supports bilingual content with English as mandatory and Sinhala fallback indicator.
 */
class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_si',
        'slug',
        'short_description_en',
        'short_description_si',
        'description_en',
        'description_si',
        'category',
        'category_id',
        'category_si',
        'route_zone',
        'featured_image',
        'gallery_images',
        'distance_km',
        'travel_time_minutes',
        'stay_duration_minutes',
        'safety_alerts_en',
        'safety_alerts_si',
        'packing_list_en',
        'packing_list_si',
        'dress_code_en',
        'dress_code_si',
        'nearest_bus_stop',
        'bus_route_numbers',
        'latitude',
        'longitude',
        'is_featured',
        'featured_priority',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'distance_km' => 'float',
        'travel_time_minutes' => 'integer',
        'stay_duration_minutes' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views_count' => 'integer',
    ];

    /**
     * Auto-generate slug if not provided.
     */
    protected static function booted()
    {
        static::creating(function ($destination) {
            if (empty($destination->slug)) {
                $destination->slug = Str::slug($destination->title_en);
            }
        });
    }

    /**
     * Check if Sinhala translation is provided.
     */
    public function hasSinhala(): bool
    {
        return !empty(trim($this->title_si ?? '')) && !empty(trim($this->description_si ?? ''));
    }

    /**
     * Determine if Sinhala fallback notice should be displayed.
     * When user selected Sinhala but Sinhala content is missing.
     */
    public function needsSinhalaNotice(): bool
    {
        return app()->getLocale() === 'si' && !$this->hasSinhala();
    }

    /**
     * Get localized title with English fallback.
     */
    public function getLocalizedTitle(): string
    {
        if (app()->getLocale() === 'si' && !empty($this->title_si)) {
            return $this->title_si;
        }
        return $this->title_en;
    }

    /**
     * Get localized short description with English fallback.
     */
    public function getLocalizedShortDescription(): string
    {
        if (app()->getLocale() === 'si' && !empty($this->short_description_si)) {
            return $this->short_description_si;
        }
        return $this->short_description_en;
    }

    /**
     * Get localized full description with English fallback.
     */
    public function getLocalizedDescription(): string
    {
        if (app()->getLocale() === 'si' && !empty($this->description_si)) {
            return $this->description_si;
        }
        return $this->description_en;
    }

    /**
     * Get localized category name.
     */
    public function getLocalizedCategory(): string
    {
        if (app()->getLocale() === 'si' && !empty($this->category_si)) {
            return $this->category_si;
        }
        return $this->category;
    }

    /**
     * Get localized safety alerts.
     */
    public function getLocalizedSafetyAlerts(): ?string
    {
        if (app()->getLocale() === 'si' && !empty($this->safety_alerts_si)) {
            return $this->safety_alerts_si;
        }
        return $this->safety_alerts_en;
    }

    /**
     * Get localized packing list.
     */
    public function getLocalizedPackingList(): ?string
    {
        if (app()->getLocale() === 'si' && !empty($this->packing_list_si)) {
            return $this->packing_list_si;
        }
        return $this->packing_list_en;
    }

    /**
     * Get localized dress code advice.
     */
    public function getLocalizedDressCode(): ?string
    {
        if (app()->getLocale() === 'si' && !empty($this->dress_code_si)) {
            return $this->dress_code_si;
        }
        return $this->dress_code_en;
    }

    /**
     * Get Google Maps direction URL.
     */
    public function getGoogleMapsUrl(): string
    {
        return "https://www.google.com/maps/dir/?api=1&destination={$this->latitude},{$this->longitude}";
    }

    /**
     * Category relationship.
     */
    public function destinationCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DestinationCategory::class, 'category_id');
    }

    /**
     * Reviews relationship.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(DestinationReview::class);
    }

    /**
     * Calculate average rating.
     */
    public function averageRating(): float
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating') ?: 4.8, 1);
    }

    /**
     * Total review count.
     */
    public function reviewsCount(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }
}
