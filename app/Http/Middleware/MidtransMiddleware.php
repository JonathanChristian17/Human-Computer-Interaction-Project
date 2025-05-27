<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Log incoming request
        Log::info('Midtrans webhook request received', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        // Add CORS headers
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization, Accept, X-Midtrans-Notification, X-Midtrans-Signature, X-Midtrans-Timestamp');

        return $response;
    }
} 