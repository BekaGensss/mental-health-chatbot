<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\Loggable; // IMPORT TRAIT BARU

class AcademicClassController extends Controller
{
    use Loggable; // GUNAKAN TRAIT LOGGABLE

    /**
     * Menampilkan daftar semua kelas (READ/Index).
     */
    public function index()
    {
        $classes = AcademicClass::orderBy('name')->get();
        return view('admin.kelas.index', compact('classes'));
    }

    /**
     * Menampilkan formulir tambah kelas (CREATE).
     */
    public function create()
    {
        return view('admin.kelas.create');
    }

    /**
     * Menyimpan kelas baru ke database (STORE).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:10|unique:academic_classes,name',
        ]);

        $class = AcademicClass::create([
            'name' => strtoupper($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        // LOGGING AKTIVITAS (CREATE)
        $this->recordActivity('CREATE', "Menambah Kelas baru: {$class->name}", $class);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Menampilkan formulir edit kelas (EDIT).
     */
    public function edit(AcademicClass $class)
    {
        return view('admin.kelas.edit', compact('class'));
    }

    /**
     * Memperbarui kelas di database (UPDATE).
     */
    public function update(Request $request, AcademicClass $class)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:10', Rule::unique('academic_classes', 'name')->ignore($class->id)],
        ]);

        $class->update([
            'name' => strtoupper($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        // LOGGING AKTIVITAS (UPDATE)
        $this->recordActivity('UPDATE', "Mengubah Kelas: {$class->name}", $class);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    /**
     * Menghapus kelas (DESTROY).
     */
    public function destroy(AcademicClass $class)
    {
        // LOGGING AKTIVITAS (DELETE) SEBELUM DELETE
        $this->recordActivity('DELETE', "Menghapus Kelas: {$class->name}", $class);
        
        $class->delete();
        
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}