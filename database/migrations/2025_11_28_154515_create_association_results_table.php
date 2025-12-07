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
        Schema::create('association_results', function (Blueprint $table) {
            $table->id();
            
            // Aturan Asosiasi: IF (Antecedent) -> THEN (Consequent)
            $table->string('antecedent')->comment('Jawaban/Itemset A (Misal: PHQ9_Q1_Ya)');
            $table->string('consequent')->comment('Jawaban/Itemset B (Misal: GAD7_Q2_Ya)');
            
            // Metrik Algoritma Apriori
            $table->decimal('support_value', 8, 4)->comment('Dukungan (Seberapa sering kombinasi muncul)');
            $table->decimal('confidence_value', 8, 4)->comment('Keyakinan (Probabilitas B jika A terjadi)');
            $table->decimal('lift_value', 8, 4)->comment('Lift (Kekuatan hubungan, >1 berarti positif)');
            
            // Algoritma yang digunakan
            $table->string('algorithm_used')->default('Simulasi Apriori');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_results');
    }
};