<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\StudentData; 
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PublicController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran dan pertanyaan (Interface Chatbot).
     */
    public function showForm()
    {
        // Ambil semua pertanyaan yang sudah diurutkan, beserta opsinya (untuk JS/skoring)
        $questions = Question::with('options')->orderBy('order')->get();
        
        // Data Kelas (Dropdown List)
        $classes = AcademicClass::where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name')
                                ->all(); 

        return view('public_form', compact('questions', 'classes'));
    }
    
    /**
     * Menampilkan formulir kuesioner PHQ-9/GAD-7 statis multi-step.
     */
    public function showScoringForm()
    {
        // Ambil data yang dibutuhkan sama seperti showForm
        $questions = Question::with('options')->orderBy('order')->get();
        $classes = AcademicClass::where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name')
                                ->all(); 

        return view('public_scoring_form', compact('questions', 'classes'));
    }

    /**
     * Menangani pengiriman formulir dari murid.
     * Logika skoring untuk PHQ9, GAD7, KPK, dan DASS21
     */
    public function submitForm(Request $request)
    {
        // Mendapatkan total jumlah pertanyaan untuk validasi array 'answers'
        $questionCount = Question::count();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'class_level' => 'required|string|max:10',
            // Memastikan jumlah jawaban sesuai dengan jumlah pertanyaan (seharusnya 25)
            'answers' => 'required|array|size:' . $questionCount, 
        ]);

        // Inisialisasi semua variabel skor
        $totalScore = 0;
        $phq9Score = 0;
        $gad7Score = 0;
        $kpkScore = 0;
        $dass21Score = 0;

        // Ambil semua pertanyaan dan opsinya
        $questions = Question::with('options')->get()->keyBy('id');

        foreach ($request->answers as $questionId => $optionId) {
            $question = $questions->get($questionId);
            
            if (!$question) {
                continue; 
            }

            $option = $question->options->where('id', $optionId)->first();
            
            if ($option) {
                $score = $option->score;
                $totalScore += $score;

                // Logika perhitungan skor berdasarkan TIPE pertanyaan
                switch ($question->type) {
                    case 'phq9':
                        $phq9Score += $score;
                        break;
                    case 'gad7':
                        $gad7Score += $score;
                        break;
                    case 'kpk':
                        $kpkScore += $score;
                        break;
                    case 'dass21':
                        $dass21Score += $score;
                        break;
                }
            }
        }
        
        // Analisis hasil dan dapatkan ringkasan teks
        $resultSummary = $this->analyzeResults($phq9Score, $gad7Score, $kpkScore, $dass21Score);

        // --- Simpan data murid dan tangkap ID-nya ---
        $studentData = StudentData::create([
            'name' => $request->name,
            'class_level' => $request->class_level,
            'answers' => $request->answers,
            'result_summary' => "Total Score: $totalScore. PHQ9: $phq9Score, GAD7: $gad7Score, KPK: $kpkScore, DASS21: $dass21Score. Ringkasan: $resultSummary",
        ]);
        
        // Kirim semua skor dan ID MURID ke halaman sukses (untuk PDF)
        return redirect()->route('form.success', [
            'totalScore' => $totalScore,
            'phq9Score' => $phq9Score, 
            'gad7Score' => $gad7Score, 
            'kpkScore' => $kpkScore, 
            'dass21Score' => $dass21Score, 
            'summary' => $resultSummary, 
            'studentId' => $studentData->id, // ID MURID WAJIB
        ]);
    }
    
    /**
     * Helper: Logika analisis hasil berdasarkan skor (Diperbarui untuk 4 skala).
     */
    private function analyzeResults(int $phq9, int $gad7, int $kpk, int $dass21): string
    {
        // Ambang Batas disesuaikan dengan Max Score Baru (PHQ9 Max 15, GAD7 Max 15, KPK Max 15, DASS21 Max 30)
        
        // 1. Logika PHQ-9 (Max Score 15)
        $phq9Result = match (true) {
            $phq9 >= 12 => 'Depresi Berat',      // Disesuaikan dari 20
            $phq9 >= 8 => 'Depresi Sedang-Berat', // Disesuaikan dari 15
            $phq9 >= 5 => 'Depresi Sedang',       // Disesuaikan dari 10
            $phq9 >= 3 => 'Depresi Ringan',       // Disesuaikan dari 5
            default => 'Minimal Depresi',
        };

        // 2. Logika GAD-7 (Max Score 15)
        $gad7Result = match (true) {
            $gad7 >= 12 => 'Cemas Berat',        // Disesuaikan dari 15 (Max 21)
            $gad7 >= 8 => 'Cemas Sedang',         // Disesuaikan dari 10
            $gad7 >= 4 => 'Cemas Ringan',         // Disesuaikan dari 5
            default => 'Minimal Cemas',
        };
        
        // 3. Logika KPK (Max Score 15)
        $kpkResult = $kpk > 10 ? 'Perilaku Kesehatan Baik' : 'Perlu Perbaikan Perilaku Kesehatan'; 
        
        // 4. Logika DASS-21 (Max Score 30)
        $dass21Result = match (true) {
            $dass21 >= 22 => 'Stres/Cemas/Depresi Ekstrem', // Disesuaikan dari 45
            $dass21 >= 15 => 'Stres/Cemas/Depresi Berat',   // Disesuaikan dari 35
            $dass21 >= 10 => 'Stres/Cemas/Depresi Sedang',  // Disesuaikan dari 25
            $dass21 >= 5 => 'Stres/Cemas/Depresi Ringan',   // Disesuaikan dari 15
            default => 'Normal',
        };

        return "PHQ9: $phq9Result, GAD7: $gad7Result, KPK: $kpkResult, DASS21: $dass21Result.";
    }

    /**
     * Menampilkan halaman sukses setelah pengisian formulir.
     */
    public function showSuccess(Request $request)
    {
        // Ambil semua data dari query string
        $totalScore = $request->get('totalScore');
        $phq9Score = $request->get('phq9Score');
        $gad7Score = $request->get('gad7Score');
        $kpkScore = $request->get('kpkScore');
        $dass21Score = $request->get('dass21Score');
        $resultSummary = $request->get('summary');
        
        // Pengecekan dasar jika data hilang di query string
        if (!$resultSummary || $totalScore === null) {
            return redirect('/')->with('error', 'Gagal memuat hasil. Silakan ulangi pengisian formulir.');
        }

        // --- Klasifikasi Ulang untuk Data Diagram (Persentase) ---
        // NILAI MAX DISINKRONKAN DENGAN SEEDER BARU (Total 75)
        $phq9Max = 15;
        $gad7Max = 15;
        $kpkMax = 15;
        $dass21Max = 30; 

        // Perhitungan Persentase Risiko
        $phq9Percent = ($phq9Score / $phq9Max) * 100;
        $gad7Percent = ($gad7Score / $gad7Max) * 100;
        $kpkPercent = ($kpkScore / $kpkMax) * 100;
        $dass21Percent = ($dass21Score / $dass21Max) * 100;

        // Ambil ID Murid dari URL
        $studentId = $request->get('studentId'); 

        // ===================================================
        // >>> PENAMBAHAN KODE KRITIS UNTUK DATA MURID <<<
        // ===================================================
        $studentName = 'Data Tidak Ditemukan'; // Nilai default
        $studentClass = 'N/A'; // Nilai default

        if ($studentId) {
            // Cari data murid di database berdasarkan ID
            $studentData = StudentData::find($studentId);
            
            if ($studentData) {
                // Jika ditemukan, ambil Nama dan Kelas
                $studentName = $studentData->name;
                $studentClass = $studentData->class_level;
            }
        }
        // ===================================================

        return view('public_success', compact(
            'totalScore', 
            'resultSummary', 
            'phq9Score', 
            'gad7Score',
            'kpkScore',
            'dass21Score',
            'phq9Percent', 
            'gad7Percent',
            'kpkPercent',
            'dass21Percent',
            'studentId', // WAJIB DIKIRIM KE VIEW
            // >>> KIRIM VARIABEL NAMA DAN KELAS KE VIEW <<<
            'studentName', 
            'studentClass' 
        ));
    }
}