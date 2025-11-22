<x-app-layout>
    {{-- Memuat Chart.js (dibiarkan) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script> 

    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Formulir --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5h6m-9 6h3m-3 4h6"></path>
            </svg>
            {{ __('Kelola Formulir Pertanyaan') }}
        </h2>
    </x-slot>

    {{-- Menggunakan padding dan background yang sama dengan dashboard --}}
    <div class="py-6 sm:py-10 bg-gray-50/70"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Success (Warna Cyan) --}}
            @if(session('success'))
                <div class="bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700 p-5 rounded-xl mb-8 shadow-lg transition duration-300" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-lg">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Card Kontainer Utama (Shadow elegan, rounded 3xl) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                <div class="p-6 sm:p-10 text-gray-900">
                    
                    {{-- HEADER KONTEN UTAMA & TOMBOL (Menggabungkan H3 dan Tombol) --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center pb-5 border-b-2 border-cyan-100 mb-8 space-y-4 sm:space-y-0">
                        
                        {{-- Counter Total Item (Pindah ke sini) --}}
                        <h3 class="font-extrabold text-2xl text-gray-800">
                            {{-- Mengganti "Daftar Item" dengan Total Item di sini --}}
                            <span class="px-3 py-1 text-base font-extrabold bg-cyan-100 text-cyan-700 rounded-lg shadow-inner whitespace-nowrap">
                                Total Item: {{ $questions->count() }}
                            </span>
                        </h3>
                        
                        {{-- Tombol Tambah Item Baru --}}
                        <a href="{{ route('admin.questions.create') }}" class="inline-flex items-center px-6 py-3 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 active:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-500/50 transition duration-300 transform hover:scale-[1.02]">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Item Baru
                        </a>
                    </div>
                    
                    @if($questions->isEmpty())
                        {{-- Konten Kosong (Warna Cyan) --}}
                        <div class="p-12 text-center text-gray-500 border-4 border-dashed border-cyan-300 rounded-2xl bg-cyan-50">
                            <i class="fas fa-exclamation-circle text-4xl mb-3 text-cyan-600"></i>
                            <p class="font-extrabold text-2xl text-gray-700">Belum Ada Item Pertanyaan</p>
                            <p class="text-md mt-2">Silakan tambahkan pertanyaan pertama Anda untuk memulai evaluasi.</p>
                        </div>
                    @else
                        {{-- Daftar Pertanyaan (menggunakan card styling yang ditingkatkan) --}}
                        <div class="space-y-6">
                            @foreach($questions as $question)
                                {{-- Card Pertanyaan --}}
                                <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 transition duration-300 hover:shadow-cyan-200/50 hover:ring-2 hover:ring-cyan-50">
                                    {{-- Flexbox di mobile diubah menjadi stack (flex-col) untuk kerapihan --}}
                                    <div class="flex flex-col lg:flex-row justify-between items-start">
                                        
                                        {{-- KONTEN PERTANYAAN --}}
                                        <div class="flex-grow pr-0 lg:pr-8 w-full">
                                            
                                            {{-- Baris Status/Urutan --}}
                                            <div class="flex flex-wrap items-center mb-3 space-x-3 text-xs text-gray-500 font-semibold">
                                                <span class="px-3 py-1 bg-gray-100 rounded-full border border-gray-300">
                                                    <i class="fas fa-list-ol mr-1"></i> Urutan: {{ $question->order }}
                                                </span>
                                                <span class="px-3 py-1 bg-cyan-100 text-cyan-600 rounded-full border border-cyan-200 uppercase">
                                                    <i class="fas fa-tag mr-1"></i> Tipe: {{ strtoupper($question->type) }}
                                                </span>
                                            </div>

                                            {{-- Teks Pertanyaan --}}
                                            <p class="font-extrabold text-xl text-gray-800 mt-2 leading-snug">
                                                {{ $question->order }}. {{ $question->content }}
                                            </p>
                                            
                                            {{-- Opsi Jawaban (Grid Responsif) --}}
                                            <div class="mt-4 p-4 bg-gray-50 border border-gray-100 rounded-lg shadow-inner">
                                                <p class="font-bold text-sm mb-3 text-cyan-700 flex items-center">
                                                    <i class="fas fa-check-square mr-2"></i> Opsi Jawaban (Skor):
                                                </p>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @foreach($question->options->sortBy('score') as $option)
                                                        {{-- Card Opsi Jawaban --}}
                                                        <div class="flex items-center text-sm bg-white p-3 rounded-xl shadow-sm border border-cyan-200">
                                                            <span class="font-extrabold font-mono text-white bg-cyan-600 px-3 py-1 rounded-full mr-3 min-w-[40px] text-center">
                                                                {{ $option->score }}
                                                            </span> 
                                                            <span class="text-gray-700 font-medium">{{ $option->text }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- KOLOM AKSI --}}
                                        {{-- Di mobile, kolom aksi di bawah dan mengambil lebar penuh --}}
                                        <div class="flex flex-row space-x-3 lg:flex-col lg:space-x-0 lg:space-y-2 mt-4 lg:mt-0 w-full lg:w-32 flex-shrink-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-gray-100">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.questions.edit', $question->id) }}" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition duration-150 shadow-md w-1/2 lg:w-full">
                                                <i class="fas fa-edit mr-2 lg:mr-1"></i> Edit
                                            </a>
                                            
                                            {{-- Tombol Hapus --}}
                                            <form method="POST" action="{{ route('admin.questions.destroy', $question->id) }}" onsubmit="return confirm('⚠️ Anda yakin ingin menghapus pertanyaan ini? Tindakan ini tidak dapat dibatalkan.')" class="w-1/2 lg:w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition duration-150 shadow-md">
                                                    <i class="fas fa-trash-alt mr-2 lg:mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>