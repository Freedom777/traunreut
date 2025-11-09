<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventRu extends Model
{
    protected $table = 'events_ru';

    protected $fillable = [
        'id',
        'title_ru',
        'title_de'
    ];

    /**
     * Получить все события, использующие этот перевод
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'events_ru_id');
    }
}
