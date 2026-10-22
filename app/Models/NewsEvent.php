<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'title',
        'slug',
        'type', // 'news' or 'event'
        'event_date',
        'location',
        'content',
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
                $item->slug = Str::slug($item->title) . '-' . Str::random(5);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        if (!empty($this->featured_image)) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->featured_image)) {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->featured_image);
            }
            if (file_exists(public_path('storage/' . $this->featured_image))) {
                return asset('storage/' . $this->featured_image);
            }
            if (file_exists(public_path($this->featured_image))) {
                return asset($this->featured_image);
            }
        }
        return asset('images/news/news1.svg');
    }
}
