<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsReceptionist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== 'receptionist') {
            abort(403, 'Unauthorized. Only receptionists can access this area.');
        }

        return $next($request);
    }
} 