<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('phq_gad'); // Tipe pertanyaan (misal: phq_gad)
            $table->text('content'); // Isi pertanyaan
            $table->integer('order'); // Urutan pertanyaan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};