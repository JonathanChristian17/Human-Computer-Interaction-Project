<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with([
                'prompt' => 'select_account',
                'access_type' => 'offline'
            ])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Log received data from Google
            Log::info('Google User Data:', [
                'id' => $googleUser->id,
                'email' => $googleUser->email,
                'name' => $googleUser->name
            ]);

            // Find user by google_id or email
            $user = User::where('google_id', $googleUser->id)
                       ->orWhere('email', $googleUser->email)
                       ->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'customer' // Add default role
                ]);

                Log::info('New User Created:', ['user_id' => $user->id]);
            
                // Update google_id for existing user
                $user->update([
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now()
                ]);

                Log::info('Existing User Updated:', ['user_id' => $user->id]);
            }

            // Login user
            Auth::login($user, true);

            if (Auth::check()) {
                Log::info('User successfully logged in:', ['user_id' => Auth::id()]);
                
                // Add welcome message similar to direct login
                $welcomeMessage = 'Welcome back, ' . $user->name . '!';
                
                if ($user->role === 'admin') {
                    return redirect()->intended(route('admin.dashboard'))
                        ->with('success', $welcomeMessage);
                }

                if ($user->role === 'receptionist') {
                    return redirect()->intended(route('receptionist.dashboard'))
                        ->with('success', $welcomeMessage);
                }

                // For regular users, redirect to landing page
                return redirect()->route('landing')
                    ->with('success', $welcomeMessage)
                    ->with('login_success', true);
            } else {
                Log::error('Failed to login user after social authentication');
                throw new Exception('Failed to login user');
            }

        } catch (Exception $e) {
            Log::error('Google Login Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('error', 'An error occurred while logging in with Google. Please try again.');
        }
    }
} 