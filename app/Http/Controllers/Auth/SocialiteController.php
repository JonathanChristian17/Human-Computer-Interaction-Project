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
            
            // Log data yang diterima dari Google
            Log::info('Google User Data:', [
                'id' => $googleUser->id,
                'email' => $googleUser->email,
                'name' => $googleUser->name
            ]);

            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                       ->orWhere('email', $googleUser->email)
                       ->first();

            if (!$user) {
                // Buat user baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'customer' // Tambahkan role default
                ]);

                Log::info('New User Created:', ['user_id' => $user->id]);
            } else if (!$user->google_id) {
                // Update google_id untuk user yang sudah ada
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
                return redirect()->intended('/dashboard')
                               ->with('status', 'Berhasil login dengan Google!');
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
                           ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }
} 