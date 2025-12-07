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
        Schema::create('classification_results', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke data murid yang dianalisis
            $table->foreignId('student_data_id')->unique()->constrained('student_data')->onDelete('cascade');
            
            // Input Skor dari 4 Skala Kuesioner
            $table->integer('phq9_score')->nullable();
            $table->integer('gad7_score')->nullable();
            $table->integer('dass21_score')->nullable(); // <-- BARU
            $table->integer('kpk_score')->nullable();    // <-- BARU
            
            // Hasil Prediksi
            $table->string('predicted_label')->comment('Hasil klasifikasi (Misal: Risiko Tinggi, Risiko Sedang, dst.)');
            $table->decimal('risk_probability', 5, 4)->nullable()->comment('Nilai probabilitas risiko, 0.0000 - 1.0000');
            
            // Algoritma yang digunakan
            $table->string('algorithm_used')->default('Simulasi Multivariat');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classification_results');
    }
};