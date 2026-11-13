<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Class ForumTopic
 *
 * Travel discussion topic. Direct publication without admin approval.
 */
class ForumTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_category_id',
        'user_id',
        'language', // 'all', 'en', 'si'
        'title',
        'title_si',
        'slug',
        'content',
        'content_si',
        'views_count',
    ];

    protected $casts = [
        'views_count' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($topic) {
            if (empty($topic->slug)) {
                $topic->slug = Str::slug($topic->title).'-'.Str::random(5);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ForumCategory::class, 'forum_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class);
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
