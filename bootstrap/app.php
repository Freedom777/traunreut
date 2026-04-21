<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::post('/telegraph/{token}/webhook', [
                \App\Http\Controllers\Telegram\TelegramWebhookHandler::class, 'handle'
            ])
                ->name('telegraph.webhook')
                ->where('token', '.*')
                ->withoutMiddleware([
                    \Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class,
                    \Illuminate\Session\Middleware\StartSession::class,
                    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                ]);
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'log.telegram' => \App\Http\Middleware\LogTelegramRequests::class,
        ]);
        // Доверять Nginx proxy
        $middleware->trustProxies(at: '*');
        /*$middleware->preventRequestForgery(except: [
            'telegraph/*',
        ]);*/
        // Убираем сессии из API middleware
        $middleware->api(remove: [
            \Illuminate\Session\Middleware\StartSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
