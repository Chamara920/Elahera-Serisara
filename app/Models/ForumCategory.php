<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_si',
        'slug',
        'icon',
        'description',
    ];

    protected static function booted()
    {
        static::creating(function ($cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name_en);
            }
        });
    }

    public function topics(): HasMany
    {
        return $this->hasMany(ForumTopic::class);
    }

    public function getLocalizedName(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->name_si)) {
            return $this->name_si;
        }

        return $this->name_en;
    }
}
