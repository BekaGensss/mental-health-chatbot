<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Menampilkan daftar semua pertanyaan (READ/Index).
     */
    public function index()
    {
        $questions = Question::with('options')->orderBy('order')->get();
        return view('admin.kelola_pertanyaan', compact('questions'));
    }

    /**
     * Menampilkan formulir tambah pertanyaan (CREATE).
     */
    public function create()
    {
        // Mendapatkan urutan berikutnya secara otomatis
        $nextOrder = Question::max('order') + 1;
        
        return view('admin.questions.create', compact('nextOrder'));
    }

    /**
     * Menyimpan pertanyaan baru ke database (STORE).
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'content' => 'required|string|max:500',
            // 🔑 PERBAIKAN VALIDASI: Menambahkan 'kpk' dan 'dass21'
            'type' => ['required', Rule::in(['phq9', 'gad7', 'kpk', 'dass21'])],
            'order' => 'required|integer|unique:questions,order',
            'options' => 'required|array|min:4',
            'options.*.text' => 'required|string|max:255',
            'options.*.score' => 'required|integer|min:0|max:3',
        ]);

        DB::transaction(function () use ($request) {
            // 2. Buat Pertanyaan
            $question = Question::create([
                'content' => $request->content,
                'type' => $request->type,
                'order' => $request->order,
            ]);

            // 3. Buat Opsi Jawaban
            foreach ($request->options as $optionData) {
                $question->options()->create($optionData);
            }
        });

        return redirect()->route('admin.kelola_pertanyaan')->with('success', 'Pertanyaan baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan formulir edit pertanyaan (EDIT).
     */
    public function edit(Question $question)
    {
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Memperbarui pertanyaan di database (UPDATE).
     */
    public function update(Request $request, Question $question)
    {
        // 1. Validasi Input
        $request->validate([
            'content' => 'required|string|max:500',
            // 🔑 PERBAIKAN VALIDASI: Menambahkan 'kpk' dan 'dass21'
            'type' => ['required', Rule::in(['phq9', 'gad7', 'kpk', 'dass21'])],
            'order' => ['required', 'integer', Rule::unique('questions', 'order')->ignore($question->id)],
            'options' => 'required|array|min:4',
            'options.*.id' => 'nullable|exists:options,id',
            'options.*.text' => 'required|string|max:255',
            'options.*.score' => 'required|integer|min:0|max:3',
        ]);

        DB::transaction(function () use ($request, $question) {
            // 2. Update Pertanyaan
            $question->update([
                'content' => $request->content,
                'type' => $request->type,
                'order' => $request->order,
            ]);

            // 3. Update Opsi Jawaban (Cara sederhana: hapus semua, buat ulang)
            $question->options()->delete();
            foreach ($request->options as $optionData) {
                // Hapus key 'id' jika ada, agar Model::create tidak mencoba update
                unset($optionData['id']); 
                $question->options()->create($optionData);
            }
        });

        return redirect()->route('admin.kelola_pertanyaan')->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    /**
     * Menghapus pertanyaan (DESTROY).
     */
    public function destroy(Question $question)
    {
        // Karena kita menggunakan onDelete('cascade') di migrasi, opsi akan otomatis terhapus
        $question->delete();

        return redirect()->route('admin.kelola_pertanyaan')->with('success', 'Pertanyaan berhasil dihapus!');
    }
}