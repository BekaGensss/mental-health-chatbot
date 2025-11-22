<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudentDataController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AcademicClassController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\UserController; 
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicPdfController; 
use App\Http\Controllers\ChatbotController;
// use App\Http\Controllers\PublicChatController; // DIHAPUS
// use App\Http\Controllers\Admin\LiveChatController; // DIHAPUS
use Illuminate\Support\Facades\Route;

// ----------------------------------------------------------------------
// RUTE PUBLIK (MURID)
// ----------------------------------------------------------------------
Route::get('/', [PublicController::class, 'showForm'])->name('form.show');
Route::post('/submit', [PublicController::class, 'submitForm'])->name('form.submit');
Route::get('/success', [PublicController::class, 'showSuccess'])->name('form.success');

// Rute Export PDF
Route::get('/export/pdf', [PublicPdfController::class, 'exportResult'])->name('form.export_pdf');

// RUTE BARU: FORM SKORING STATIS
Route::get('/scoring-form', [PublicController::class, 'showScoringForm'])->name('form.scoring_form'); 

// RUTE BARU: CHATBOT INTERAKSI (AJAX)
Route::post('/chat/interact', [ChatbotController::class, 'interact'])->name('chatbot.interact');

// ----------------------------------------------------------------------
// RUTE ADMIN (WAJIB LOGIN)
// ----------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menu ChatBot (HASIL JAWABAN PERTANYAAN)
    Route::get('/admin/data-murid', [StatistikController::class, 'index'])->name('admin.data_murid');
    Route::get('/admin/data-murid/export', [StatistikController::class, 'exportData'])->name('admin.data_murid.export');

    // KELOLA PERTANYAAN (CRUD Eksplisit)
    Route::get('/admin/kelola-pertanyaan', [QuestionController::class, 'index'])->name('admin.kelola_pertanyaan');
    Route::get('/admin/kelola-pertanyaan/create', [QuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/admin/kelola-pertanyaan', [QuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/admin/kelola-pertanyaan/{question}/edit', [QuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::put('/admin/kelola-pertanyaan/{question}', [QuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/admin/kelola-pertanyaan/{question}', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');

    // DATA PENGISIAN MURID (DATA MENTAH)
    Route::get('/admin/data-pengisian-murid', [StudentDataController::class, 'index'])->name('admin.data_pengisian_murid');
    Route::get('/admin/data-pengisian-murid/{studentData}', [StudentDataController::class, 'show'])->name('admin.student_data_detail');
    Route::delete('/admin/data-pengisian-murid/{studentData}', [StudentDataController::class, 'destroy'])->name('admin.student_data.destroy'); 
    
    // KELOLA KELAS (CRUD menggunakan Resource)
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

    // MANAJEMEN AKUN ADMIN (CRUD LENGKAP)
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index'); 
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create'); 
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit'); 
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update'); 
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy'); 


    // Profile Settings (Untuk akun yang sedang login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
});

require __DIR__.'/auth.php';