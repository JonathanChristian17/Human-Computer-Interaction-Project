<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,receptionist,customer'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ]);

        try {
            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $validated['profile_photo'] = $photoPath;
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'profile_photo' => $validated['profile_photo'] ?? null
            ]);

            // Log activity
            Activity::log(
                Auth::id(),
                'Created new user',
                "Created new {$user->role} account for {$user->name}",
                'user_create',
                $user
            );

        return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Gagal menambahkan user. Silakan coba lagi.']);
        }
    }

    public function edit(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat mengedit akun sendiri melalui halaman ini.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat mengedit akun sendiri melalui halaman ini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,receptionist,customer'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()]
        ]);

        $oldUser = $user->replicate();

        try {
            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $validated['profile_photo'] = $photoPath;
            }

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['phone'],
                'address' => $validated['address']
            ];

            if (isset($validated['profile_photo'])) {
                $updateData['profile_photo'] = $validated['profile_photo'];
            }

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Log activity with changes
            $changes = [];
            foreach ($updateData as $key => $value) {
                if ($key !== 'password' && $oldUser->$key != $value) {
                    $changes[] = "$key: {$oldUser->$key} → $value";
                }
            }
            if (!empty($validated['password'])) {
                $changes[] = "password: [changed]";
            }

            if (!empty($changes)) {
                Activity::log(
                    Auth::id(),
                    'Updated user',
                    "Updated {$user->role} account for {$user->name}. Changes: " . implode(', ', $changes),
                    'user_update',
                    $user
                );
            }

            return redirect()->route('admin.users.index')
                    ->with('success', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Gagal memperbarui user. Silakan coba lagi.']);
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $userName = $user->name;
        $userRole = $user->role;

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        // Log activity
        Activity::log(
            Auth::id(),
            'Deleted user',
            "Deleted {$userRole} account for {$userName}",
            'user_delete'
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
} 