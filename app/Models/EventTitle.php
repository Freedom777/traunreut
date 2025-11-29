<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventTitle extends Model
{
    use SoftDeletes;

    protected $table = 'event_titles';

    protected $fillable = [
        'title_de',
        'title_ru',
    ];

    /**
     * Получить события с этим заголовком
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'event_title_id');
    }
}
