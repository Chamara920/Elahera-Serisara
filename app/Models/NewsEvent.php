<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class NewsEvent
 *
 * News and events related to Elahera Sirisara region.
 * Submissions by community members require Admin approval.
 */
class NewsEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'language', // 'all', 'en', 'si'
        'title',
        'title_si',
        'slug',
        'type', // 'news' or 'event'
        'event_date',
        'location',
        'content',
        'content_si',
        'featured_image',
        'is_approved', // Requires admin approval
        'views_count',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_approved' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->title).'-'.Str::random(5);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(NewsEventComment::class);
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

        return asset('images/news/news1.svg');
    }

    /**
     * Get title based on active application locale.
     */
    public function getLocalizedTitle(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->title_si)) {
            return $this->title_si;
        }

        return $this->title;
    }

    /**
     * Get content based on active application locale.
     */
    public function getLocalizedContent(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->content_si)) {
            return $this->content_si;
        }

        return $this->content;
    }

    /**
     * Scope query to match user locale ('all' or current locale).
     */
    public function scopeForLocale($query, ?string $locale = null)
    {
        $loc = $locale ?: app()->getLocale();

        return $query->where(function ($q) use ($loc) {
            $q->where('language', 'all')
                ->orWhere('language', $loc)
                ->orWhereNull('language');
        });
    }
}
