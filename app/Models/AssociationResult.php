<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'antecedent',
        'consequent',
        'support_value',
        'confidence_value', // <-- HARUS ADA
        'lift_value',       // <-- HARUS ADA
        'algorithm_used',
    ];

    // Jika Anda ingin menambahkan relasi, misalnya ke User/Admin yang menjalankan mining:
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}