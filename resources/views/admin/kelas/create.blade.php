<x-app-layout>
    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Kelas --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.83L12 18l-6.16-3.83z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v4"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9 5 9-5"></path>
            </svg>
            {{ __('Tambah Kelas') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-6 sm:py-10 bg-gray-50/70">
        {{-- Lebar Kontainer Tetap xl --}}
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            {{-- Card Kontainer Utama (Shadow elegan, rounded 3xl, Ring Cyan) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                <div class="p-6 sm:p-8 text-gray-900">
                    
                    {{-- Judul Utama dengan Gaya Konsisten --}}
                    <h3 class="font-extrabold text-3xl mb-6 text-gray-800 border-b-2 border-cyan-100 pb-3 flex items-center">
                        <i class="fas fa-plus-square mr-2 text-cyan-600"></i> Formulir Tambah Kelas Baru
                    </h3>

                    <form method="POST" action="{{ route('admin.kelas.store') }}">
                        @csrf
                        
                        {{-- 1. Nama Kelas --}}
                        <div class="mb-6">
                            <label for="name" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-tag mr-2 text-cyan-600"></i> Nama Kelas (Contoh: VIII-A)
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                                    class="mt-1 block w-full border-gray-300 rounded-xl shadow-md uppercase p-2.5 
                                           focus:border-cyan-500 focus:ring-cyan-500 transition duration-150"
                                    placeholder="Contoh: IX-B">
                            
                            @error('name') 
                                <p class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        {{-- 2. Status Aktif (Cyan Accent) --}}
                        <div class="mb-8 p-4 bg-cyan-50 rounded-xl border border-cyan-300 shadow-md">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" checked
                                        class="rounded border-gray-300 text-cyan-600 shadow-sm focus:ring-cyan-500 w-5 h-5">
                                <label for="is_active" class="ml-3 block text-base font-semibold text-gray-900">
                                    Aktifkan Kelas ini (Muncul di Formulir Murid)
                                </label>
                            </div>
                        </div>

                        {{-- Tombol Aksi (Cyan Accent) --}}
                        <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end space-x-3">
                            
                            {{-- Tombol Batal --}}
                            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center px-5 py-2 bg-white border border-gray-300 rounded-xl font-bold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition duration-300 transform hover:scale-[1.02] shadow-md">
                                <i class="fas fa-times-circle mr-2"></i> Batal
                            </a>
                            
                            {{-- Tombol Simpan --}}
                            <button type="submit" class="inline-flex items-center px-5 py-2 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/50 transition duration-300 transform hover:scale-[1.02]">
                                <i class="fas fa-save mr-2"></i> Simpan Kelas
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>