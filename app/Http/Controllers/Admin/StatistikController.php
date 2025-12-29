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
        // 1. Ambil Filter Kelas dan Search Query
        $classFilter = $request->input('class_filter');
        $searchQuery = $request->input('search_query'); 
        
        $query = StudentData::query();
        
        // Terapkan Filter Kelas
        if ($classFilter && $classFilter !== 'all') {
            $query->where('class_level', $classFilter);
        }

        // Terapkan Pencarian (Search Query) - HANYA BERDASARKAN NAMA DAN KELAS
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                // Mencari berdasarkan nama murid ATAU kelas
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('class_level', 'like', '%' . $searchQuery . '%');
            });
        }

        // 2. Ambil Data Dasar dan Kelas Aktif
        $totalMurid = $query->count();
        $allClasses = AcademicClass::where('is_active', true)->pluck('name', 'name')->prepend('Semua Kelas', 'all');
        
        // Variabel tambahan untuk Dashboard Global (tidak berubah)
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
             
             $weeklySubmissions = [0, 0, 0, 0, 0, 0, 0];
             $weeklyLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
             $kpkRadarData = [0, 0, 0, 0, 0];
             $kpkRadarLabels = ['PHQ-9 (Depresi)', 'GAD-7 (Kecemasan)', 'DASS-21 (Skor Rata)', 'KPK (Skor Rata)', 'Resiko Total'];
             $studentDetails = collect(); 

            return view('admin.hasil_jawaban', compact(
                'totalMurid', 'allClasses', 'classFilter', 'phq9Summary', 'gad7Summary', 'kpkSummary', 'dass21Summary', 
                'totalPengisianHariIni', 'totalPertanyaan', 'totalAkunAdmin', 'totalKelasAktif', 'totalLogAktivitas',
                'avgPhq9', 'avgGad7', 'avgKpk', 'avgDass21', 'totalRiskCount',
                'totalPhq9', 'totalGad7', 'totalKpk', 'totalDass21', 'submissionsPerClass', 'searchQuery',
                'weeklySubmissions', 'weeklyLabels', 'kpkRadarData', 'kpkRadarLabels', 'studentDetails'
            ));
        }

        // 4. Ambil semua pertanyaan, opsi, dan data murid yang sudah difilter/dicari
        $questions = Question::with('options')->get()->keyBy('id');
        $kpkQuestions = $questions->where('type', 'kpk'); 
        $options = Option::all()->keyBy('id');
        
        // Ambil data murid LENGKAP untuk diproses dan ditampilkan di tabel
        $allStudentData = $query->get();
        
        // Inisialisasi array untuk menyimpan detail murid yang akan dikirim ke view
        $studentDetails = collect(); 

        // 5. Inisialisasi Penghitung
        $phq9Summary = ['Minimal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];
        $gad7Summary = ['Minimal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];
        $kpkSummary = ['Baik' => 0, 'Perlu Perbaikan' => 0];
        $dass21Summary = ['Normal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0]; 
        $totalRiskCount = 0; 
        
        $totalScorePhq9 = 0; $totalScoreGad7 = 0; $totalScoreKpk = 0; $totalScoreDass21 = 0; 
        $totalPhq9Answers = 0; $totalGad7Answers = 0; $totalKpkAnswers = 0; $totalDass21Answers = 0;

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
            
            $phq9Kategori = 'Belum Mengisi';
            $gad7Kategori = 'Belum Mengisi';
            $kpkKategori = 'Belum Mengisi';
            $dass21Kategori = 'Belum Mengisi';
            
            // Klasifikasi PHQ-9
            if ($answeredPhq9) {
                if ($phq9Score >= 12) { $phq9Summary['Berat']++; $phq9Kategori = 'Berat'; }
                else if ($phq9Score >= 9) { $phq9Summary['Sedang']++; $phq9Kategori = 'Sedang'; }
                else if ($phq9Score >= 6) { $phq9Summary['Ringan']++; $phq9Kategori = 'Ringan'; }
                else { $phq9Summary['Minimal']++; $phq9Kategori = 'Minimal'; }
            }

            // Klasifikasi GAD-7
            if ($answeredGad7) {
                if ($gad7Score >= 15) { $gad7Summary['Berat']++; $gad7Kategori = 'Berat'; }
                else if ($gad7Score >= 10) { $gad7Summary['Sedang']++; $gad7Kategori = 'Sedang'; } 
                else if ($gad7Score >= 5) { $gad7Summary['Ringan']++; $gad7Kategori = 'Ringan'; }
                else { $gad7Summary['Minimal']++; $gad7Kategori = 'Minimal'; }
            }

            // KPK 
            if ($answeredKpk) {
                $kpkLimit = 8;
                if ($kpkScore > $kpkLimit) { $kpkSummary['Baik']++; $kpkKategori = 'Baik'; }
                else { $kpkSummary['Perlu Perbaikan']++; $kpkKategori = 'Perlu Perbaikan'; }
            }

            // DASS-21
            if ($answeredDass21) {
                // Batas DASS-21 Depresi Mentah: Normal 0-4, Ringan 5-6, Sedang 7-10, Berat >=11
                if ($dass21Score >= 11) { $dass21Summary['Berat']++; $dass21Kategori = 'Berat'; } 
                else if ($dass21Score >= 7) { $dass21Summary['Sedang']++; $dass21Kategori = 'Sedang'; } 
                else if ($dass21Score >= 5) { $dass21Summary['Ringan']++; $dass21Kategori = 'Ringan'; }
                else { $dass21Summary['Normal']++; $dass21Kategori = 'Normal'; }
            }
            
            // Hitung Risiko Tinggi (Sedang ke atas)
            if (($answeredPhq9 && $phq9Score >= 9) || ($answeredGad7 && $gad7Score >= 10)) { 
                 $totalRiskCount++;
            }
            
            // Tambahkan data detail murid ke koleksi untuk tabel
            // HANYA MENGGUNAKAN 'name' (TIDAK ADA 'nis')
            $studentDetails->push([
                'id' => $studentData->id,
                'name' => $studentData->name, // Menggunakan 'name'
                'class' => $studentData->class_level,
                'phq9_kategori' => $phq9Kategori,
                'gad7_kategori' => $gad7Kategori,
                'dass21_kategori' => $dass21Kategori,
                'kpk_global' => $kpkKategori,
            ]);
        }
        
        // 7. Hitung Totals & Averages (Tidak Berubah)
        $totalPhq9 = $totalPhq9Answers; 
        $totalGad7 = $totalGad7Answers;
        $totalKpk = $totalKpkAnswers; 
        $totalDass21 = $totalDass21Answers; 

        $avgPhq9 = ($totalPhq9Answers > 0) ? $totalScorePhq9 / $totalPhq9Answers : 0; 
        $avgGad7 = ($totalGad7Answers > 0) ? $totalScoreGad7 / $totalGad7Answers : 0;
        
        $avgKpk = ($totalKpkAnswers > 0) ? $totalScoreKpk / $totalKpkAnswers : 0;
        $avgDass21 = ($totalDass21Answers > 0) ? $totalScoreDass21 / $totalDass21Answers : 0;
        
        // Data untuk Bar Chart Pengisian Per Kelas (Tidak Berubah)
        $submissionsPerClass = StudentData::select('class_level', DB::raw('count(*) as count'))
                                         ->groupBy('class_level')
                                         ->pluck('count', 'class_level')
                                         ->toArray();
        
        // 8. Logika Real-Time KPK (Radar Chart) - DISESUAIKAN DENGAN SEEDER BARU
// PHQ-9 (5 soal * 3) = 15
// GAD-7 (5 soal * 3) = 15
// DASS-21 (10 soal * 3) = 30
// KPK (5 soal * 3) = 15

        $normPhq9 = ($avgPhq9 > 0) ? round(($avgPhq9 / 15) * 5, 2) : 0; 
        $normGad7 = ($avgGad7 > 0) ? round(($avgGad7 / 15) * 5, 2) : 0;
        $normDass21 = ($avgDass21 > 0) ? round(($avgDass21 / 30) * 5, 2) : 0;
        $normKpk = ($avgKpk > 0) ? round(($avgKpk / 15) * 5, 2) : 0; 
        $normRisk = ($totalMurid > 0) ? round(($totalRiskCount / $totalMurid) * 5, 2) : 0;
        
        $kpkRadarLabels = [
            'Rata-rata PHQ-9', 
            'Rata-rata GAD-7', 
            'Rata-rata DASS-21', 
            'Rata-rata KPK Global', 
            'Persentase Risiko Tinggi'
        ];
        
        $kpkRadarData = [
            $normPhq9, $normGad7, $normDass21, $normKpk, $normRisk
        ];

        // 9. Logika Real-Time Tren Pengisian Mingguan (Line Chart) (Tidak Berubah)
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
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->isoFormat('ddd'); 

            $weeklyLabels[] = $label;
            $weeklySubmissions[] = $weeklyMap[$date] ?? 0;
        }

        // 10. Kembalikan Data ke View
        return view('admin.hasil_jawaban', compact(
            'totalMurid', 'allClasses', 'classFilter', 'totalRiskCount', 'searchQuery',
            'totalPengisianHariIni', 'totalPertanyaan', 'totalAkunAdmin', 'totalKelasAktif', 'totalLogAktivitas',
            
            'avgPhq9', 'avgGad7', 'avgKpk', 'avgDass21',
            
            'phq9Summary', 'gad7Summary', 'kpkSummary', 'dass21Summary', 
            'totalPhq9', 'totalGad7', 'totalKpk', 'totalDass21',
            
            'submissionsPerClass',
            
            'weeklySubmissions', 'weeklyLabels', 'kpkRadarData', 'kpkRadarLabels',
            'studentDetails'
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