<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Class BlogPost
 *
 * Community blog articles. Posts submitted by community users require Admin approval.
 */
class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'is_approved', // Requires admin approval
        'views_count',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title) . '-' . Str::random(5);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
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
        return asset('images/blog/blog1.svg');
    }
}
