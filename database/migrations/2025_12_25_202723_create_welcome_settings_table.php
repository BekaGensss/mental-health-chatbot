<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('welcome_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Selamat Datang');
            $table->text('description')->nullable();
            $table->string('btn_test_label')->default('Mulai Tes Form');
            $table->string('btn_admin_label')->default('Login Admin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('welcome_settings');
    }
};