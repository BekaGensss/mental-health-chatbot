<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade'); // Menghubungkan ke tabel questions
            $table->string('text'); // Teks opsi jawaban (misal: "Hampir Setiap Hari")
            $table->integer('score'); // Nilai skor opsi (misal: 0, 1, 2, 3)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};