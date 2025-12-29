<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentData;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\Http;

class ScoringController extends Controller
{
    /**
     * Menangani pengiriman form, kalkulasi skor, dan analisis AI.
     */
    public function submit(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string',
            'class_level' => 'required',
            'answers' => 'required|array',
        ]);

        // 2. Hitung Skor Klinis (PHQ-9, GAD-7, DASS-21, KPK)
        $scores = ['phq9' => 0, 'gad7' => 0, 'dass21' => 0, 'kpk' => 0];
        $contextText = [];

        foreach ($request->answers as $qId => $oId) {
            $question = Question::find($qId);
            $option = Option::find($oId);
            if ($question && $option) {
                $scores[$question->type] += (int) $option->score;
                // Ambil konteks jawaban untuk dianalisis AI
                $contextText[] = "{$question->content}: {$option->text}";
            }
        }

        // 3. Analisis dengan NLP Gemini AI
        $aiResult = $this->analyzeWithGemini($scores, $contextText, $request->name);

        // 4. Simpan ke Database
        $student = StudentData::create([
            'name' => $request->name,
            'class_level' => $request->class_level,
            'answers' => $request->answers,
            'scores' => $scores,
            'ai_analysis' => $aiResult['analysis'],
            'mental_status' => $aiResult['status'],
            'result_summary' => "Skor Klinis - PHQ9: {$scores['phq9']}, GAD7: {$scores['gad7']}, DASS21: {$scores['dass21']}, KPK: {$scores['kpk']}",
        ]);

        // 5. Redirect ke Halaman Sukses
        return redirect()->route('form.success', [
            'studentId' => $student->id,
            'totalScore' => array_sum($scores),
            'summary' => $student->result_summary
        ]);
    }

    /**
     * Analisis menggunakan Google Gemini AI (NLP)
     */
    private function analyzeWithGemini($scores, $context, $name)
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $prompt = "Anda adalah Psikolog Klinis profesional. Analisis data murid $name.\n" .
                  "Skor: PHQ-9: {$scores['phq9']}, GAD-7: {$scores['gad7']}, DASS-21: {$scores['dass21']}, KPK: {$scores['kpk']}.\n" .
                  "WAJIB JAWAB HANYA DALAM FORMAT JSON: {\"analysis\": \"...\", \"status\": \"Aman/Waspada/Bahaya\"}";

        try {
            $response = Http::withOptions(['verify' => false])
                ->timeout(30)
                ->post($url, [
                    "contents" => [["parts" => [["text" => $prompt]]]]
                ]);

            if ($response->successful()) {
                $rawText = $response->json()['candidates'][0]['content']['parts'][0]['text'];
                $cleanText = preg_replace('/^```json|```$/m', '', trim($rawText));
                $decoded = json_decode($cleanText, true);
                
                return [
                    'analysis' => $decoded['analysis'] ?? $rawText,
                    'status' => $decoded['status'] ?? 'Waspada'
                ];
            }
            return ['analysis' => 'AI sedang sibuk.', 'status' => 'Waspada'];
        } catch (\Exception $e) {
            return ['analysis' => 'Error: ' . $e->getMessage(), 'status' => 'Waspada'];
        }
    }

    /**
     * PENCARIAN PSIKOLOG GRATIS (Overpass API / OpenStreetMap)
     * Tidak membutuhkan API Key Google.
     */
    public function getPsychologists(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Koordinat tidak ditemukan'], 400);
        }

        try {
            // Query Overpass: Mencari RS, Klinik, dan Dokter dalam radius 10km
            $query = "[out:json];
                (
                  node[\"amenity\"=\"hospital\"](around:10000, $lat, $lng);
                  node[\"amenity\"=\"clinic\"](around:10000, $lat, $lng);
                  node[\"amenity\"=\"doctors\"](around:10000, $lat, $lng);
                  way[\"amenity\"=\"hospital\"](around:10000, $lat, $lng);
                  way[\"amenity\"=\"clinic\"](around:10000, $lat, $lng);
                );
                out center;";

            $response = Http::withOptions(['verify' => false])
                ->asForm()
                ->post("https://overpass-api.de/api/interpreter", [
                    'data' => $query
                ]);

            $data = $response->json();
            $results = [];

            if (isset($data['elements'])) {
                foreach ($data['elements'] as $element) {
                    // Mendapatkan koordinat (node menggunakan lat/lon, way menggunakan center)
                    $itemLat = $element['lat'] ?? ($element['center']['lat'] ?? null);
                    $itemLng = $element['lon'] ?? ($element['center']['lon'] ?? null);

                    if ($itemLat && $itemLng) {
                        $results[] = [
                            'name' => $element['tags']['name'] ?? 'Layanan Kesehatan/Psikologi',
                            'vicinity' => $element['tags']['addr:full'] ?? 
                                         ($element['tags']['addr:street'] ?? 'Alamat tersedia di peta'),
                            'geometry' => [
                                'location' => [
                                    'lat' => $itemLat,
                                    'lng' => $itemLng
                                ]
                            ]
                        ];
                    }
                }
            }

            return response()->json($results);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}