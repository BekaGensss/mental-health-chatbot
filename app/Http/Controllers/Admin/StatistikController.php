<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentData;
use App\Models\Question;
use App\Models\Option;
use App\Models\AcademicClass;
use App\Models\User; 
use App\Models\ActivityLog; 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\StudentsExport;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Filter Kelas
        $classFilter = $request->input('class_filter');
        $query = StudentData::query();
        
        if ($classFilter && $classFilter !== 'all') {
            $query->where('class_level', $classFilter);
        }

        // 2. Ambil Data Dasar dan Kelas Aktif
        $totalMurid = $query->count();
        $allClasses = AcademicClass::where('is_active', true)->pluck('name', 'name')->prepend('Semua Kelas', 'all');
        
        // Variabel tambahan untuk Dashboard Global
        $totalPengisianHariIni = StudentData::whereDate('created_at', today())->count();
        $totalPertanyaan = Question::count();
        $totalAkunAdmin = User::count();
        $totalKelasAktif = AcademicClass::where('is_active', true)->count();
        $totalLogAktivitas = ActivityLog::count(); 

        // 3. Cek jika tidak ada murid yang mengisi
        if ($totalMurid === 0) {
            $phq9Summary = $gad7Summary = ['Minimal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];
            $kpkSummary = ['Baik' => 0, 'Perlu Perbaikan' => 0];
            $dass21Summary = ['Normal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0]; 
            $totalRiskCount = 0;
            $avgPhq9 = $avgGad7 = $avgKpk = $avgDass21 = 0;
            $totalPhq9 = $totalGad7 = $totalKpk = $totalDass21 = 0; 
            $submissionsPerClass = []; 
            
            // Variabel real-time baru dengan nilai default nol
            $weeklySubmissions = [0, 0, 0, 0, 0, 0, 0];
            $weeklyLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            
            // Atur default agar Chart Radar muncul
            $kpkRadarData = [0, 0, 0, 0, 0];
            $kpkRadarLabels = ['PHQ-9 (Depresi)', 'GAD-7 (Kecemasan)', 'DASS-21 (Skor Rata)', 'KPK (Skor Rata)', 'Resiko Total'];


            return view('admin.hasil_jawaban', compact(
                'totalMurid', 'allClasses', 'classFilter', 'phq9Summary', 'gad7Summary', 'kpkSummary', 'dass21Summary', 
                'totalPengisianHariIni', 'totalPertanyaan', 'totalAkunAdmin', 'totalKelasAktif', 'totalLogAktivitas',
                'avgPhq9', 'avgGad7', 'avgKpk', 'avgDass21', 'totalRiskCount',
                'totalPhq9', 'totalGad7', 'totalKpk', 'totalDass21', 'submissionsPerClass',
                // Kirim variabel real-time baru
                'weeklySubmissions', 'weeklyLabels', 'kpkRadarData', 'kpkRadarLabels'
            ));
        }

        // 4. Ambil semua pertanyaan, opsi, dan data murid
        // Mengambil juga pertanyaan KPK yang akan digunakan untuk Radar Chart
        $questions = Question::with('options')->get()->keyBy('id');
        $kpkQuestions = $questions->where('type', 'kpk'); // Filter hanya pertanyaan KPK
        $options = Option::all()->keyBy('id');
        
        // Ambil data murid, termasuk 'answers'
        $allStudentData = $query->select('answers')->get();

        // 5. Inisialisasi Penghitung
        $phq9Summary = ['Minimal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];
        $gad7Summary = ['Minimal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];
        $kpkSummary = ['Baik' => 0, 'Perlu Perbaikan' => 0];
        $dass21Summary = ['Normal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0]; 
        $totalRiskCount = 0; 
        
        $totalScorePhq9 = 0; $totalScoreGad7 = 0; $totalScoreKpk = 0; $totalScoreDass21 = 0; 
        $totalPhq9Answers = 0; $totalGad7Answers = 0; $totalKpkAnswers = 0; $totalDass21Answers = 0;

        // Inisialisasi penghitung skor per dimensi KPK (tetap dipertahankan untuk jaga-jaga)
        $kpkDimTotals = [];
        $kpkDimCounts = [];

        // 6. Proses Data Murid: Menghitung skor per murid untuk semua skala
        foreach ($allStudentData as $studentData) {
            $phq9Score = 0; $gad7Score = 0; $kpkScore = 0; $dass21Score = 0;
            $answeredPhq9 = false; $answeredGad7 = false; 
            $answeredKpk = false; $answeredDass21 = false;

            $answers = is_array($studentData->answers) ? $studentData->answers : json_decode($studentData->answers, true);
            if (!$answers) continue; 

            foreach ($answers as $questionId => $optionId) {
                $question = $questions->get($questionId);
                $option = $options->get($optionId);
                
                if ($option && $question) {
                    $score = $option->score;
                    
                    switch ($question->type) {
                        case 'phq9': 
                            $phq9Score += $score; 
                            $totalScorePhq9 += $score;
                            $answeredPhq9 = true;
                            break;
                        case 'gad7': 
                            $gad7Score += $score; 
                            $totalScoreGad7 += $score;
                            $answeredGad7 = true;
                            break;
                        case 'kpk': 
                            $kpkScore += $score; 
                            $totalScoreKpk += $score; 
                            $answeredKpk = true;
                            
                            // Logika Real-Time KPK (Radar Chart): Akumulasi skor per dimensi (jika Question memiliki field 'dimension')
                            $dimension = $question->dimension ?? 'Lain-lain'; 
                            
                            $kpkDimTotals[$dimension] = ($kpkDimTotals[$dimension] ?? 0) + $score;
                            $kpkDimCounts[$dimension] = ($kpkDimCounts[$dimension] ?? 0) + 1;

                            break;
                        case 'dass21': 
                            $dass21Score += $score; 
                            $totalScoreDass21 += $score; 
                            $answeredDass21 = true;
                            break;
                    }
                }
            }
            
            // Akumulasi total responden sah
            if ($answeredPhq9) $totalPhq9Answers++;
            if ($answeredGad7) $totalGad7Answers++;
            if ($answeredKpk) $totalKpkAnswers++;
            if ($answeredDass21) $totalDass21Answers++;
            
            
            // --- KLASIFIKASI & SUMMARIZATION ---
            
            // Klasifikasi PHQ-9 (Menggunakan batas yang Anda pakai)
            if ($answeredPhq9) {
                if ($phq9Score >= 12) $phq9Summary['Berat']++;
                else if ($phq9Score >= 9) $phq9Summary['Sedang']++;
                else if ($phq9Score >= 6) $phq9Summary['Ringan']++;
                else $phq9Summary['Minimal']++;
            }

            // Klasifikasi GAD-7: KOREKSI UTAMA! Menggunakan batas standar (5, 10, 15)
            if ($answeredGad7) {
                if ($gad7Score >= 15) $gad7Summary['Berat']++;       // Skor 15 - 21
                else if ($gad7Score >= 10) $gad7Summary['Sedang']++; // Skor 10 - 14
                else if ($gad7Score >= 5) $gad7Summary['Ringan']++;  // Skor 5 - 9 
                else $gad7Summary['Minimal']++;                      // Skor 0 - 4
            }

            // KPK (TIDAK BERUBAH)
            if ($answeredKpk) {
                $kpkLimit = 8;
                if ($kpkScore > $kpkLimit) $kpkSummary['Baik']++;
                else $kpkSummary['Perlu Perbaikan']++;
            }

            // DASS-21: KOREKSI BATAS SKOR! Menggunakan batas DASS Depresi Mentah (max 21)
            if ($answeredDass21) {
                // Batas DASS-21 Depresi Mentah: Normal 0-4, Ringan 5-6, Sedang 7-10, Berat >=11
                if ($dass21Score >= 11) $dass21Summary['Berat']++;    
                else if ($dass21Score >= 7) $dass21Summary['Sedang']++; 
                else if ($dass21Score >= 5) $dass21Summary['Ringan']++; 
                else $dass21Summary['Normal']++;                      
            }
            
            // Hitung Risiko Tinggi (Sedang ke atas)
            if (($answeredPhq9 && $phq9Score >= 9) || ($answeredGad7 && $gad7Score >= 10)) { 
                 $totalRiskCount++;
            }
        }
        
        // 7. Hitung Totals & Averages
        // Mengirimkan total responden sebagai variabel terpisah untuk kalkulasi persentase di view
        $totalPhq9 = $totalPhq9Answers; 
        $totalGad7 = $totalGad7Answers;
        $totalKpk = $totalKpkAnswers; 
        $totalDass21 = $totalDass21Answers; 

        // Rata-rata Skor Mentah / Total Murid yang benar-benar menjawab
        $avgPhq9 = ($totalPhq9Answers > 0) ? $totalScorePhq9 / $totalPhq9Answers : 0; 
        $avgGad7 = ($totalGad7Answers > 0) ? $totalScoreGad7 / $totalGad7Answers : 0;
        
        $avgKpk = ($totalKpkAnswers > 0) ? $totalScoreKpk / $totalKpkAnswers : 0;
        $avgDass21 = ($totalDass21Answers > 0) ? $totalScoreDass21 / $totalDass21Answers : 0;
        
        // Data untuk Bar Chart Pengisian Per Kelas
        $submissionsPerClass = StudentData::select('class_level', DB::raw('count(*) as count'))
                                         ->groupBy('class_level')
                                         ->pluck('count', 'class_level')
                                         ->toArray();
        
        // --- START: LOGIKA REAL-TIME BARU (Disesuaikan untuk menghindari masalah dimensi kosong) ---
        
        // 8. Logika Real-Time KPK (Radar Chart) - MENGGUNAKAN SKALA GLOBAL
        // Normalisasi Skor ke Skala 0-5 (Contoh)
        
        // PHQ-9 (Max 27). Normalisasi: (Skor / 27) * 5
        $normPhq9 = ($avgPhq9 > 0) ? round(($avgPhq9 / 27) * 5, 2) : 0; 
        
        // GAD-7 (Max 21). Normalisasi: (Skor / 21) * 5
        $normGad7 = ($avgGad7 > 0) ? round(($avgGad7 / 21) * 5, 2) : 0;
        
        // KPK (Max asumsi 24/Jumlah item * 5 jika skala 1-5, kita pakai avgKPK / 5)
        // Jika KPK menggunakan skor 0-4 per item (max 36), Normalisasi: (Skor / 36) * 5
        // Karena kita tidak tahu max score KPK, kita gunakan AvgKPK. Kita asumsikan skor per item max 4.
        $normKpk = ($avgKpk > 0) ? round($avgKpk / 5, 2) : 0; 
        
        // DASS-21 (Depresi Max 21). Normalisasi: (Skor / 21) * 5
        $normDass21 = ($avgDass21 > 0) ? round(($avgDass21 / 21) * 5, 2) : 0;

        // Total Risiko Tinggi (Max totalMurid). Normalisasi: (Risiko / Total Murid) * 5
        $normRisk = ($totalMurid > 0) ? round(($totalRiskCount / $totalMurid) * 5, 2) : 0;
        
        // Mengirim data Skala Global sebagai sumbu Radar Chart (Max 5)
        $kpkRadarLabels = [
            'Rata-rata PHQ-9', 
            'Rata-rata GAD-7', 
            'Rata-rata DASS-21', 
            'Rata-rata KPK Global', 
            'Persentase Risiko Tinggi'
        ];
        
        // Data diisi dengan nilai yang sudah dinormalisasi (0-5)
        $kpkRadarData = [
            $normPhq9,
            $normGad7,
            $normDass21,
            $normKpk, 
            $normRisk
        ];


        // 9. Logika Real-Time Tren Pengisian Mingguan (Line Chart)
        $weeklySubmissionsQuery = StudentData::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $weeklyMap = $weeklySubmissionsQuery->pluck('count', 'date')->toArray();
        
        $weeklySubmissions = [];
        $weeklyLabels = [];
        
        // Isi data untuk 7 hari terakhir (Hari ini dan 6 hari sebelumnya)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->isoFormat('ddd'); 

            $weeklyLabels[] = $label;
            $weeklySubmissions[] = $weeklyMap[$date] ?? 0;
        }

        // --- END: LOGIKA REAL-TIME BARU ---
        
        // 10. Kembalikan Data ke View (TERMASUK VARIABEL REAL-TIME BARU)
        return view('admin.hasil_jawaban', compact(
            'totalMurid', 'allClasses', 'classFilter', 'totalRiskCount',
            'totalPengisianHariIni', 'totalPertanyaan', 'totalAkunAdmin', 'totalKelasAktif', 'totalLogAktivitas',
            
            'avgPhq9', 'avgGad7', 'avgKpk', 'avgDass21',
            
            // MENGIRIM RINGKASAN & TOTAL ANSWERS
            'phq9Summary', 'gad7Summary', 'kpkSummary', 'dass21Summary', 
            'totalPhq9', 'totalGad7', 'totalKpk', 'totalDass21',
            
            'submissionsPerClass',
            
            // VARIABEL REAL-TIME BARU (Skala Global)
            'weeklySubmissions', 'weeklyLabels', 'kpkRadarData', 'kpkRadarLabels'
        ));
    }
    
    /**
     * Export data mentah murid ke CSV/Excel menggunakan Laravel Excel.
     */
     public function exportData(Request $request)
     {
         $classFilter = $request->input('class_filter');
         $fileName = 'laporan_kuesioner_' . ($classFilter && $classFilter !== 'all' ? str_replace(' ', '_', $classFilter) : 'semua') . '_' . now()->format('Ymd') . '.xlsx';
         
         return Excel::download(new StudentsExport($classFilter), $fileName);
     }
}