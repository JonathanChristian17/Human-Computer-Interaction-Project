<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Don't redirect if the route is related to bookings or payments
                if ($request->is('bookings/*') || $request->is('bookings') || $request->is('*/payment*')) {
                    \Log::info('Allowing access to booking/payment route', [
                        'url' => $request->url(),
                        'route' => $request->route()->getName()
                    ]);
                    return $next($request);
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
} 