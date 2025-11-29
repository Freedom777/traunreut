<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTypeKeyword extends Model
{
    protected $fillable = ['keyword', 'event_type_id'];

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }
}
