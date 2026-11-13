<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class HeroSlider
 *
 * Full-width hero sliders on homepage, managed via Filament.
 */
class HeroSlider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_si',
        'subtitle_en',
        'subtitle_si',
        'image',
        'button1_text_en',
        'button1_text_si',
        'button1_url',
        'button2_text_en',
        'button2_text_si',
        'button2_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getLocalizedTitle(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->title_si)) {
            return $this->title_si;
        }

        return $this->title_en;
    }

    public function getLocalizedSubtitle(): ?string
    {
        if (app()->getLocale() === 'si' && ! empty($this->subtitle_si)) {
            return $this->subtitle_si;
        }

        return $this->subtitle_en;
    }

    public function getLocalizedButton1Text(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->button1_text_si)) {
            return $this->button1_text_si;
        }

        return $this->button1_text_en ?: 'Smart Trip Planner';
    }

    public function getLocalizedButton2Text(): string
    {
        if (app()->getLocale() === 'si' && ! empty($this->button2_text_si)) {
            return $this->button2_text_si;
        }

        return $this->button2_text_en ?: 'Explore Destinations';
    }

    /**
     * Resolve public web URL for the slider image.
     */
    public function getImageUrl(): string
    {
        if (! empty($this->image)) {
            if (Storage::disk('public')->exists($this->image)) {
                return asset('storage/'.$this->image);
            }
            if (file_exists(public_path('storage/'.$this->image))) {
                return asset('storage/'.$this->image);
            }
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
        }

        return asset('images/sliders/slider1.jpg');
    }
}
