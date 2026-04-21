<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogTelegramRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::channel('single')->info('Telegram request', [
            'path'    => $request->path(),
            'payload' => $request->all(),
        ]);

        return $next($request);
    }
}
