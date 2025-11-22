<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait Loggable
{
    /**
     * Mencatat aktivitas admin ke dalam tabel activity_logs.
     *
     * @param string $type Jenis aktivitas (CREATE, UPDATE, DELETE)
     * @param string $description Deskripsi aksi
     * @param \Illuminate\Database\Eloquent\Model|null $subject Objek Model yang terlibat
     * @return void
     */
    protected function recordActivity(string $type, string $description, $subject = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => $type,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
        ]);
    }
}