<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'events_ru_id',
        'site',
        'event_id',
        'category',
        'artist',
        'title',
        'img',
        'start_date',
        'end_date',
        'location',
        'link',
        'region',
        'cities',
        'event_types',
        'source',
        'description',
        'price',
        'is_active',
        'debug_html',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Получить заголовки
     */
    public function titles(): BelongsTo
    {
        return $this->belongsTo(EventRu::class, 'events_ru_id');
    }
}
