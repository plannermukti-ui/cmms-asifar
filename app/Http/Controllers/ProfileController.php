<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information and avatar.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_whatsapp'  => ['nullable', 'string', 'max:20'],
            'bio'          => ['nullable', 'string', 'max:1000'],
            'avatar'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'], // max 2MB
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'no_whatsapp'  => $request->no_whatsapp,
            'bio'          => $request->bio,
        ];

        // Process avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatars', $filename, 'public');
            
            $data['avatar'] = $filename;
        }

        $user->update($data);

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Display another user's public profile/bio page.
     */
    public function show(\App\Models\User $user)
    {
        $user->load(['department', 'jabatan', 'site', 'roles']);
        return view('profile.show', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password Anda berhasil diperbarui.');
    }
}
