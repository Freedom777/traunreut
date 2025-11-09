<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeeplApiCount extends Model
{
    protected $table = 'deepl_api_counts';

    protected $fillable = [
        'char_count'
    ];

}
