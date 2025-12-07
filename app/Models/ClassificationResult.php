<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassificationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_data_id',
        'phq9_score',
        'gad7_score',
        'dass21_score', // DITAMBAHKAN
        'kpk_score',    // DITAMBAHKAN
        'predicted_label',
        'risk_probability',
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