<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // Diperlukan untuk validasi email

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     * Menggunakan Request standar karena kita memisahkan validasi.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // ----------------------------------------------------
        // LOGIKA UNGGAH FOTO PROFIL (JIKA ADA FILE DIKIRIM)
        // ----------------------------------------------------
        if ($request->hasFile('photo')) {
            // Validasi file foto secara terpisah
            $request->validate([
                'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
            ]);

            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('profile-photos', 'public');
            
            // Simpan path ke database
            $user->profile_photo_path = $path;
            $user->save();

            // Selesai, redirect kembali
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        // ----------------------------------------------------
        // LOGIKA UPDATE INFORMASI PROFIL (JIKA HANYA NAMA/EMAIL DIKIRIM)
        // ----------------------------------------------------
        
        // Gunakan validasi formal untuk Nama dan Email
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    
    // ... (method updatePassword dan destroy tetap sama)
}