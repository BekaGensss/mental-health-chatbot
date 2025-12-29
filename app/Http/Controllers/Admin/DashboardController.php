<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentData;
use App\Models\Question;
use App\Models\AcademicClass; 
use App\Models\User; 
use App\Models\ActivityLog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama admin dengan statistik ringkasan.
     * Mengambil data ringkas untuk kartu-kartu dashboard utama.
     */
    public function index()
    {
        // Mendapatkan data dasar untuk ringkasan global
        $totalMurid = StudentData::count();
        $totalPengisianHariIni = StudentData::whereDate('created_at', today())->count();
        
        // Data Global Tambahan
        $totalPertanyaan = Question::count();
        $totalAkunAdmin = User::count();
        $totalKelasAktif = AcademicClass::where('is_active', true)->count();
        $totalLogAktivitas = ActivityLog::count(); 

        // Menyediakan variabel untuk View (meskipun filter tidak digunakan secara aktif di halaman ini)
        $allClasses = AcademicClass::where('is_active', true)->pluck('name', 'name')->prepend('Semua Kelas', 'all');
        $classFilter = 'all'; 

        // Menyediakan data rata-rata (disarankan diambil dari cache atau data ringkasan)
        // Disini kita gunakan nol untuk menghindari perhitungan berat, namun variable harus ada.
        $avgPhq9 = 0; $avgGad7 = 0; $avgKpk = 0; $avgDass21 = 0;
        $totalRiskCount = 0;
        
        // --- MOCKUP DATA JS (Data harus dikirim, meskipun nol) ---
        $mockupDataLine = [0]; $mockupLabelsLine = ['Hari'];
        $mockupDataRadar = [0]; $mockupLabelsRadar = ['Kategori'];
        $submissionsPerClass = []; // Harus disediakan

        // Memanggil View Dashboard utama
        return view('dashboard', compact(
            'totalMurid', 'totalPengisianHariIni', 'totalPertanyaan', 'totalAkunAdmin',
            'totalKelasAktif', 'totalLogAktivitas', 'allClasses', 'classFilter',
            'avgPhq9', 'avgGad7', 'avgKpk', 'avgDass21', 'totalRiskCount',
            'mockupDataLine', 'mockupLabelsLine', 'mockupDataRadar', 'mockupLabelsRadar', 'submissionsPerClass'
        ));
    }

    /**
     * --- FUNGSI BARU ---
     * Menampilkan Laporan Hasil Analisis NLP Gemini AI.
     * Fungsi ini untuk menangani navigasi rute admin.reports.ai
     */
    public function aiReports()
    {
        // Mengambil data murid yang memiliki hasil analisis AI (ai_analysis tidak null)
        $students = StudentData::whereNotNull('ai_analysis')
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Memanggil View khusus laporan AI di folder admin
        return view('admin.reports_ai', compact('students'));
    }
}