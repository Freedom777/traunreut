<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('parse:site --site=traunreut')
    ->weeklyOn(4, '07:30')
    ->appendOutputTo(storage_path('logs/parse.log'));
Schedule::command('parse:site --site=k1')
    ->weeklyOn(4, '07:40')
    ->appendOutputTo(storage_path('logs/parse.log'));
Schedule::command('parse:site --site=naturfreunde')
    ->dailyAt( '07:45')
    ->appendOutputTo(storage_path('logs/parse.log'));

Schedule::command('translate:words')
    ->dailyAt( '07:50')
    ->appendOutputTo(storage_path('logs/translate.log'));
