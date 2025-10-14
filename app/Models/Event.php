<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    protected $fillable = [
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
     * Связь с переводом на русский язык
     */
    public function translation(): HasOne
    {
        return $this->hasOne(EventRu::class, 'id', 'id');
    }
}
