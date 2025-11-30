<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_title_id',
        'site',
        'event_id',
        'category',
        'artist',
        'img',
        'start_date',
        'end_date',
        'location',
        'link',
        'city_id',
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
     * Получить заголовок события
     */
    public function eventTitle()
    {
        return $this->belongsTo(EventTitle::class, 'event_title_id');
    }

    /**
     * Получить город события
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function eventTypes()
    {
        return $this->belongsToMany(EventType::class, 'event_event_type');
    }
}
