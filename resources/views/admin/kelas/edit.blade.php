<x-app-layout>
    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Kelas --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            {{ __('Edit Kelas') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-6 sm:py-10 bg-gray-50/70">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Success (Warna Cyan) --}}
            @if(session('success'))
                <div class="bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700 p-5 rounded-xl mb-8 shadow-lg transition duration-300" role="alert">
                    <div class="flex items-center">
                        <svg class="w-7 h-7 mr-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-lg">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Card Kontainer Utama (Shadow elegan, rounded 3xl) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                <div class="p-6 sm:p-8 text-gray-900">
                    
                    {{-- Judul Konten --}}
                    <h3 class="font-extrabold text-3xl mb-6 text-gray-800 border-b-2 border-cyan-100 pb-3 flex items-center">
                        Edit Kelas: <span class="ml-2 text-cyan-600">{{ $class->name }}</span>
                    </h3>
                    
                    {{-- Form Edit Kelas --}}
                    {{-- Gunakan method PUT untuk update --}}
                    <form method="POST" action="{{ route('admin.kelas.update', $class->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Input Nama Kelas --}}
                        <div class="mb-5">
                            <label for="name" class="block font-bold text-sm text-gray-700 mb-2">Nama Kelas</label>
                            <input id="name" name="name" type="text" class="w-full border-gray-300 rounded-xl shadow-md focus:border-cyan-500 focus:ring-cyan-500 @error('name') border-red-500 @enderror" value="{{ old('name', $class->name) }}" required autofocus />
                            @error('name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input Status Kelas --}}
                        <div class="mb-8">
                            <label for="is_active" class="block font-bold text-sm text-gray-700 mb-2">Status Kelas</label>
                            <select id="is_active" name="is_active" class="w-full border-gray-300 rounded-xl shadow-md focus:border-cyan-500 focus:ring-cyan-500 @error('is_active') border-red-500 @enderror" required>
                                <option value="1" {{ old('is_active', $class->is_active) == 1 ? 'selected' : '' }}>AKTIF (Muncul di formulir)</option>
                                <option value="0" {{ old('is_active', $class->is_active) == 0 ? 'selected' : '' }}>NON-AKTIF (Disembunyikan)</option>
                            </select>
                            @error('is_active')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-between space-x-3">
                            {{-- Tombol Kembali --}}
                            <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition duration-200 shadow-md transform hover:scale-[1.02]">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>

                            {{-- Tombol Simpan --}}
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-base text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 active:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-500/50 transition duration-300 transform hover:scale-[1.02]">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>