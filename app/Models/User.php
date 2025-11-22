<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Import Storage tetap dipertahankan, tetapi kita juga bisa menggunakan asset() secara langsung

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tambahkan 'profile_photo_path' ke fillable
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Get the user's profile photo URL, prioritizing uploaded photo over Gravatar.
     * Menggunakan helper asset() untuk akses publik yang lebih andal.
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        // 1. Cek apakah ada foto yang diunggah
        if ($this->profile_photo_path) {
            // SOLUSI ANDAL: Gunakan asset() untuk mengakses link simbolis publik/storage.
            // Kita hapus "public/" dari path jika ada, karena asset() mengarah ke folder public.
            // Catatan: profile_photo_path di DB tersimpan sebagai 'profile-photos/...'
            return asset('storage/' . $this->profile_photo_path); 
        }
        
        // 2. Fallback ke Gravatar
        $email_hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$email_hash}?s=200&d=mp"; 
    }
}