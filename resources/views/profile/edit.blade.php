<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard | Profil Saya') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-6 sm:py-8 bg-gray-50">
        {{-- Kontainer Utama (max-w-7xl) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- GRID UTAMA: Pembagi Konten (2/3 Kiri, 1/3 Kanan) --}}
            {{-- Menggunakan lg:grid-cols-3 untuk rasio 2:1 --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 space-y-6 lg:space-y-0">
                
                {{-- KOLOM KIRI (Lebar 2/3): INFORMASI UMUM & FOTO --}}
                {{-- Ini adalah blok utama yang paling sering diakses --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- 1. BAGIAN FOTO PROFIL --}}
                    @include('profile.partials.update-profile-photo-form') 

                    {{-- 2. BAGIAN INFORMASI PROFIL (Nama & Email) --}}
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- KOLOM KANAN (Lebar 1/3): KEAMANAN & PENGATURAN SENSITIF --}}
                {{-- Konten dipusatkan di sini untuk memfokuskan aksi keamanan --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- 3. BAGIAN GANTI PASSWORD --}}
                    @include('profile.partials.update-password-form')
                    
                    {{-- 4. BAGIAN HAPUS AKUN --}}
                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>
</x-app-layout>