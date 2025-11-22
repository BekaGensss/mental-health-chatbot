<x-app-layout>
    <x-slot name="header">
        {{-- Header modern dengan aksen Cyan Elektrik (Disamakan) --}}
        <h2 class="font-extrabold text-2xl sm:text-4xl text-gray-800 leading-snug border-l-4 border-cyan-500 pl-5 py-2">
            {{-- Menggunakan SVG Chart Bar dari kode sebelumnya --}}
            <svg class="w-7 h-7 sm:w-8 sm:h-8 inline-block mr-2 text-cyan-500 transform -translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5h6"></path>
            </svg>
            {{ __('Detail Data Pengisian Murid') }}
        </h2>
    </x-slot>

    {{-- Logika PHP untuk Menghitung Total Skor per Tipe Evaluasi --}}
    @php
        $totalScores = [];
        $grandTotal = 0;
        
        // 1. Hitung total skor per tipe
        foreach ($detailedAnswers as $answer) {
            $type = strtoupper($answer['type']);
            $score = $answer['score'];

            if (!isset($totalScores[$type])) {
                $totalScores[$type] = 0;
            }
            $totalScores[$type] += $score;
        }

        // 2. Tambahkan skor PHQ9, GAD7, DASS21, KPK jika tidak ada di data, 
        //    menggunakan nilai default 0 (jika tidak terisi di $detailedAnswers)
        //    Ini memastikan semua tipe yang diminta muncul di daftar Total Skor.
        $typesNeeded = ['PHQ9', 'GAD7', 'DASS21', 'KPK'];
        foreach ($typesNeeded as $type) {
            if (!isset($totalScores[$type])) {
                $totalScores[$type] = 0;
            }
        }
        
        // 3. Hitung Grand Total
        $grandTotal = array_sum($totalScores);

        // Mengurutkan kunci array (tipe) secara alfabetis agar rapi
        ksort($totalScores);
    @endphp

    {{-- Latar belakang netral yang lebih cerah --}}
    <div class="py-10 bg-gray-50/70">
        {{-- max-w-4xl tetap, space-y-10 diperkuat --}}
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            {{-- Bagian Judul Utama (Nama dan Kelas) --}}
            <div class="sm:px-0 px-4"> 
                <h3 class="font-extrabold text-3xl sm:text-5xl text-gray-800 tracking-tight border-b-2 border-cyan-100 pb-3 inline-flex flex-col sm:flex-row sm:items-baseline">
                    {{ $studentData->name }} 
                    <span class="text-3xl sm:text-5xl font-extrabold text-cyan-600 sm:ml-4 whitespace-nowrap">({{ $studentData->class_level }})</span>
                </h3>
            </div>

            {{-- CARD 1: INFORMASI UTAMA, TOTAL SKOR & RINGKASAN HASIL --}}
            <div class="bg-white p-8 shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                
                {{-- Info Waktu dan Tombol Kembali --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 pb-4 mb-8 space-y-4 sm:space-y-0">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Diisi pada:</p>
                        <p class="font-bold text-lg text-gray-700 font-mono">{{ $studentData->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    {{-- Tombol Kembali (Aksen Cyan) --}}
                    <a href="{{ route('admin.data_pengisian_murid') }}" class="inline-flex items-center text-cyan-700 hover:text-cyan-800 font-semibold text-sm transition duration-200 py-2 px-4 rounded-xl bg-cyan-100 hover:bg-cyan-200 shadow-md">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                        Kembali ke Daftar
                    </a>
                </div>

                {{-- INFORMASI TOTAL SKOR --}}
                <div class="mb-10">
                    <p class="font-extrabold text-xl text-gray-700 flex items-center mb-4 border-b border-gray-100 pb-2">
                        <svg class="w-6 h-6 mr-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2m-2 4h2m-2 4h2m-2 2h6m-5.46-9.01l7.15-7.15A1 1 0 0115.5 3H19a1 1 0 011 1v3.5a1 1 0 01-.29.71l-7.15 7.15m-1.74-5.22l-1.42 1.42-3.1-3.1"></path></svg>
                        Total Skor Evaluasi
                    </p>
                    {{-- Grid Responsif untuk Total Skor --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach($totalScores as $type => $score)
                            <div class="p-4 bg-cyan-50 rounded-xl border border-cyan-300 shadow-md flex flex-col items-center justify-center text-center">
                                <span class="font-extrabold text-lg text-gray-700">{{ $type }}</span>
                                <span class="font-extrabold text-3xl text-cyan-800 mt-1">{{ $score }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- GRAND TOTAL (Baris terpisah dan lebih menonjol) --}}
                    <div class="mt-6 p-4 bg-cyan-600 rounded-xl shadow-lg flex justify-between items-center">
                        <span class="font-extrabold text-xl text-white">GRAND TOTAL SKOR</span>
                        <span class="font-extrabold text-3xl text-white">{{ $grandTotal }}</span>
                    </div>
                </div>
                
                {{-- RINGKASAN HASIL --}}
                <div class="mb-10">
                    <p class="font-extrabold text-xl text-cyan-700 flex items-center mb-4">
                        <svg class="w-6 h-6 mr-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.149-2.052-.418-3.016z"></path></svg>
                        Kesimpulan Evaluasi
                    </p>
                    {{-- Kontainer Hasil Utama --}}
                    <div class="p-6 bg-cyan-100 rounded-xl border-2 border-cyan-400 shadow-lg">
                        <p class="text-2xl font-extrabold text-cyan-800 leading-snug">
                            {{ $studentData->result_summary }}
                        </p>
                    </div>
                </div>
                
            </div>

            
            {{-- CARD 2: DETAIL JAWABAN PER PERTANYAAN --}}
            <div class="bg-white p-8 shadow-2xl ring-2 ring-gray-100 sm:rounded-3xl">
                <h3 class="font-bold text-2xl mb-8 text-gray-800 border-b border-gray-200 pb-3 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    Detail Jawaban
                </h3>
                
                <div class="space-y-6">
                    @foreach($detailedAnswers as $index => $answer)
                        @php
                            $score = $answer['score'];
                            $type = strtoupper($answer['type']);
                            
                            // Logika Warna untuk skor
                            $cardBase = 'bg-white border-gray-200 border-l-4';
                            $scoreText = 'text-cyan-700 bg-cyan-100';
                            $scoreColor = 'text-cyan-800'; 
                            
                            // Skema Warna Peringatan
                            if ($score >= 3) {
                                $cardBase = "bg-red-50 border-red-500 border-l-4 shadow-xl";
                                $scoreText = "text-white bg-red-700"; // Background lebih gelap
                                $scoreColor = 'text-white'; // Teks skor menjadi putih agar kontras
                            } elseif ($score == 2) {
                                $cardBase = "bg-orange-50 border-orange-500 border-l-4 shadow-lg"; 
                                $scoreText = "text-orange-900 bg-orange-300";
                                $scoreColor = 'text-orange-700';
                            } elseif ($score == 1) {
                                $cardBase = "bg-lime-50 border-lime-500 border-l-4 shadow-md"; 
                                $scoreText = "text-lime-800 bg-lime-200";
                                $scoreColor = 'text-lime-700';
                            }
                            
                            // Type tag color 
                            $typeBg = ($type == 'PHQ9') ? 'bg-cyan-100 text-cyan-700 font-extrabold' : 'bg-green-100 text-green-700 font-extrabold';
                        @endphp

                        {{-- Kartu Pertanyaan --}}
                        <div class="p-6 rounded-xl border {{ $cardBase }} transition duration-200">
                            {{-- Perbaikan Responsif: Menggunakan Flex Wrap di mobile --}}
                            <div class="flex flex-col sm:flex-row sm:justify-between items-start mb-4 space-y-3 sm:space-y-0">
                                
                                {{-- Pertanyaan --}}
                                <p class="font-extrabold text-lg sm:text-xl text-gray-800 leading-snug w-full sm:w-auto sm:mr-6">
                                    {{ $index + 1 }}. {{ $answer['question'] }}
                                </p>
                                
                                {{-- Tag Score dan Type --}}
                                <div class="flex space-x-2 text-sm flex-shrink-0">
                                    {{-- Tag Tipe --}}
                                    <span class="px-3 py-1 rounded-full font-semibold {{ $typeBg }}">
                                        {{ $type }}
                                    </span>
                                    {{-- Tag Skor --}}
                                    <span class="px-3 py-1 rounded-full font-extrabold {{ $scoreText }}">
                                        Skor: <span class="{{ $scoreColor }}">{{ $score }}</span>
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Area Jawaban --}}
                            <div class="p-4 bg-white border border-gray-100 rounded-lg mt-4 shadow-inner">
                                <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Jawaban Murid</p>
                                <p class="text-gray-800 font-semibold text-base">
                                    {{ $answer['answer_text'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Kembali di bagian bawah --}}
            <div class="text-center pt-4 pb-4">
                 {{-- Tombol Utama Cyan --}}
                 <a href="{{ route('admin.data_pengisian_murid') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-full text-white bg-cyan-600 hover:bg-cyan-700 transition duration-200 shadow-xl shadow-cyan-500/50 hover:shadow-2xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                    Kembali ke Daftar Data
                </a>
            </div>

        </div>
    </div>
</x-app-layout>