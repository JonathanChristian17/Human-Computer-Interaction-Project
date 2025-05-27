<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = $request->user();
            $data = $request->validated();

            if ($request->hasFile('profile_photo')) {
                // Delete old profile photo if exists
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                // Store new profile photo
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $data['profile_photo'] = $path;
            }

            $user->fill($data);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'profile_photo_url' => $user->profile_photo_url,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'profile_photo_url' => $user->profile_photo_url
                    ]
                ]);
            }

            return back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to update profile. ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'min:8', 'confirmed', 'different:current_password'],
                'password_confirmation' => ['required']
            ], [
                'current_password.current_password' => 'The current password is incorrect.',
                'password.different' => 'The new password must be different from your current password.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.min' => 'The password must be at least 8 characters.'
            ]);

            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => ['required', 'image', 'max:1024'],
            ]);

            $user = $request->user();

            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new photo
            $path = $request->file('photo')->store('profile-photos', 'public');
            
            // Update user with new photo path
            $user->update([
                'profile_photo_path' => $path
            ]);

            // Get the full URL for the stored photo
            $photoUrl = asset('storage/' . $path);

            // Return JSON response for AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile photo updated successfully',
                    'profile_photo_url' => $photoUrl
                ]);
            }

            return back()->with('success', 'Profile photo updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Profile photo upload error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile photo',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to update profile photo. ' . $e->getMessage());
        }
    }

    public function destroyPhoto(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
                
                $user->update([
                    'profile_photo_path' => null,
                ]);
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile photo removed successfully',
                    'default_photo_url' => asset('images/default-avatar.png')
                ]);
            }

            return back()->with('success', 'Profile photo removed.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove profile photo',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to remove profile photo. ' . $e->getMessage());
        }
    }

    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
        ]);

        $request->user()->update($validated);

        return back()->with('success', 'Notification preferences updated.');
    }

    public function checkStorageLink()
    {
        $linked = file_exists(public_path('storage'));
        
        if (!$linked) {
            \Artisan::call('storage:link');
            $linked = file_exists(public_path('storage'));
        }
        
        return response()->json([
            'success' => $linked,
            'message' => $linked ? 'Storage link exists' : 'Failed to create storage link'
        ]);
    }
}