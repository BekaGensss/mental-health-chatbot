<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard | Tambah Pertanyaan Baru') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-8 bg-gray-50">
        {{-- Lebar Kontainer Tetap 3xl untuk Formulir --}}
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-xl rounded-xl border border-gray-100">
                
                {{-- Judul Utama dengan Gaya Konsisten --}}
                <h3 class="font-extrabold text-3xl mb-6 text-indigo-800 border-b-4 border-indigo-200 pb-2 flex items-center">
                    <i class="fas fa-plus-circle mr-3 text-indigo-600"></i> Formulir Tambah Pertanyaan Baru
                </h3>

                <form method="POST" action="{{ route('admin.questions.store') }}">
                    @csrf
                    
                    {{-- 1. Konten Pertanyaan --}}
                    <div class="mb-6">
                        <label for="content" class="block font-semibold text-sm text-gray-700 mb-2">
                            <i class="fas fa-comment-dots mr-2 text-indigo-500"></i> Konten Pertanyaan Utama
                        </label>
                        <textarea name="content" id="content" rows="4" required
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-inner focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base resize-none placeholder-gray-400">{{ old('content') }}</textarea>
                        @error('content') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- 2. Tipe dan Urutan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8 p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                        <div>
                            <label for="type" class="block font-semibold text-sm text-gray-700 mb-2">
                                <i class="fas fa-balance-scale mr-2 text-indigo-500"></i> Tipe Skala Kuesioner
                            </label>
                            {{-- PENAMBAHAN OPSI SKALA BARU DI SINI --}}
                            <select name="type" id="type" required 
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 bg-white text-base transition duration-150">
                                
                                <option value="phq9" @selected(old('type') == 'phq9')>PHQ-9 (Depresi)</option>
                                <option value="gad7" @selected(old('type') == 'gad7')>GAD-7 (Kecemasan)</option>
                                <option value="kpk" @selected(old('type') == 'kpk')>KPK (Perilaku Kesehatan)</option>
                                <option value="dass21" @selected(old('type') == 'dass21')>DASS-21 (Depresi, Cemas, Stres)</option>
                            </select>
                            @error('type') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="order" class="block font-semibold text-sm text-gray-700 mb-2">
                                <i class="fas fa-sort-numeric-up-alt mr-2 text-indigo-500"></i> Urutan Pertanyaan (Harus Unik)
                            </label>
                            {{-- $nextOrder harus disediakan dari controller, atau set default 1 --}}
                            <input type="number" name="order" id="order" value="{{ old('order', $nextOrder ?? 1) }}" required min="1"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2.5 text-base transition duration-150">
                            @error('order') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- 3. Opsi Jawaban (Skor 0-3) --}}
                    <h4 class="text-xl font-bold mt-6 mb-4 text-gray-800 flex items-center border-b border-gray-200 pb-2">
                        <i class="fas fa-list-check mr-2 text-green-600"></i> Konfigurasi Opsi Jawaban
                    </h4>
                    
                    @php
                        // Menggunakan default options untuk kuesioner skala 0-3
                        $options = [
                            ['text' => 'Tidak Sama Sekali', 'score' => 0],
                            ['text' => 'Beberapa Hari', 'score' => 1],
                            ['text' => 'Lebih dari Separuh Hari', 'score' => 2],
                            ['text' => 'Hampir Setiap Hari', 'score' => 3],
                        ];
                    @endphp

                    @foreach($options as $index => $option)
                        {{-- Card untuk setiap Opsi --}}
                        <div class="grid grid-cols-4 gap-4 mb-4 items-center p-4 bg-gray-100 border border-gray-200 rounded-lg">
                            
                            <div class="col-span-3">
                                <label class="block font-medium text-xs text-gray-700 mb-1">
                                    Teks Opsi Jawaban
                                </label>
                                <input type="text" name="options[{{ $index }}][text]" value="{{ old('options.'.$index.'.text', $option['text']) }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2">
                                @error('options.'.$index.'.text') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            
                            <div class="col-span-1 text-center">
                                <label class="block font-medium text-xs text-gray-700 mb-1">Skor Ditetapkan</label>
                                {{-- Skor dibuat Readonly --}}
                                <input type="number" name="options[{{ $index }}][score]" value="{{ old('options.'.$index.'.score', $option['score']) }}" required readonly
                                    class="mt-1 block w-full border-none rounded-lg shadow-inner bg-green-100 p-3 text-lg font-bold text-center text-green-700 cursor-not-allowed">
                                    <input type="hidden" name="options[{{ $index }}][score]" value="{{ $option['score'] }}"> 
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- Tombol Aksi --}}
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                        <a href="{{ route('admin.kelola_pertanyaan') }}" class="inline-flex items-center px-6 py-2 bg-white border border-gray-300 rounded-lg font-bold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300">
                            <i class="fas fa-times-circle mr-2"></i> Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-wider shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 transform hover:scale-[1.01]">
                            <i class="fas fa-paper-plane mr-2"></i> Simpan Pertanyaan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>