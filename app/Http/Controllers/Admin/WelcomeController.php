<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WelcomeSetting;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil data pertama, jika tidak ada buat objek baru sebagai default
        $setting = WelcomeSetting::first() ?? new WelcomeSetting([
            'title' => 'Selamat Datang',
            'description' => 'Silakan pilih akses menu Anda untuk melanjutkan.',
            'btn_test_label' => 'Mulai Tes Form',
            'btn_admin_label' => 'Login Admin'
        ]);

        return view('admin.welcome', compact('setting'));
    }
}