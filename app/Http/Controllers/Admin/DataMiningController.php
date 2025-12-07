<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassificationResult;
use App\Models\RegressionResult;
use App\Models\ClusteringResult;
use App\Models\AssociationResult;
use App\Models\AnomalyResult;       // Model Anomali
use App\Models\StudentData;
use App\Models\Question;
use App\Models\Option;
use App\Traits\Loggable;
use Illuminate\Support\Facades\DB;

class DataMiningController extends Controller
{
    use Loggable;

    // ======================================================================
    // >>> HELPER: MENGAMBIL SKOR DARI DATA MURID MENTAH (ANSWERS) <<<
    // ======================================================================
    protected function getScoresFromStudentData(StudentData $studentData)
    {
        $phq9Score = 0; $gad7Score = 0; $kpkScore = 0; $dass21Score = 0;
        
        $questions = Question::with('options')->get()->keyBy('id');
        $options = Option::all()->keyBy('id');
        
        $answers = is_array($studentData->answers) ? $studentData->answers : json_decode($studentData->answers, true);
        
        if (!$answers) {
            return ['phq9' => 0, 'gad7' => 0, 'dass21' => 0, 'kpk' => 0];
        }

        foreach ($answers as $questionId => $optionId) {
            $question = $questions->get($questionId);
            $option = $options->get($optionId);
            
            if ($option && $question) {
                $score = $option->score;
                switch ($question->type) {
                    case 'phq9': $phq9Score += $score; break;
                    case 'gad7': $gad7Score += $score; break;
                    case 'kpk': $kpkScore += $score; break;
                    case 'dass21': $dass21Score += $score; break;
                }
            }
        }
        
        return [
            'phq9' => $phq9Score, 
            'gad7' => $gad7Score, 
            'dass21' => $dass21Score, 
            'kpk' => $kpkScore
        ];
    }
    
    // ======================================================================
    // 1. KLASIFIKASI (PREDIKSI RISIKO)
    // ======================================================================

