<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRu extends Model
{
    protected $table = 'events_ru';

    protected $fillable = [
        'id',
        'title',
    ];

    public $incrementing = false;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'id', 'id');
    }
}
