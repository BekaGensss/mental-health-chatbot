<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin Khusus
        User::create([
            'name' => 'Admin BK',
            'email' => 'superadmin@alwahab.com',
            'password' => Hash::make('superadmin'), 
        ]);

        $this->command->info('Admin user created successfully!');
        
        // 2. Memanggil Seeder Lainnya
        $this->call([
            // AcademicClassSeeder::class, // BARIS INI DIHAPUS UNTUK MENGHINDARI ERROR
            NewQuestionSeeder::class,   // Memasukkan 25 Pertanyaan Kuesioner
        ]);
        
        $this->command->info('Database seeding completed. Run php artisan serve to start the app.');
    }
}