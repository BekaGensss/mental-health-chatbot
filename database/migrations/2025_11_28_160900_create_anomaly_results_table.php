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
        Schema::create('anomaly_results', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke data murid
            $table->foreignId('student_data_id')->unique()->constrained('student_data')->onDelete('cascade');
            
            // Skor Anomali (Misal: Jarak dari data normal, atau skor kecepatan pengisian)
            $table->decimal('anomaly_score', 8, 4)->comment('Skor yang menunjukkan seberapa anomali data tersebut');
            
            // Hasil Anomali
            $table->string('is_anomaly')->comment('YES atau NO');
            $table->string('anomaly_reason')->comment('Deskripsi alasan (Misal: Jawaban terlalu cepat, Pola tidak konsisten)');
            
            // Algoritma yang digunakan
            $table->string('algorithm_used')->default('Simulasi Deteksi Kecepatan & Outlier');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anomaly_results');
    }
};