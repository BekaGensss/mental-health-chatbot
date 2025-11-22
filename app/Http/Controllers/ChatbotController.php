<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function interact(Request $request)
    {
        // Mendapatkan API Key
        $apiKey = env('GEMINI_API_KEY', 'AIzaSyBR3gTATHpNLNZnR0hOt3K5Xb00_aQsLWM');

        $message = $request->input('message');
        $lowerMessage = strtolower($message); 
        
        if (empty($message)) {
            return response()->json(['bot_message' => 'Silakan ketikkan sesuatu untuk memulai.'], 200);
        }
        
        // ========================================================
        // LOGIKA PENDEKSI INTENT (PRIORITAS 1: MEMICU FORM STATIS)
        // ========================================================
        if (str_contains($lowerMessage, 'mulai form') || 
            str_contains($lowerMessage, 'isi formulir') ||
            str_contains($lowerMessage, 'siap mengisi')) 
        {
            // Jika kata kunci ditemukan, langsung kirim sinyal untuk REDIRECT.
            return response()->json([
                'status' => 'success',
                'bot_message' => "Hebat! Saya sudah menyiapkan tautan ke formulir skoring resmi. Silakan tekan tombol yang muncul di bawah.",
                'action' => 'START_SCORING_FORM' // Sinyal untuk JS
            ], 200);
        }
        
        // ========================================================
        // LOGIKA GEMINI API (PRIORITAS 2: CHAT BEBAS/NLP)
        // ========================================================
        $aiText = "Maaf, saat ini fitur AI mengalami kendala. Silakan ketik MULAI FORM untuk melanjutkan."; // Default failover

        try {
            // Panggil API Gemini
            $response = Http::timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [ 'role' => 'user', 'parts' => [['text' => "Anda adalah asisten konseling BK. Tanggapi dan berinteraksi secara alami dalam BAHASA INDONESIA. Tanggapi pesan ini: '{$message}'"]]],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? $aiText;
            } else {
                // Jika API gagal (bukan karena kode kita), fallback ke pesan yang mengarahkan user.
                Log::error("Gemini API Error: Status " . $response->status() . " Body: " . $response->body());
                $aiText = "Maaf, fitur AI sedang mengalami kendala jaringan. Silakan ketik MULAI FORM untuk melanjutkan ke formulir.";
            }
            
        } catch (\Exception $e) {
            // Jika terjadi Timeout/Exception (masalah jaringan Anda)
            Log::error("Chatbot Critical Error: " . $e->getMessage());
            $aiText = "Koneksi ke server AI gagal. Mohon coba lagi atau ketik MULAI FORM.";
        }

        // 3. RETORNE JAWABAN GEMINI/DEFAULT
        return response()->json([
            'status' => 'success',
            'bot_message' => $aiText,
        ], 200);
    }
}