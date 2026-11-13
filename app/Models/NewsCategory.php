<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Class NewsCategory
 *
 * Represents category classifications for News & Events.
 */
class NewsCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_si',
        'slug',
        'icon',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name_en);
            }
        });
    }

    public function newsEvents(): HasMany
    {
        return $this->hasMany(NewsEvent::class, 'category_id');
    }

    public function getLocalizedName(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->name_si)) {
            return $this->name_si;
        }

        return $this->name_en;
    }
}
