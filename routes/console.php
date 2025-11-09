<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
crontab -e
* * * * * cd /var/www/traunreut && php artisan schedule:run >> /dev/null 2>&1
*/
Schedule::command('parse:site', ['--site' => 'all'])->monthlyOn(1, '00:01')->appendOutputTo(storage_path('logs/parse.log'));;
Schedule::command('translate:words')->monthlyOn(1, '00:20')->appendOutputTo(storage_path('logs/translate.log'));;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

