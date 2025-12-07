<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusteringResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_data_id',
        'phq9_score',
        'gad7_score',
        'dass21_score',
        'kpk_score',
        'cluster_id',
        'cluster_label',
        'distance_to_centroid',
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