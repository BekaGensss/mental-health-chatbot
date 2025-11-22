<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Asumsi Model Option ada di namespace App\Models
use App\Models\Option; 

class Question extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * @note Kolom 'type' dapat menampung kategori baru seperti 'kpk' atau 'dass21'.
     */
    protected $fillable = [
        'type',
        'content',
        'order',
    ];

    /**
     * Get the options for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }
}