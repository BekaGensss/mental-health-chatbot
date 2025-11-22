<?php

namespace App\Exports;

use App\Models\StudentData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon; // Import Carbon untuk memastikan fungsi zona waktu

/**
 * Kelas ekspor untuk data siswa, disederhanakan agar hanya mencakup
 * informasi dasar siswa dan Ringkasan Hasil (sesuai permintaan user).
 */
class StudentsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $classFilter;

    /**
     * @param string $classFilter Filter level kelas (atau 'all' untuk semua).
     */
    public function __construct(string $classFilter = 'all')
    {
        $this->classFilter = $classFilter;
    }

    /**
     * Metode query() digunakan untuk mengambil data StudentData yang sudah difilter.
     */
    public function query()
    {
        // Hanya ambil kolom yang relevan
        $query = StudentData::query()->select('id', 'name', 'class_level', 'result_summary', 'created_at');

        if ($this->classFilter && $this->classFilter !== 'all') {
            // Filter kelas: Menggunakan LOWER() dan whereRaw untuk case-insensitive.
            $filterLower = strtolower($this->classFilter);
            $query->whereRaw('LOWER(class_level) = ?', [$filterLower]);
        }

        return $query;
    }

    /**
     * Mendefinisikan baris judul (headings) di Excel sesuai format yang diminta.
     */
    public function headings(): array
    {
        // Kolom disederhanakan (tanpa kolom skor dinamis)
        return [
            'ID',
            'Nama Murid',
            'Kelas',
            'Ringkasan Hasil', // Kolom ini mencakup ringkasan skor keseluruhan
            'Tanggal Pengisian',
        ];
    }

    /**
     * Memetakan data StudentData ke format baris Excel.
     * @param StudentData $studentData
     * @return array
     */
    public function map($studentData): array
    {
        // Dapatkan objek Carbon dari created_at
        $createdAt = $studentData->created_at;
        $formattedDate = null;
        
        // Dapatkan zona waktu dari konfigurasi Laravel (yang diambil dari .env)
        $appTimezone = config('app.timezone');

        if ($createdAt) {
            // Pindahkan waktu ke zona waktu aplikasi yang telah dikonfigurasi
            // sebelum memformat. Ini akan memastikan waktu "realtime" lokal sesuai .env.
            // Gunakan Carbon::parse() untuk memastikan ini adalah objek Carbon.
            $formattedDate = Carbon::parse($createdAt)
                ->setTimezone($appTimezone) 
                ->format('Y-m-d H:i:s');
        }
        
        // Data dipetakan langsung ke kolom yang disederhanakan
        return [
            $studentData->id,
            $studentData->name,
            $studentData->class_level,
            // Pastikan result_summary adalah string (decode jika masih JSON)
            is_array($studentData->result_summary) ? json_encode($studentData->result_summary) : $studentData->result_summary,
            // Gunakan tanggal yang telah diformat dengan zona waktu lokal
            $formattedDate,
        ];
    }
}