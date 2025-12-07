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
        Schema::create('clustering_results', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke data murid
            $table->foreignId('student_data_id')->unique()->constrained('student_data')->onDelete('cascade');
            
            // Input Skor dari 4 Skala Kuesioner
            $table->integer('phq9_score')->nullable();
            $table->integer('gad7_score')->nullable();
            $table->integer('dass21_score')->nullable();
            $table->integer('kpk_score')->nullable();
            
            // Hasil Clustering
            $table->integer('cluster_id')->comment('ID kelompok (cluster) tempat murid berada');
            $table->string('cluster_label')->comment('Label deskriptif kelompok (Misal: Profil Risiko Tinggi)');
            $table->decimal('distance_to_centroid', 8, 4)->nullable()->comment('Jarak ke pusat cluster');
            
            // Algoritma yang digunakan
            $table->string('algorithm_used')->default('Simulasi K-Means');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clustering_results');
    }
};