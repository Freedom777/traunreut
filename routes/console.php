<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
crontab -e
# Только crontab
30 23 28-31 * * [ $(date -d tomorrow +\%d) -eq 1 ] && cd /var/www/traunreut && php artisan parse:site --site=all >> /var/www/traunreut/storage/logs/parse.log 2>&1
50 23 28-31 * * [ $(date -d tomorrow +\%d) -eq 1 ] && cd /var/www/traunreut && php artisan translate:words >> /var/www/traunreut/storage/logs/translate.log 2>&1
*/
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

