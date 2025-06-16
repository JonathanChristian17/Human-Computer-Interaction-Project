<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Delete any existing codes for this email
        PasswordResetCode::where('email', $request->email)->delete();
        
        // Create new reset code
        PasswordResetCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15)
        ]);

        // Send email with verification code
        Mail::send('emails.reset-password', ['code' => $code], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Verification Code');
        });

        // Store email in session and redirect to verify page
        session(['reset_email' => $request->email]);
        
        return redirect()->route('password.verify')
            ->with('status', 'A verification code has been sent to your email.');
    }

    public function showVerifyForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Please enter your email first.');
        }
        
        return view('auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $resetCode = PasswordResetCode::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$resetCode || $resetCode->isExpired()) {
            return back()->withErrors(['code' => 'Verification code is invalid or has expired.']);
        }

        // Generate token for password reset
        $token = Str::random(60);
        session(['reset_token' => $token]);

        return redirect()->route('password.reset.form')
            ->with('status', 'Verification code is valid. Please enter your new password.');
    }

    public function showResetForm()
    {
        if (!session('reset_email') || !session('reset_token')) {
            return redirect()->route('password.request')
                ->with('error', 'Please start the password reset process from the beginning.');
        }

        return view('auth.reset-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clean up
        PasswordResetCode::where('email', $request->email)->delete();
        session()->forget(['reset_email', 'reset_token']);

        return redirect()->route('login')
            ->with('status', 'Password successfully changed! Please login with your new password.');
    }
}