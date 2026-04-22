<?php

use App\Http\Controllers\Telegram\TelegramWebhookHandler;
use Illuminate\Support\Facades\Route;

/*Route::post('/telegraph/{token}/webhook', [TelegramWebhookHandler::class, 'handle'])
    ->name('telegraph.webhook')
    ->where('token', '.*');*/
Route::post('/test-nocsrf', function() {
    return 'OK';
})->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class);
