<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_data', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama murid
            $table->string('class_level'); // Kelas murid (misal: "X-A", "XI-B")
            $table->json('answers'); // Jawaban formulir/chatbot, disimpan sebagai JSON
            $table->string('result_summary')->nullable(); // Ringkasan/skor hasil (opsional, bisa dihitung nanti)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_data');
    }
};