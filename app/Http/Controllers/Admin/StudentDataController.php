<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentData;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Traits\Loggable; // WAJIB DIIMPOR

class StudentDataController extends Controller
{
    use Loggable; // TRAIT LOGGING AKTIF

    /**
     * Menampilkan daftar semua data pengisian formulir murid.
     */
    public function index()
    {
        // Ambil semua data murid, diurutkan berdasarkan tanggal terbaru
        $dataMurid = StudentData::orderBy('created_at', 'desc')->paginate(10); 

        return view('admin.data_pengisian_murid', compact('dataMurid'));
    }

    /**
     * Menampilkan detail pengisian formulir dari murid tertentu.
     */
    public function show(StudentData $studentData)
    {
        // Ambil semua pertanyaan (termasuk opsi) untuk referensi
        $questions = Question::with('options')->orderBy('order')->get()->keyBy('id');
        $muridAnswers = $studentData->answers; 

        $detailedAnswers = [];
        foreach ($muridAnswers as $questionId => $optionId) {
            $question = $questions->get($questionId);
            if ($question) {
                $option = $question->options->where('id', $optionId)->first();
                
                $detailedAnswers[] = [
                    'question' => $question->content,
                    'answer_text' => $option ? $option->text : 'Tidak Ditemukan',
                    'score' => $option ? $option->score : 0,
                    'type' => $question->type,
                ];
            }
        }

        return view('admin.student_data_detail', compact('studentData', 'detailedAnswers'));
    }

    /**
     * Menghapus data pengisian murid (DESTROY).
     */
    public function destroy(StudentData $studentData)
    {
        $muridName = $studentData->name;
        
        // LOGGING AKTIVITAS SEBELUM DELETE
        $this->recordActivity('DELETE', "Menghapus data pengisian oleh: {$muridName} (Kelas: {$studentData->class_level})", $studentData);
        
        $studentData->delete();

        return redirect()->route('admin.data_pengisian_murid')->with('success', "Data pengisian murid atas nama {$muridName} berhasil dihapus.");
    }
}