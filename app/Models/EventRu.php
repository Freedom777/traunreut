<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRu extends Model
{
    protected $table = 'events_ru';

    protected $fillable = [
        'id',
        'title',
    ];
}
