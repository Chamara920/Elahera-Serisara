<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_name_en',
        'institution_name_si',
        'category', // Police, Hospital, Wildlife/Forest, Disaster, Local Council
        'phone_primary',
        'phone_secondary',
        'location',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function getLocalizedName(): string
    {
        if (app()->getLocale() === 'si' && !empty($this->institution_name_si)) {
            return $this->institution_name_si;
        }
        return $this->institution_name_en;
    }
}
