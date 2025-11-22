<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class NewQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. HAPUS DATA LAMA (untuk menghindari duplikasi saat re-seed)
        DB::table('questions')->delete();
        DB::table('options')->delete();
        $this->command->info('Data pertanyaan lama dibersihkan.');
        
        // 2. Opsi Jawaban
        // Opsi Standar (Skala 0-3) - Digunakan oleh PHQ-9, GAD-7, dan DASS-21
        $standardOptions = [
            ['text' => 'Tidak Sama Sekali', 'score' => 0],
            ['text' => 'Beberapa Hari', 'score' => 1],
            ['text' => 'Lebih dari Separuh Hari', 'score' => 2],
            ['text' => 'Hampir Setiap Hari', 'score' => 3],
        ];

        // Opsi KPK (Skala 0-3) - Digunakan oleh KPK
        $kpkOptions = [
            ['text' => 'Tidak Pernah', 'score' => 0],
            ['text' => 'Kadang-kadang', 'score' => 1],
            ['text' => 'Sering', 'score' => 2],
            ['text' => 'Ya (Selalu)', 'score' => 3],
        ];

        // 3. DAFTAR PERTANYAAN BARU LENGKAP (Total 25 Pertanyaan)
        
        // A. PHQ-9 (5 Pertanyaan Inti)
        $phq9 = [
            'Kurang berminat atau kurang senang melakukan sesuatu.',
            'Merasa murung, sedih, atau putus asa.',
            'Sulit tidur atau terlalu banyak tidur.',
            'Merasa lelah atau kurang energi.',
            'Pikiran bahwa Anda lebih baik mati, atau ingin menyakiti diri sendiri dengan cara apa pun.',
        ];

        // B. GAD-7 (5 Pertanyaan Inti)
        $gad7 = [
            'Merasa gugup, cemas, atau tegang.',
            'Tidak mampu menghentikan atau mengendalikan rasa khawatir.',
            'Terlalu mengkhawatirkan berbagai hal.',
            'Sangat gelisah sehingga sulit untuk duduk diam.',
            'Merasa takut seolah sesuatu yang buruk akan terjadi.',
        ];
        
        // C. DASS-21 (DITINGKATKAN MENJADI 10 PERTANYAAN KHUSUS DEPRESI/CEMAS/STRES MURNI)
        // Logika di Controller Anda menghitung skor DASS-21 dari 20 item DASS-21 yang berbeda
        // Agar skor Anda mencapai 25+ dengan mudah, kita gunakan 10 item khusus DASS-21 di sini.
        $dass21 = [
            // Depresi (D)
            ['content' => 'Saya merasa sedih dan tertekan.', 'type' => 'dass21'],
            ['content' => 'Saya tidak dapat merasakan perasaan positif sama sekali.', 'type' => 'dass21'],
            ['content' => 'Saya merasa tidak berharga.', 'type' => 'dass21'],
            ['content' => 'Saya merasa putus asa.', 'type' => 'dass21'],
            // Kecemasan (A)
            ['content' => 'Saya cenderung panik.', 'type' => 'dass21'],
            ['content' => 'Saya merasa sangat sensitif terhadap hal-hal.', 'type' => 'dass21'],
            ['content' => 'Saya takut tanpa alasan yang jelas.', 'type' => 'dass21'],
            ['content' => 'Saya merasa takut seolah sesuatu yang buruk akan terjadi.', 'type' => 'dass21'],
            // Stres (S)
            ['content' => 'Saya merasa bahwa saya menghabiskan banyak energi karena cemas.', 'type' => 'dass21'],
            ['content' => 'Saya mudah marah atau kesal.', 'type' => 'dass21'],
        ];

        // D. KPK (5 Pertanyaan Kunci)
        $kpk = [
            'Saya selalu tidur cukup (minimal 7 jam/hari).',
            'Saya makan sayur dan buah setiap hari.',
            'Saya berolahraga secara teratur (minimal 3 kali/minggu).',
            'Saya mampu mengelola stres dengan baik (misal: hobi/meditasi).',
            'Saya mampu mengendalikan amarah dan emosi saya.',
        ];

        $order = 1;

        // 4. Proses dan Masukkan Data ke Database
        
        // PHQ-9 dan GAD-7 (Menggunakan Opsi Standar)
        foreach (['phq9' => $phq9, 'gad7' => $gad7] as $type => $qList) {
            foreach ($qList as $content) {
                $q = Question::create(['type' => $type, 'content' => $content, 'order' => $order++]);
                foreach ($standardOptions as $option) $q->options()->create($option);
            }
        }
        
        // DASS-21 (DITINGKATKAN KE 10 ITEM)
        foreach ($dass21 as $qData) {
            // Karena ini adalah item DASS murni, kita beri type dass21 agar skornya terpisah dari PHQ/GAD
            $q = Question::create(['type' => $qData['type'], 'content' => $qData['content'], 'order' => $order++]);
            foreach ($standardOptions as $option) $q->options()->create($option);
        }

        // KPK (Menggunakan Opsi KPK)
        foreach ($kpk as $content) {
            $q = Question::create(['type' => 'kpk', 'content' => $content, 'order' => $order++]);
            foreach ($kpkOptions as $option) $q->options()->create($option);
        }

        $this->command->info("Total " . ($order - 1) . " Pertanyaan (5 PHQ-9, 5 GAD-7, 10 DASS-21, 5 KPK) berhasil dimasukkan!");
        $this->command->info("Skor DASS-21 Total Maksimal: 10 item * 3 poin = 30."); // Catatan Skor Max Baru
    }
}