    public function klasifikasiIndex(Request $request)
    {
        $search = $request->query('search');
        $filterRisk = $request->query('filter_risk');

        $query = ClassificationResult::with('studentData')
                                     ->orderBy('created_at', 'desc');

        // Logic Pencarian (Berdasarkan Nama Murid)
        if ($search) {
            $query->whereHas('studentData', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        
        // Logic Filtering Berdasarkan Label Risiko
        if ($filterRisk) {
            $query->where('predicted_label', $filterRisk);
        }
        
        $results = $query->paginate(15)->withQueryString();
                                            
        // Perhitungan untuk Statistik & Visualisasi
        $totalResults = ClassificationResult::count();
        $highRiskCount = ClassificationResult::where('predicted_label', 'Risiko Tinggi')->count();
        $mediumRiskCount = ClassificationResult::where('predicted_label', 'Risiko Sedang')->count();
        $lowRiskCount = ClassificationResult::where('predicted_label', 'Risiko Rendah')->count();
        
        $totalRisiko = $highRiskCount + $mediumRiskCount;
        $riskPercentage = $totalResults > 0 ? round(($totalRisiko / $totalResults) * 100, 2) : 0;

        // Data Ringkasan untuk Visualisasi
        $riskSummary = [
            'labels' => ['Risiko Tinggi', 'Risiko Sedang', 'Risiko Rendah'],
            'data' => [$highRiskCount, $mediumRiskCount, $lowRiskCount]
        ];

        return view('admin.mining.klasifikasi', [
            'title' => 'Klasifikasi (Prediksi Risiko)',
            'results' => $results,
            'totalResults' => $totalResults,
            'highRiskCount' => $highRiskCount,
            'mediumRiskCount' => $mediumRiskCount,
            'riskPercentage' => $riskPercentage,
            'search' => $search, 
            'filterRisk' => $filterRisk, 
            'riskSummary' => $riskSummary, 
        ]);
    }

    public function runClassification(Request $request)
    {
        $dataToAnalyze = StudentData::whereNotNull('answers')->get();
        // ClassificationResult::truncate(); 

        $countProcessed = 0;
        
        foreach ($dataToAnalyze as $studentData) {
            
            $scores = $this->getScoresFromStudentData($studentData);
            
            $phq9 = $scores['phq9'];
            $gad7 = $scores['gad7'];
            $dass21 = $scores['dass21'];
            $kpk = $scores['kpk'];
            
            // LOGIKA PREDETEKSI RISIKO MULTIVARIAT SEDERHANA (THRESHOLD)
            if (($phq9 >= 9) || ($gad7 >= 10)) { 
                $predictedLabel = 'Risiko Tinggi';
                $riskProbability = rand(7000, 9999) / 10000;
            } 
            else if (($dass21 >= 10) || ($kpk <= 10)) {
                $predictedLabel = 'Risiko Sedang';
                $riskProbability = rand(5000, 7000) / 10000;
            }
            else {
                $predictedLabel = 'Risiko Rendah';
                $riskProbability = rand(100, 4999) / 10000; 
            }

            ClassificationResult::updateOrCreate( // Menggunakan updateOrCreate
                ['student_data_id' => $studentData->id],
                [
                    'phq9_score' => $phq9,
                    'gad7_score' => $gad7,
                    'dass21_score' => $dass21,
                    'kpk_score' => $kpk,
                    'predicted_label' => $predictedLabel,
                    'risk_probability' => $riskProbability,
                    'algorithm_used' => 'Simulasi Threshold Multivariat',
                ]
            );
            $countProcessed++;
        }
        
        $this->recordActivity('CREATE', "Menjalankan proses Klasifikasi (Prediksi Risiko) pada {$countProcessed} data murid.", null);

        return redirect()->route('admin.mining.klasifikasi')->with('success', "Proses Klasifikasi berhasil dijalankan pada {$countProcessed} data murid.");
    }
    
    // ======================================================================
    // 2. REGRESI (PREDIKSI NILAI KONTINU)
    // ======================================================================

    public function regresiIndex(Request $request)
    {
        $search = $request->query('search');
        $filterValue = $request->query('filter_value'); // Mengambil filter nilai yang benar
        
        // Catatan: Variabel filterRisk dari permintaan sebelumnya diganti menjadi filterValue
        // untuk fungsionalitas Regresi yang benar (memfilter nilai prediksi).

        $query = RegressionResult::with('studentData')
                                     ->orderBy('predicted_value', 'desc'); // Diubah: Urutkan berdasarkan Nilai Prediksi

        // Logic Pencarian (Berdasarkan Nama Murid)
        if ($search) {
            $query->whereHas('studentData', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        
        // Logic Filtering Berdasarkan Nilai Prediksi (Filter Nilai)
        if ($filterValue == 'Tinggi') {
            // Nilai prediksi tinggi (misalnya >= 80)
            $query->where('predicted_value', '>=', 80);
        } elseif ($filterValue == 'Rendah') {
            // Nilai prediksi rendah (misalnya < 65)
            $query->where('predicted_value', '<', 65);
        }

        $results = $query->paginate(15)->withQueryString();
                                            
        // Perhitungan untuk Statistik & Visualisasi
        $totalResults = RegressionResult::count();
        $avgMse = RegressionResult::avg('mean_squared_error') ?? 0;
        $avgRsq = RegressionResult::avg('r_squared') ?? 0;
        
        // Data Ringkasan untuk Visualisasi (Scatter/Histogram Prediksi)
        $chartData = RegressionResult::select('predicted_value')->orderBy('created_at', 'desc')->take(50)->get()->pluck('predicted_value');

        return view('admin.mining.regresi', [
            'title' => 'Regresi (Prediksi Nilai Kontinu)',
            'results' => $results,
            'totalResults' => $totalResults,
            'avgMse' => round($avgMse, 4),
            'avgRsq' => round($avgRsq, 4),
            'search' => $search, // Kirim variabel search ke view
            'filterValue' => $filterValue, // Kirim variabel filterValue (untuk dropdown)
            'chartData' => $chartData, // Data untuk Visualisasi
        ]);
    }

    public function runRegression(Request $request)
    {
        $dataToAnalyze = StudentData::whereNotNull('answers')->get();
        RegressionResult::truncate(); 

        $countProcessed = 0;
        
        foreach ($dataToAnalyze as $studentData) {
            
            $scores = $this->getScoresFromStudentData($studentData);
            
            $phq9 = $scores['phq9'];
            $gad7 = $scores['gad7'];
            $dass21 = $scores['dass21'];
            $kpk = $scores['kpk'];
            
            // Logika Regresi (Simulasi)
            $mentalScorePenalty = ($phq9 + $gad7 + $dass21) / 3; 
            $kpkBonus = $kpk * 3; 
            
            $predictedValue = 75 + $kpkBonus - $mentalScorePenalty;
            $predictedValue = max(50, min(95, $predictedValue)); 
            
            // Simulasi Metrik Evaluasi 
            $mse = rand(500, 1500) / 1000; 
            $r_squared = rand(6000, 8500) / 10000; 

            RegressionResult::updateOrCreate( // Perbaikan: Menggunakan updateOrCreate untuk integritas data
                ['student_data_id' => $studentData->id],
                [
                    'phq9_score' => $phq9,
                    'gad7_score' => $gad7,
                    'dass21_score' => $dass21,
                    'kpk_score' => $kpk,
                    'predicted_value' => $predictedValue,
                    'mean_squared_error' => $mse,
                    'r_squared' => $r_squared,
                    'algorithm_used' => 'Simulasi Regresi Linier',
                ]
            );
            $countProcessed++;
        }
        
        $this->recordActivity('CREATE', "Menjalankan proses Regresi (Prediksi Nilai Akademik) pada {$countProcessed} data murid.", null);

        return redirect()->route('admin.mining.regresi')->with('success', "Proses Regresi berhasil dijalankan pada {$countProcessed} data murid.");
    }
    
    // ======================================================================
    // 3. CLUSTERING (PENGELOMPOKAN PROFIL)
    // ======================================================================

    public function clusteringIndex(Request $request)
    {
        $search = $request->query('search');
        $filterCluster = $request->query('filter_cluster');

        $query = ClusteringResult::with('studentData')
                                     ->orderBy('cluster_id')
                                     ->orderBy('distance_to_centroid');

        // Logic Pencarian (Berdasarkan Nama Murid)
        if ($search) {
            $query->whereHas('studentData', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        
        // Logic Filtering Berdasarkan Label Cluster
        if ($filterCluster) {
            $query->where('cluster_label', $filterCluster);
        }

        $results = $query->paginate(15)->withQueryString();
                                            
        $totalResults = ClusteringResult::count();
        
        // Data Ringkasan untuk Visualisasi (Bar/Pie Chart Cluster Distribution)
        $summary = ClusteringResult::select('cluster_label', DB::raw('count(*) as count'), DB::raw('AVG(phq9_score) as avg_phq9'), DB::raw('AVG(gad7_score) as avg_gad7'))
                                           ->groupBy('cluster_label')
                                           ->orderBy('cluster_label')
                                           ->get();

        return view('admin.mining.clustering', [
            'title' => 'Clustering (Pengelompokan Profil)',
            'results' => $results,
            'totalResults' => $totalResults,
            'summary' => $summary, // Data untuk Visualisasi
            'search' => $search, // Kirim variabel search ke view
            'filterCluster' => $filterCluster,
        ]);
    }

    public function runClustering(Request $request)
    {
        $dataToAnalyze = StudentData::whereNotNull('answers')->get();
        ClusteringResult::truncate(); 

        $countProcessed = 0;
        
        foreach ($dataToAnalyze as $studentData) {
            
            $scores = $this->getScoresFromStudentData($studentData);
            
            $phq9 = $scores['phq9'];
            $gad7 = $scores['gad7'];
            $dass21 = $scores['dass21'];
            $kpk = $scores['kpk'];

            // LOGIKA SIMULASI CLUSTERING (K-MEANS SEDERHANA)
            $totalMentalRisk = $phq9 + $gad7 + $dass21;
            $clusterId = 0; 
            
            if ($totalMentalRisk >= 30) {
                $clusterId = 3;
                $clusterLabel = 'Profil Risiko Tinggi (Rentan)';
            } else if ($totalMentalRisk >= 10) {
                $clusterId = 2;
                $clusterLabel = 'Profil Perlu Perhatian';
            } else {
                $clusterId = 1;
                $clusterLabel = 'Profil Sehat';
            }
            
            $distance = rand(500, 2000) / 10000; 

            ClusteringResult::updateOrCreate( // Perbaikan: Menggunakan updateOrCreate untuk integritas data
                ['student_data_id' => $studentData->id],
                [
                    'phq9_score' => $phq9,
                    'gad7_score' => $gad7,
                    'dass21_score' => $dass21,
                    'kpk_score' => $kpk,
                    'cluster_id' => $clusterId,
                    'cluster_label' => $clusterLabel,
                    'distance_to_centroid' => $distance,
                    'algorithm_used' => 'Simulasi K-Means (3 Clusters)',
                ]
            );
            $countProcessed++;
        }
        
        $this->recordActivity('CREATE', "Menjalankan proses Clustering (Pengelompokan Profil) pada {$countProcessed} data murid.", null);

        return redirect()->route('admin.mining.clustering')->with('success', "Proses Clustering berhasil dijalankan pada {$countProcessed} data murid.");
    }
    
    // ======================================================================
    // 4. ASOSIASI POLA (HUBUNGAN JAWABAN)
    // ======================================================================

    public function asosiasiIndex(Request $request)
    {
        $search = $request->query('search');
        $filterConfidence = $request->query('filter_confidence');

        $query = AssociationResult::orderBy('confidence_value', 'desc')
                                     ->orderBy('lift_value', 'desc');

        // Logic Pencarian (Berdasarkan Antecedent/Consequent)
        if ($search) {
            $query->where('antecedent', 'like', '%' . $search . '%')
                  ->orWhere('consequent', 'like', '%' . $search . '%');
        }
        
        // Logic Filtering Berdasarkan Confidence Value
        if ($filterConfidence == 'Tinggi') {
            // Confidence Tinggi >= 0.8
            $query->where('confidence_value', '>=', 0.8);
        } elseif ($filterConfidence == 'Rendah') {
            // Confidence Rendah < 0.8
            $query->where('confidence_value', '<', 0.8);
        }
        
        $results = $query->paginate(15)->withQueryString();
                                            
        // Perhitungan untuk Statistik & Visualisasi
        $totalRules = AssociationResult::count();
        $highConfidenceRules = AssociationResult::where('confidence_value', '>=', 0.8)->count();
        $lowConfidenceRules = AssociationResult::where('confidence_value', '<', 0.8)->count();
        
        // Data Ringkasan untuk Visualisasi (Bar Chart/Histogram Confidence)
        $confidenceSummary = [
            'labels' => ['Confidence Tinggi (>= 0.8)', 'Confidence Rendah (< 0.8)'],
            'data' => [$highConfidenceRules, $lowConfidenceRules],
            'confidence_values' => AssociationResult::select('confidence_value')->pluck('confidence_value')->toArray()
        ];


        return view('admin.mining.asosiasi', [
            'title' => 'Asosiasi Pola (Hubungan Jawaban)',
            'results' => $results,
            'totalRules' => $totalRules,
            'highConfidenceRules' => $highConfidenceRules,
            'search' => $search, // Kirim variabel search ke view
            'filterConfidence' => $filterConfidence,
            'confidenceSummary' => $confidenceSummary, // Data untuk Visualisasi
        ]);
    }

    public function runAssociation(Request $request)
    {
        AssociationResult::truncate(); 

        $countProcessed = 0;
        
        $simulatedRules = [
            ['ant' => 'Depresi Sedang (PHQ9>=6)', 'con' => 'Kecemasan Sedang (GAD7>=5)', 'conf' => 0.85, 'lift' => 1.5],
            ['ant' => 'DASS21 (Skor>=10)', 'con' => 'KPK (Skor<=10)', 'conf' => 0.78, 'lift' => 1.2],
            ['ant' => 'Ide Bunuh Diri (PHQ9_Q5=Ya)', 'con' => 'Kecemasan Berat (GAD7>=12)', 'conf' => 0.92, 'lift' => 2.1],
        ];

        foreach ($simulatedRules as $rule) {
            AssociationResult::updateOrCreate( // Perbaikan: Menggunakan updateOrCreate
                ['antecedent' => $rule['ant'], 'consequent' => $rule['con']],
                [
                    'support_value' => rand(1500, 4500) / 10000, 
                    'confidence_value' => $rule['conf'],
                    'lift_value' => $rule['lift'],
                    'algorithm_used' => 'Simulasi Apriori',
                ]
            );
            $countProcessed++;
        }
        
        $this->recordActivity('CREATE', "Menjalankan proses Asosiasi Pola pada {$countProcessed} aturan yang ditemukan.", null);

        return redirect()->route('admin.mining.asosiasi')->with('success', "Proses Asosiasi Pola berhasil dijalankan. Ditemukan {$countProcessed} aturan.");
    }
    
    // ======================================================================
    // 5. DETEKSI ANOMALI/SEKUENSING
    // ======================================================================

    /**
     * Menampilkan halaman Deteksi Anomali/Sekuensing.
     */
    public function sekuensingIndex(Request $request)
    {
        $search = $request->query('search');
        $filterAnomaly = $request->query('filter_anomaly');

        $query = AnomalyResult::with('studentData')
                              ->orderBy('anomaly_score', 'desc');

        // Logic Pencarian (Berdasarkan Nama Murid)
        if ($search) {
            $query->whereHas('studentData', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        
        // Logic Filtering Berdasarkan Status Anomali
        if ($filterAnomaly) {
            $query->where('is_anomaly', $filterAnomaly);
        }
        
        $results = $query->paginate(15)->withQueryString();
                                            
        // Perhitungan untuk Statistik & Visualisasi
        $totalResults = AnomalyResult::count();
        $anomalyCount = AnomalyResult::where('is_anomaly', 'YES')->count();
        $normalCount = AnomalyResult::where('is_anomaly', 'NO')->count();
        $anomalyPercentage = $totalResults > 0 ? round(($anomalyCount / $totalResults) * 100, 2) : 0;

        // Data Ringkasan untuk Visualisasi (Pie Chart Anomali)
        $anomalySummary = [
            'labels' => ['Anomali (YES)', 'Normal (NO)'],
            'data' => [$anomalyCount, $normalCount],
            'anomaly_scores' => AnomalyResult::select('anomaly_score')->pluck('anomaly_score')->toArray()
        ];


        return view('admin.mining.sekuensing', [
            'title' => 'Deteksi Anomali/Sekuensing',
            'results' => $results,
            'totalResults' => $totalResults,
            'anomalyCount' => $anomalyCount,
            'anomalyPercentage' => $anomalyPercentage,
            'search' => $search, // Kirim variabel search ke view
            'filterAnomaly' => $filterAnomaly,
            'anomalySummary' => $anomalySummary, // Data untuk Visualisasi
        ]);
    }

    /**
     * Menjalankan proses Deteksi Anomali.
     */
    public function runAnomaly(Request $request)
    {
        $dataToAnalyze = StudentData::whereNotNull('answers')->get();
        // !!! KODE AnomalyResult::truncate(); DIHAPUS agar data lama tidak hilang !!!

        $countProcessed = 0;
        
        foreach ($dataToAnalyze as $studentData) {
            
            $is_anomaly = 'NO';
            $anomaly_reason = 'Normal';
            $anomaly_score = rand(1000, 5000) / 10000; 
            
            // LOGIKA SIMULASI DETEKSI ANOMALI
            if ($studentData->id % 3 === 0 || $studentData->id % 5 === 0) {
                $is_anomaly = 'YES';
                $anomaly_reason = 'Pola Outlier Skala (Skor Ekstrem)';
                $anomaly_score = rand(7000, 9999) / 10000; 
            }
            
            AnomalyResult::updateOrCreate( // MENGGANTI create() menjadi updateOrCreate()
                ['student_data_id' => $studentData->id], // Kunci Pencarian
                [
                    'is_anomaly' => $is_anomaly,
                    'anomaly_reason' => $anomaly_reason,
                    'anomaly_score' => $anomaly_score,
                    'algorithm_used' => 'Simulasi One-Class SVM', 
                ]
            );
            $countProcessed++;
        }
        
        $countAnomaly = AnomalyResult::where('is_anomaly', 'YES')->count();

        $this->recordActivity('CREATE', "Menjalankan proses Deteksi Anomali/Sekuensing pada {$countProcessed} data murid.", null);

        return redirect()->route('admin.mining.sekuensing')->with('success', "Proses Deteksi Anomali berhasil dijalankan pada {$countProcessed} data murid. Ditemukan {$countAnomaly} anomali.");
    }
}