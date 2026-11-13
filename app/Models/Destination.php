<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
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
        'opening_time',
        'closing_time',
        'is_always_open',
        'closed_days',
        'closure_notes',
        'best_months',
        'moderate_months',
        'not_recommended_months',
        'ticket_price_min',
        'ticket_price_max',
        'ticket_price_range',
        'safety_alerts_en',
        'safety_alerts_si',
        'packing_list_en',
        'packing_list_si',
        'dress_code_en',
        'dress_code_si',
        'nearest_bus_stop',
        'nearest_bus_stop_id',
        'bus_stop_walk_minutes',
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
        'bus_stop_walk_minutes' => 'integer',
        'is_always_open' => 'boolean',
        'closed_days' => 'array',
        'best_months' => 'array',
        'moderate_months' => 'array',
        'not_recommended_months' => 'array',
        'ticket_price_min' => 'float',
        'ticket_price_max' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views_count' => 'integer',
    ];

    protected $appends = [
        'featured_image_url',
    ];

    /**
     * Auto-generate slug if not provided and sync category names.
     */
    protected static function booted()
    {
        static::creating(function ($destination) {
            if (empty($destination->slug)) {
                $destination->slug = Str::slug($destination->title_en);
            }
        });

        static::saving(function ($destination) {
            if ($destination->category_id && empty($destination->category)) {
                $cat = DestinationCategory::find($destination->category_id);
                if ($cat) {
                    $destination->category = $cat->name_en;
                    if (empty($destination->category_si)) {
                        $destination->category_si = $cat->name_si;
                    }
                }
            } elseif (empty($destination->category_id) && ! empty($destination->category)) {
                $cat = DestinationCategory::where('name_en', $destination->category)->first();
                if ($cat) {
                    $destination->category_id = $cat->id;
                    if (empty($destination->category_si)) {
                        $destination->category_si = $cat->name_si;
                    }
                }
            }
        });
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        return $this->getFeaturedImageUrl();
    }

    /**
     * Resolve public web URL for the featured image.
     */
    public function getFeaturedImageUrl(): string
    {
        if (! empty($this->featured_image)) {
            if (Storage::disk('public')->exists($this->featured_image)) {
                return asset('storage/'.$this->featured_image);
            }
            if (file_exists(public_path('storage/'.$this->featured_image))) {
                return asset('storage/'.$this->featured_image);
            }
            if (file_exists(public_path($this->featured_image))) {
                return asset($this->featured_image);
            }
        }

        return asset('images/destinations/anicut.svg');
    }

    /**
     * Resolve public web URLs for gallery images array.
     *
     * @return list<string>
     */
    public function getGalleryImageUrls(): array
    {
        if (empty($this->gallery_images) || ! is_array($this->gallery_images)) {
            return [];
        }

        $urls = [];
        foreach ($this->gallery_images as $img) {
            if (empty($img)) {
                continue;
            }

            if (Storage::disk('public')->exists($img)) {
                $urls[] = asset('storage/'.$img);
            } elseif (file_exists(public_path('storage/'.$img))) {
                $urls[] = asset('storage/'.$img);
            } elseif (file_exists(public_path($img))) {
                $urls[] = asset($img);
            } else {
                $urls[] = asset('images/destinations/anicut1.svg');
            }
        }

        return $urls;
    }

    /**
     * Check if Sinhala translation is provided.
     */
    public function hasSinhala(): bool
    {
        return ! empty(trim($this->title_si ?? '')) && ! empty(trim($this->description_si ?? ''));
    }

    /**
     * Determine if Sinhala fallback notice should be displayed.
     * When user selected Sinhala but Sinhala content is missing.
     */
    public function needsSinhalaNotice(): bool
    {
        return app()->getLocale() === 'si' && ! $this->hasSinhala();
    }

    /**
     * Get localized title with English fallback.
     */
    public function getLocalizedTitle(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->title_si)) {
            return $this->title_si;
        }

        return $this->title_en;
    }

    /**
     * Get localized short description with English fallback.
     */
    public function getLocalizedShortDescription(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->short_description_si)) {
            return $this->short_description_si;
        }

        return $this->short_description_en;
    }

    /**
     * Get localized full description with English fallback.
     */
    public function getLocalizedDescription(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->description_si)) {
            return $this->description_si;
        }

        return $this->description_en;
    }

    /**
     * Get localized category name.
     */
    public function getLocalizedCategory(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->category_si)) {
            return $this->category_si;
        }

        return $this->category;
    }

    /**
     * Get localized safety alerts.
     */
    public function getLocalizedSafetyAlerts(): ?string
    {
        if (app()->getLocale() === 'si' && ! empty($this->safety_alerts_si)) {
            return $this->safety_alerts_si;
        }

        return $this->safety_alerts_en;
    }

    /**
     * Get localized packing list.
     */
    public function getLocalizedPackingList(): ?string
    {
        if (app()->getLocale() === 'si' && ! empty($this->packing_list_si)) {
            return $this->packing_list_si;
        }

        return $this->packing_list_en;
    }

    /**
     * Get localized dress code advice.
     */
    public function getLocalizedDressCode(): ?string
    {
        if (app()->getLocale() === 'si' && ! empty($this->dress_code_si)) {
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
    public function destinationCategory(): BelongsTo
    {
        return $this->belongsTo(DestinationCategory::class, 'category_id');
    }

    /**
     * All categories associated with this destination.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(DestinationCategory::class, 'destination_destination_category');
    }

    /**
     * Get all localized category names as a list or collection.
     */
    public function getAllLocalizedCategories(): array
    {
        if ($this->relationLoaded('categories') && $this->categories->isNotEmpty()) {
            return $this->categories->map(function ($c) {
                return (app()->getLocale() === 'si' && ! empty($c->name_si)) ? $c->name_si : $c->name_en;
            })->all();
        }

        $cats = $this->categories()->get();
        if ($cats->isNotEmpty()) {
            return $cats->map(function ($c) {
                return (app()->getLocale() === 'si' && ! empty($c->name_si)) ? $c->name_si : $c->name_en;
            })->all();
        }

        return [$this->getLocalizedCategory()];
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
     * Get formatted visiting hours string.
     */
    public function getFormattedOpeningHours(): string
    {
        if ($this->is_always_open) {
            return 'Open 24/7 (Always Open)';
        }

        $open = $this->opening_time ? substr($this->opening_time, 0, 5) : '06:00';
        $close = $this->closing_time ? substr($this->closing_time, 0, 5) : '18:00';

        return "{$open} - {$close}";
    }

    /**
     * Check if destination is open at a specific time string (e.g. "09:30").
     */
    public function isOpenAt(string $timeString): bool
    {
        if ($this->is_always_open) {
            return true;
        }

        $check = substr($timeString, 0, 5);
        $open = $this->opening_time ? substr($this->opening_time, 0, 5) : '06:00';
        $close = $this->closing_time ? substr($this->closing_time, 0, 5) : '18:00';

        return $check >= $open && $check <= $close;
    }

    /**
     * Check if destination is usually closed on a given day (e.g. "Monday").
     */
    public function isClosedOnDay(string $dayName): bool
    {
        if (empty($this->closed_days) || ! is_array($this->closed_days)) {
            return false;
        }

        return in_array($dayName, $this->closed_days, true);
    }

    /**
     * Get seasonal suitability for a given month (e.g., 'January', 'July', or current month).
     * Returns: 'best', 'moderate', 'not_recommended', or 'normal'.
     */
    public function getSeasonalStatusForMonth(?string $month = null): string
    {
        $monthName = $month ?: date('F');

        $best = (array) ($this->best_months ?? []);
        $moderate = (array) ($this->moderate_months ?? []);
        $notRecommended = (array) ($this->not_recommended_months ?? []);

        if (in_array($monthName, $best, true)) {
            return 'best';
        }

        if (in_array($monthName, $notRecommended, true)) {
            return 'not_recommended';
        }

        if (in_array($monthName, $moderate, true)) {
            return 'moderate';
        }

        return 'normal';
    }

    /**
     * Get formatted ticket price string.
     */
    public function getFormattedTicketPrice(): string
    {
        if (! empty($this->ticket_price_range)) {
            return $this->ticket_price_range;
        }

        if ($this->ticket_price_min > 0 || $this->ticket_price_max > 0) {
            if ($this->ticket_price_min == $this->ticket_price_max) {
                return 'LKR '.number_format($this->ticket_price_min);
            }

            return 'LKR '.number_format($this->ticket_price_min).' - '.number_format($this->ticket_price_max);
        }

        return 'Free Entry';
    }

    /**
     * Total review count.
     */
    public function reviewsCount(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * Nearest physical bus stop halt.
     */
    public function nearestBusStop(): BelongsTo
    {
        return $this->belongsTo(BusStop::class, 'nearest_bus_stop_id');
    }
}
