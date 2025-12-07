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
        Schema::create('regression_results', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke data murid
            $table->foreignId('student_data_id')->unique()->constrained('student_data')->onDelete('cascade');
            
            // Input Skor dari 4 Skala Kuesioner (Independent Variables)
            $table->integer('phq9_score')->nullable();
            $table->integer('gad7_score')->nullable();
            $table->integer('dass21_score')->nullable();
            $table->integer('kpk_score')->nullable();
            
            // Hasil Prediksi (Dependent Variable)
            $table->decimal('predicted_value', 8, 2)->comment('Nilai Akademik yang diprediksi (misal: 0.00 - 100.00)');
            
            // Metrik Evaluasi Regresi
            $table->decimal('mean_squared_error', 10, 4)->nullable();
            $table->decimal('r_squared', 5, 4)->nullable();

            // Algoritma yang digunakan
            $table->string('algorithm_used')->default('Simulasi Regresi Linier');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regression_results');
    }
};