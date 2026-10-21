<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsEventComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_event_id',
        'user_id',
        'comment',
    ];

    public function newsEvent(): BelongsTo
    {
        return $this->belongsTo(NewsEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
