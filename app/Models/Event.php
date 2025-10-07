<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'is_active'
    ];
}
