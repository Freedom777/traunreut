<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Schedule::command('parse:site --site=all')
    ->weeklyOn(4, '07:30')
    ->appendOutputTo(storage_path('logs/parse.log'));

Schedule::command('translate:words')
    ->weeklyOn(4, '07:50')
    ->appendOutputTo(storage_path('logs/translate.log'));
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

