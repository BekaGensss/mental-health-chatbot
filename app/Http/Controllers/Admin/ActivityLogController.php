<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar Log Aktivitas.
     */
    public function index()
    {
        // Ambil semua log, tampilkan 15 per halaman, urutkan dari terbaru, preload data user
        $logs = ActivityLog::with('user')->latest()->paginate(15); 
        
        return view('admin.activity_log.index', compact('logs'));
    }

    /**
     * Menghapus semua Log Aktivitas.
     */
    public function clearLogs()
    {
        // Menghapus semua baris dari tabel ActivityLogs.
        ActivityLog::truncate(); 

        return redirect()->route('admin.log_aktivitas')->with('success', 'Semua riwayat log aktivitas berhasil dihapus.');
    }
}