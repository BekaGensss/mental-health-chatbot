<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Loggable; // DIIMPOR
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Dipertahankan, meskipun tidak digunakan langsung di sini

class UserController extends Controller
{
    use Loggable; // TRAIT LOGGING AKTIF

    /**
     * Menampilkan daftar semua akun admin (READ/Index).
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan formulir tambah akun admin baru (CREATE).
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan akun admin baru ke database (STORE).
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // 2. Buat User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        // 3. Logging Aktivitas
        $this->recordActivity('CREATE', "Menambah akun admin baru: {$user->name} ({$user->email})", $user);

        // 4. Redirect ke Daftar Akun
        return redirect()->route('admin.users.index')->with('success', 'Akun admin baru berhasil ditambahkan!');
    }
    
    /**
     * Menampilkan formulir edit akun admin (EDIT).
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui akun admin di database (UPDATE).
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        // LOGGING AKTIVITAS
        $this->recordActivity('UPDATE', "Mengubah data akun admin: {$user->name} ({$user->email})", $user);

        return redirect()->route('admin.users.index')->with('success', 'Akun admin berhasil diperbarui!');
    }

    /**
     * Menghapus akun admin (DESTROY).
     */
    public function destroy(User $user)
    {
        // Pastikan admin tidak menghapus akunnya sendiri
        if (auth()->id() == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        
        $user->delete();
        
        // LOGGING AKTIVITAS
        $this->recordActivity('DELETE', "Menghapus akun admin: {$user->name}", $user);

        return redirect()->route('admin.users.index')->with('success', 'Akun admin berhasil dihapus!');
    }
}