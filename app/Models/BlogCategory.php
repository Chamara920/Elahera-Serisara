<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_si',
        'slug',
    ];

    protected static function booted()
    {
        static::creating(function ($cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name_en);
            }
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

    public function getLocalizedName(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->name_si)) {
            return $this->name_si;
        }

        return $this->name_en;
    }
}
