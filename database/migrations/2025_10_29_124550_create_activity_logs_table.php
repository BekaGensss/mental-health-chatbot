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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Admin yang melakukan aksi
            $table->string('activity_type'); // Tipe aksi (misal: CREATE, UPDATE, DELETE)
            $table->string('subject_type')->nullable(); // Model yang diubah (misal: App\Models\AcademicClass)
            $table->unsignedBigInteger('subject_id')->nullable(); // ID record yang diubah
            $table->text('description'); // Deskripsi log (misal: "Menambah Kelas VII-D")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};