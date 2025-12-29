<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeSetting extends Model
{
    protected $fillable = ['title', 'description', 'btn_test_label', 'btn_admin_label'];
}