<x-app-layout>
    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Tambah Akun Admin Baru --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            {{ __('Tambah Akun Admin Baru') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-6 sm:py-10 bg-gray-50/70">
        {{-- Lebar Kontainer Ditingkatkan menjadi 3xl untuk Grid 2 Kolom --}}
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8"> 
            {{-- Card Kontainer Utama (Shadow elegan, rounded 3xl, Ring Cyan) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                <div class="p-6 sm:p-8 text-gray-900">
                    
                    {{-- Judul Utama dengan Gaya Konsisten --}}
                    <h3 class="font-extrabold text-3xl mb-6 text-gray-800 border-b-2 border-cyan-100 pb-3 flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2 text-cyan-600"></i> Buat Akun Admin Baru
                    </h3>

                    {{-- FORM REGISTRASI ADMIN BARU --}}
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        {{-- PEMBAGIAN FORM MENJADI 2 KOLOM PADA LAYAR MD KE ATAS --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                            {{-- Kolom Kiri: Nama & Email --}}
                            <div class="md:space-y-4">
                                {{-- 1. Nama Lengkap --}}
                                <div class="mb-4">
                                    <label for="name" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-id-badge mr-2 text-cyan-600"></i> Nama Lengkap
                                    </label>
                                    <input id="name" class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                                            type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                                    @error('name') <p class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>

                                {{-- 2. Email --}}
                                <div class="mb-4">
                                    <label for="email" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-at mr-2 text-cyan-600"></i> Email
                                    </label>
                                    <input id="email" class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                                            type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                                    @error('email') <p class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Kolom Kanan: Password & Konfirmasi Password --}}
                            <div class="md:space-y-4">
                                {{-- 3. Password --}}
                                <div class="mb-4">
                                    <label for="password" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-key mr-2 text-cyan-600"></i> Password
                                    </label>
                                    <input id="password" class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                                            type="password" name="password" required autocomplete="new-password" />
                                    @error('password') <p class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>

                                {{-- 4. Konfirmasi Password --}}
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-check-double mr-2 text-cyan-600"></i> Konfirmasi Password
                                    </label>
                                    <input id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                                            type="password" name="password_confirmation" required autocomplete="new-password" />
                                    @error('password_confirmation') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        {{-- AKHIR PEMBAGIAN KOLOM --}}

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end pt-4 border-t border-gray-200 space-x-3 mt-6">
                            {{-- Tombol Batal --}}
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-5 py-2 bg-white border border-gray-300 rounded-xl font-bold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition duration-300 transform hover:scale-[1.02] shadow-md">
                                <i class="fas fa-times-circle mr-2"></i> Batal
                            </a>
                            
                            {{-- Tombol Tambahkan Akun (Cyan) --}}
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/50 transition duration-300 transform hover:scale-[1.02]">
                                <i class="fas fa-user-check mr-2"></i> Tambahkan Akun
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>