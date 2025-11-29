<?php

use App\Http\Controllers\Telegram\TelegramWebhookHandler;
use Illuminate\Support\Facades\Route;

Route::post('/telegraph/{token}/webhook', [TelegramWebhookHandler::class, 'handle'])
    ->name('telegraph.webhook.custom')
    ->where('token', '.*');
