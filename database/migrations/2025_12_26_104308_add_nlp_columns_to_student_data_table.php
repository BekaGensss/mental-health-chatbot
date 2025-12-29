<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_data', function (Blueprint $table) {
            // Kolom JSON untuk breakdown skor (PHQ9, GAD7, DASS21, KPK)
            $table->json('scores')->nullable()->after('answers'); 
            // Kolom teks panjang untuk interpretasi NLP dari Gemini
            $table->text('ai_analysis')->nullable()->after('result_summary');
            // Kolom status singkat untuk filter admin
            $table->string('mental_status')->nullable()->after('ai_analysis');
        });
    }

    public function down(): void
    {
        Schema::table('student_data', function (Blueprint $table) {
            $table->dropColumn(['scores', 'ai_analysis', 'mental_status']);
        });
    }
};