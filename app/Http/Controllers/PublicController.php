<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\StudentData; 
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran dan pertanyaan (Interface Chatbot).
     */
    public function showForm()
    {
        $questions = Question::with('options')->orderBy('order')->get();
        $classes = AcademicClass::where('is_active', true)->orderBy('name')->pluck('name')->all(); 

        return view('public_form', compact('questions', 'classes'));
    }
    
    /**
     * Menampilkan formulir kuesioner PHQ-9/GAD-7 statis multi-step.
     */
    public function showScoringForm()
    {
        $questions = Question::with('options')->orderBy('order')->get();
        $classes = AcademicClass::where('is_active', true)->orderBy('name')->pluck('name')->all(); 

        return view('public_scoring_form', compact('questions', 'classes'));
    }

    /**
     * Menangani pengiriman formulir, Kalkulasi Skor, dan Integrasi Gemini AI (NLP).
     */
    public function submitForm(Request $request)
    {
        $questionCount = Question::count();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'class_level' => 'required|string|max:255',
            'answers' => 'required|array|size:' . $questionCount, 
        ]);

        // Inisialisasi variabel skor
        $scores = ['phq9' => 0, 'gad7' => 0, 'kpk' => 0, 'dass21' => 0];
        $totalScore = 0;
        $contextText = []; // Konteks untuk AI

        $questions = Question::with('options')->get()->keyBy('id');

        foreach ($request->answers as $questionId => $optionId) {
            $question = $questions->get($questionId);
            if (!$question) continue;

            $option = $question->options->where('id', $optionId)->first();
            if ($option) {
                $scoreValue = (int)$option->score;
                $totalScore += $scoreValue;
                
                // Pastikan tipe kategori ada di array scores
                if (array_key_exists($question->type, $scores)) {
                    $scores[$question->type] += $scoreValue;
                }
                
                // Kumpulkan konteks jika skor menunjukkan gejala menengah-berat
                if ($scoreValue >= 2) {
                    $contextText[] = "Gejala: {$question->content} (Respon: {$option->text})";
                }
            }
        }
        
        // 1. Dapatkan Analisis Rule-based
        $resultSummaryText = $this->analyzeResults($scores['phq9'], $scores['gad7'], $scores['kpk'], $scores['dass21']);

        // 2. INTEGRASI NLP GEMINI AI
        $aiResult = $this->analyzeWithGemini($scores, $contextText, $request->name);

        // 3. Simpan data ke Database
        $studentData = StudentData::create([
            'name' => $request->name,
            'class_level' => $request->class_level,
            'answers' => $request->answers,
            'scores' => $scores, // Array otomatis jadi JSON via Model Cast
            'ai_analysis' => $aiResult['analysis'], 
            'mental_status' => $aiResult['status'], 
            'result_summary' => "Total: $totalScore. $resultSummaryText",
        ]);
        
        // Redirect ke sukses dengan menyertakan studentId
        return redirect()->route('form.success', [
            'studentId' => $studentData->id,
            'totalScore' => $totalScore,
            'phq9Score' => $scores['phq9'], 
            'gad7Score' => $scores['gad7'], 
            'kpkScore' => $scores['kpk'], 
            'dass21Score' => $scores['dass21'], 
            'summary' => $resultSummaryText, 
        ]);
    }
    
    /**
     * Helper: Logika analisis hasil berdasarkan skor (Rule-based).
     */
    private function analyzeResults(int $phq9, int $gad7, int $kpk, int $dass21): string
    {
        $phq9Result = match (true) {
            $phq9 >= 12 => 'Depresi Berat',
            $phq9 >= 8 => 'Depresi Sedang',
            $phq9 >= 4 => 'Depresi Ringan',
            default => 'Normal',
        };

        $gad7Result = match (true) {
            $gad7 >= 12 => 'Cemas Berat',
            $gad7 >= 8 => 'Cemas Sedang',
            $gad7 >= 4 => 'Cemas Ringan',
            default => 'Normal',
        };
        
        $kpkResult = $kpk > 10 ? 'Baik' : 'Perlu Perhatian'; 
        
        $dassResult = match (true) {
            $dass21 >= 20 => 'Stres Tinggi',
            $dass21 >= 10 => 'Stres Sedang',
            default => 'Normal',
        };

        return "PHQ9: $phq9Result, GAD7: $gad7Result, KPK: $kpkResult, DASS21: $dassResult.";
    }

    /**
     * Fungsi Inti: Analisis NLP menggunakan Gemini AI.
     */
    private function analyzeWithGemini(array $scores, array $context, string $name)
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return ['analysis' => 'Layanan AI belum dikonfigurasi.', 'status' => 'Waspada'];
        }

        $prompt = "Anda adalah Psikolog Klinis. Analisis murid $name. 
        Skor: PHQ-9(Depresi): {$scores['phq9']}/15, GAD-7(Cemas): {$scores['gad7']}/15, DASS-21: {$scores['dass21']}/30, KPK: {$scores['kpk']}/15.
        Detail Masalah: " . implode(". ", $context) . ".
        Berikan tanggapan empati, saran rujukan (BK/Psikolog), dan 3 aktivitas pemulihan.
        Balas HANYA JSON: {\"analysis\": \"teks\", \"status\": \"Aman/Waspada/Bahaya\"}";

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey", [
                "contents" => [["parts" => [["text" => $prompt]]]]
            ]);

            $rawText = $response->json()['candidates'][0]['content']['parts'][0]['text'];
            $cleanJson = json_decode(trim(str_replace(['```json', '```'], '', $rawText)), true);
            
            return [
                'analysis' => $cleanJson['analysis'] ?? 'Analisis AI tidak tersedia.',
                'status' => $cleanJson['status'] ?? 'Waspada'
            ];
        } catch (\Exception $e) {
            return ['analysis' => 'AI sibuk. Konsultasikan skor Anda ke Guru BK.', 'status' => 'Waspada'];
        }
    }

    /**
     * Menampilkan halaman sukses (Memperbaiki error nilai 0).
     */
    public function showSuccess(Request $request)
    {
        $studentId = $request->get('studentId'); 
        $studentData = StudentData::find($studentId);

        if (!$studentData) {
            return redirect('/')->with('error', 'Data tidak ditemukan.');
        }

        // Ambil skor dari database (Model Casts harus array)
        $dbScores = $studentData->scores;

        // Ambil nilai individual (fallback ke 0 jika null)
        $p9 = (int)($dbScores['phq9'] ?? 0);
        $g7 = (int)($dbScores['gad7'] ?? 0);
        $kp = (int)($dbScores['kpk'] ?? 0);
        $d2 = (int)($dbScores['dass21'] ?? 0);

        // Hitung Persentase (Max: PHQ9=15, GAD7=15, KPK=15, DASS21=30)
        $phq9Percent = ($p9 / 15) * 100;
        $gad7Percent = ($g7 / 15) * 100;
        $kpkPercent = ($kp / 15) * 100;
        $dass21Percent = ($d2 / 30) * 100;

        return view('public_success', [
            'totalScore' => array_sum($dbScores),
            'resultSummary' => $studentData->result_summary,
            'phq9Score' => $p9,
            'gad7Score' => $g7,
            'kpkScore' => $kp,
            'dass21Score' => $d2,
            'phq9Percent' => $phq9Percent,
            'gad7Percent' => $gad7Percent,
            'kpkPercent' => $kpkPercent,
            'dass21Percent' => $dass21Percent,
            'studentId' => $studentId,
            'studentName' => $studentData->name,
            'studentClass' => $studentData->class_level,
            'aiAnalysis' => $studentData->ai_analysis,
            'mentalStatus' => $studentData->mental_status
        ]);
    }
}