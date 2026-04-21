<?php

// use App\Http\Controllers\Telegram\TelegramWebhookHandler;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Hello World';
});

// Route::post('/telegram/webhook', [TelegramWebhookHandler::class, 'webhook'])
//     ->name('telegram.webhook');


// Telegraph автоматически регистрирует свой роут для webhook
// Роут будет доступен по адресу: /telegraph/{token}/webhook
// Где {token} - это токен бота из таблицы telegraph_bots

// Дополнительно можно добавить свои роуты для управления:

// Проверка статуса бота
Route::get('/telegram/status', function () {
    $bot = \DefStudio\Telegraph\Models\TelegraphBot::first();

    if (!$bot) {
        return response()->json([
            'status' => 'error',
            'message' => 'Bot not found in database'
        ]);
    }

    return response()->json([
        'status' => 'ok',
        'bot' => $bot->name,
        'webhook_url' => route('telegraph.webhook.custom', $bot->token)
    ]);
})->name('telegram.status');

// Установка webhook программно (альтернатива команде php artisan telegraph:set-webhook)
Route::post('/telegram/setup-webhook', function () {
    $bot = \DefStudio\Telegraph\Models\TelegraphBot::first();

    if (!$bot) {
        return response()->json([
            'status' => 'error',
            'message' => 'Bot not found. Please create bot first.'
        ], 404);
    }

    try {
        $bot->registerWebhook()->send();

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook registered successfully',
            'webhook_url' => route('telegraph.webhook.custom', $bot->token)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('telegram.setup-webhook');

// Отправка тестового сообщения
Route::post('/telegram/test-message', function () {
    $bot = \DefStudio\Telegraph\Models\TelegraphBot::first();

    if (!$bot) {
        return response()->json([
            'status' => 'error',
            'message' => 'Bot not found'
        ], 404);
    }

    $chat = $bot->chats()->first();

    if (!$chat) {
        return response()->json([
            'status' => 'error',
            'message' => 'No chat found. Send /start to bot first.'
        ], 404);
    }

    try {
        $chat->message('🎉 Test message from API!')->send();

        return response()->json([
            'status' => 'success',
            'message' => 'Test message sent'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
})->name('telegram.test-message');
