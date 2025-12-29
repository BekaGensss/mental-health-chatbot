<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentDataController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AcademicClassController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DataMiningController;
use App\Http\Controllers\Admin\WelcomeController; 
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicPdfController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ScoringController; // Controller Utama NLP Gemini
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTE PUBLIK (MURID & UMUM)
|--------------------------------------------------------------------------
*/

// Halaman Landing utama
Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

// Menampilkan form awal / chatbot interface
Route::get('/', [PublicController::class, 'showForm'])->name('form.show');

// PROSES SUBMIT: Menggunakan ScoringController untuk kalkulasi & NLP Gemini
Route::post('/submit', [ScoringController::class, 'submit'])->name('form.submit');

// Halaman Sukses: Menampilkan hasil diagnosa AI kepada murid
Route::get('/success', [PublicController::class, 'showSuccess'])->name('form.success');

// Export Laporan PDF untuk Murid
Route::get('/export/pdf', [PublicPdfController::class, 'exportResult'])->name('form.export_pdf');

// Form skoring statis (opsional/alternatif)
Route::get('/scoring-form', [PublicController::class, 'showScoringForm'])->name('form.scoring_form');

// Interaksi Chatbot via AJAX
Route::post('/chat/interact', [ChatbotController::class, 'interact'])->name('chatbot.interact');

// --- TAMBAHAN BARU: GEOLOCATION API ---
// Rute untuk mendapatkan data psikolog terdekat (digunakan oleh JavaScript fetch)
Route::get('/api/nearby-psychologists', [ScoringController::class, 'getPsychologists'])->name('api.psychologists');


/*
|--------------------------------------------------------------------------
| RUTE ADMIN (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Dashboard Utama Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- NAVIGASI BARU: MONITORING ANALISIS AI ---
    // Rute untuk melihat tabel hasil analisis NLP Gemini per murid
    Route::get('/admin/reports/ai', [DashboardController::class, 'aiReports'])->name('admin.reports.ai');

    // Statistik & Data Jawaban (Menu Chatbot di Sidebar)
    Route::get('/admin/data-murid', [StatistikController::class, 'index'])->name('admin.data_murid');
    Route::get('/admin/data-murid/export', [StatistikController::class, 'exportData'])->name('admin.data_murid.export');

    // KELOLA PERTANYAAN (CRUD)
    Route::get('/admin/kelola-pertanyaan', [QuestionController::class, 'index'])->name('admin.kelola_pertanyaan');
    Route::get('/admin/kelola-pertanyaan/create', [QuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/admin/kelola-pertanyaan', [QuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/admin/kelola-pertanyaan/{question}/edit', [QuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::put('/admin/kelola-pertanyaan/{question}', [QuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/admin/kelola-pertanyaan/{question}', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');

    // DATA PENGISIAN MURID (Data Mentah)
    Route::get('/admin/data-pengisian-murid', [StudentDataController::class, 'index'])->name('admin.data_pengisian_murid');
    Route::get('/admin/data-pengisian-murid/{studentData}', [StudentDataController::class, 'show'])->name('admin.student_data_detail');
    Route::delete('/admin/data-pengisian-murid/{studentData}', [StudentDataController::class, 'destroy'])->name('admin.student_data.destroy');
    
    // KELOLA KELAS (Resource)
    Route::resource('admin/kelas', AcademicClassController::class)->parameters([
        'kelas' => 'class'
    ])->names([
        'index' => 'admin.kelas.index',
        'create' => 'admin.kelas.create',
        'store' => 'admin.kelas.store',
        'edit' => 'admin.kelas.edit',
        'update' => 'admin.kelas.update',
        'destroy' => 'admin.kelas.destroy',
    ]);
    
    // LOG AKTIVITAS
    Route::get('/admin/log-aktivitas', [ActivityLogController::class, 'index'])->name('admin.log_aktivitas');
    Route::post('/admin/log-aktivitas/clear', [ActivityLogController::class, 'clearLogs'])->name('admin.log_aktivitas.clear');

    // MANAJEMEN USER ADMIN
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // DATA MINING
    Route::get('/admin/mining/klasifikasi', [DataMiningController::class, 'klasifikasiIndex'])->name('admin.mining.klasifikasi');
    Route::post('/admin/mining/klasifikasi/run', [DataMiningController::class, 'runClassification'])->name('admin.mining.klasifikasi.run');
    Route::get('/admin/mining/regresi', [DataMiningController::class, 'regresiIndex'])->name('admin.mining.regresi');
    Route::post('/admin/mining/regresi/run', [DataMiningController::class, 'runRegression'])->name('admin.mining.regresi.run');
    Route::get('/admin/mining/clustering', [DataMiningController::class, 'clusteringIndex'])->name('admin.mining.clustering');
    Route::post('/admin/mining/clustering/run', [DataMiningController::class, 'runClustering'])->name('admin.mining.clustering.run');
    Route::get('/admin/mining/asosiasi', [DataMiningController::class, 'asosiasiIndex'])->name('admin.mining.asosiasi');
    Route::post('/admin/mining/asosiasi/run', [DataMiningController::class, 'runAssociation'])->name('admin.mining.asosiasi.run');
    Route::get('/admin/mining/sekuensing', [DataMiningController::class, 'sekuensingIndex'])->name('admin.mining.sekuensing');
    Route::post('/admin/mining/sekuensing/run', [DataMiningController::class, 'runAnomaly'])->name('admin.mining.sekuensing.run'); 
    
    // PROFILE AKUN
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';