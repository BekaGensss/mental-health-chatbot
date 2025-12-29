<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentData extends Model
{
    use HasFactory;

    protected $table = 'student_data';

    protected $fillable = [
        'name',
        'class_level',
        'answers',
        'scores',
        'result_summary',
        'ai_analysis',
        'mental_status',
    ];

    protected $casts = [
        'answers' => 'array',
        'scores' => 'array',
    ];
}