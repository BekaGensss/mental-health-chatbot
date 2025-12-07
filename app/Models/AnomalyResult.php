<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnomalyResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_data_id',
        'anomaly_score',
        'is_anomaly',
        'anomaly_reason',
        'algorithm_used',
    ];

    /**
     * Relasi ke data murid.
     */
    public function studentData()
    {
        return $this->belongsTo(StudentData::class);
    }
}