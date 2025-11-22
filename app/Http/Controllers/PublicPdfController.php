<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentData;
use PDF; // Facade Dompdf
use Exception; 

class PublicPdfController extends Controller
{
    public function exportResult(Request $request)
    {
        // 1. Ambil Data Dasar dan StudentId
        $totalScore = $request->get('totalScore');
        $resultSummary = $request->get('summary');
        $phq9Score = $request->get('phq9Score');
        $gad7Score = $request->get('gad7Score');
        
        $kpkScore = $request->get('kpkScore', 0);
        $dass21Score = $request->get('dass21Score', 0);
        $studentId = (int)$request->get('studentId'); 
        // $codeValue = strval($studentId); // Dihapus

        // Inisialisasi Max Scores
        $phqMax = 15; 
        $gad7Max = 15; 
        $kpkMax = 15; 
        // 🛑 PERBAIKAN DASS-21 MAX SCORE: 10 pertanyaan * 3 = 30
        $dass21Max = 30; 
        
        if (!$studentId || $totalScore === null) {
            return redirect()->back()->with('error', 'Data skor tidak lengkap untuk export PDF. Harap ulangi proses pengisian.');
        }

        // 2. Ambil data murid LENGKAP menggunakan ID
        $studentData = StudentData::find($studentId);

        // 🛑 PERBAIKAN FINAL NAMA/KELAS 🛑
        $studentName = 'Data Tidak Ditemukan (ID: ' . $studentId . ')'; 
        $studentClass = 'N/A';

        if ($studentData) {
            $studentName = trim($studentData->name) !== '' ? $studentData->name : 'NAMA TERCANTUM (DB)'; 
            $studentClass = trim($studentData->class_level) !== '' ? $studentData->class_level : 'N/A';
        }

        // ----------------------------------------------------
        // 3. GENERASI BARCODE & QR CODE (Dihapus dari Controller)
        // ----------------------------------------------------
        
        // 4. PERHITUNGAN PERSENTASE
        $phq9Percent = ($phq9Score / $phqMax) * 100;
        $gad7Percent = ($gad7Score / $gad7Max) * 100;
        $kpkPercent = ($kpkScore / $kpkMax) * 100;
        // Sekarang $dass21Max adalah 30, sehingga persentase benar.
        $dass21Percent = ($dass21Score / $dass21Max) * 100; 
        
        // ASUMSI: Logika Level Risiko (Diskalakan ke Max 30)
        if ($dass21Score >= 26) {
            $dass21Level = 'Sangat Berat';
        } elseif ($dass21Score >= 21) {
            $dass21Level = 'Berat';
        } elseif ($dass21Score >= 16) {
            $dass21Level = 'Sedang';
        } elseif ($dass21Score >= 8) { 
            $dass21Level = 'Ringan'; 
        } else {
            $dass21Level = 'Normal';
        }
        
        // Logika Level Lainnya
        $phq9Level = $phq9Score >= 10 ? 'Sedang/Berat' : ($phq9Score >= 5 ? 'Ringan' : 'Minimal Depresi');
        $gad7Level = $gad7Score >= 10 ? 'Sedang/Berat' : ($gad7Score >= 5 ? 'Ringan' : 'Minimal Cemas');
        $kpkLevel = $kpkScore > 7 ? 'Baik' : 'Perlu Perbaikan'; 

        // 5. Data untuk View PDF 
        $data = compact(
            'totalScore', 'resultSummary', 
            'phq9Score', 'gad7Score', 'phq9Percent', 'gad7Percent', 
            'kpkScore', 'dass21Score', 'kpkPercent', 'dass21Percent',
            'phq9Level', 'gad7Level', 'kpkLevel', 'dass21Level',
            'studentName', 'studentClass',
            'studentId' // Kirim ID untuk ditampilkan di Info Dasar
        );

        // 6. Generate PDF
        try {
            $pdf = PDF::loadView('pdf.result_report', $data)
                        ->setOptions([
                            'isRemoteEnabled' => true,
                            'isHtml5ParserEnabled' => true
                        ])
                        ->setPaper('A4', 'portrait'); 
            
            // Penamaan File
            $name = $studentName;
            $safeName = preg_replace('/[^A-Za-z0-9\s\-\_]/', '', $name); 
            $safeFileName = str_replace(' ', '_', $safeName);

            $fileName = 'Laporan_Hasil_' . $safeFileName . '_' . now()->format('Ymd') . '.pdf';

            return $pdf->download($fileName);
        
        } catch (Exception $e) { 
            return redirect()->back()->with('error', 'Gagal membuat file PDF. Error: ' . $e->getMessage()); 
        }
    }
}