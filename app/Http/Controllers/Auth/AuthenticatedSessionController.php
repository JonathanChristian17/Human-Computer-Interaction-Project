<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get authenticated user
        $user = Auth::user();

        if ($user) {
            // Add welcome notification with user's name
            session()->flash('success', 'Selamat datang kembali, ' . $user->name . '!');

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->role === 'receptionist') {
                return redirect()->intended(route('receptionist.dashboard'));
            }
        } else {
            // Fallback message if user is not available
            session()->flash('success', 'Selamat datang kembali!');
        }

        return redirect()->intended(route('landing'))
            ->with('login_success', true);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')
            ->with('logout_success', true);
    }
} 