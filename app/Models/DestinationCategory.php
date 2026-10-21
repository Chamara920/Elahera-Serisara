<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Class DestinationCategory
 *
 * Represents category for tourist destinations in Elahera Sirisara.
 */
class DestinationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_si',
        'slug',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name_en);
            }
        });
    }

    /**
     * Destinations belonging to this category.
     */
    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class, 'category_id');
    }

    /**
     * Localized name helper.
     */
    public function getLocalizedName(): string
    {
        if (app()->getLocale() === 'si' && !empty($this->name_si)) {
            return $this->name_si;
        }
        return $this->name_en;
    }
}
