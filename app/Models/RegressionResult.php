<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegressionResult extends Model
{ // <-- INI YANG HILANG!
    use HasFactory;

    protected $fillable = [
        'student_data_id',
        'phq9_score',
        'gad7_score',
        'dass21_score',
        'kpk_score',
        'predicted_value',
        'mean_squared_error',
        'r_squared',
        'algorithm_used',
    ];

    /**
     * Relasi ke data murid.
     */
    public function studentData()
    {
        // Pastikan Model StudentData diimpor atau berada di namespace yang sama
        return $this->belongsTo(StudentData::class);
    }
}