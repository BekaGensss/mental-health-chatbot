<x-app-layout>
    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Hasil Jawaban Murid / Analisis --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            {{ __('Hasil Jawaban Murid') }}
        </h2>
    </x-slot>

    {{-- Import Chart.js dan datalabels plugin --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    {{-- Import Font Awesome untuk Ikon (Tetap dipertahankan karena banyak digunakan) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <div class="py-6 sm:py-10 bg-gray-50/70 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-16">

            {{-- Bagian PHP Logic (INISIALISASI VARIABEL DENGAN FALLBACK AMAN) --}}
            @php
                // Data dari Controller: Ringkasan Skala Utama
                $phq9Summary = $phq9Summary ?? ['Minimal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];
                $gad7Summary = $gad7Summary ?? ['Minimal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];
                $kpkSummary = $kpkSummary ?? ['Baik' => 0, 'Perlu Perbaikan' => 0];
                $dass21Summary = $dass21Summary ?? ['Normal' => 0, 'Ringan' => 0, 'Sedang' => 0, 'Berat' => 0];
                
                // Data dari Controller: Metrik Global
                $totalMurid = $totalMurid ?? 0;
                $totalRiskCount = $totalRiskCount ?? 0;
                $avgPhq9 = $avgPhq9 ?? 0;
                $avgGad7 = $avgGad7 ?? 0;
                
                $classFilter = $classFilter ?? 'all';
                $searchQuery = $searchQuery ?? '';
                $allClasses = $allClasses ?? ['all' => 'Semua Kelas'];
                $submissionsPerClass = $submissionsPerClass ?? [];
                $studentDetails = $studentDetails ?? collect();

                // --- REAL-TIME DATA FALLBACKS (Diharapkan dari Controller) ---
                $weeklySubmissions = $weeklySubmissions ?? [0, 0, 0, 0, 0, 0, 0]; 
                $weeklyLabels = $weeklyLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                
                // DATA REAL-TIME CHART RADAR (menggunakan skala global, max 5)
                $kpkRadarData = $kpkRadarData ?? [0, 0, 0, 0, 0]; 
                $kpkRadarLabels = $kpkRadarLabels ?? ['Rata-rata PHQ-9', 'Rata-rata GAD-7', 'Rata-rata DASS-21', 'Rata-rata KPK Global', 'Persentase Risiko Tinggi'];
            @endphp

            {{-- Filter, Export, Link Formulir, dan SEARCHING (Menggunakan Aksen Cyan) --}}
            <div class="bg-white p-6 shadow-2xl rounded-2xl border border-gray-100 mb-10 flex flex-col xl:flex-row justify-between items-start xl:items-center transition duration-300 hover:shadow-cyan-100/70 space-y-4 xl:space-y-0 xl:space-x-8">

                {{-- Group Filter & Searching --}}
                <form id="filterForm" method="GET" action="{{ route('admin.data_murid') }}" class="flex flex-col lg:flex-row items-center space-y-4 lg:space-y-0 lg:space-x-4 w-full xl:w-3/5">
                    
                    {{-- Filter Kelas --}}
                    <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4 w-full lg:w-auto">
                        <label for="class_filter" class="font-bold text-gray-700 whitespace-nowrap flex items-center text-md">
                            <i class="fas fa-sliders-h mr-2 text-cyan-600"></i> Filter Kelas:
                        </label>
                        
                        <div class="relative w-full sm:w-64"> 
                            <select name="class_filter" id="class_filter" 
                                class="border-gray-300 rounded-xl shadow-md focus:border-cyan-600 focus:ring-cyan-600 transition duration-200 p-2.5 text-sm font-semibold 
                                    w-full pr-10 appearance-none bg-white hover:border-cyan-400 cursor-pointer text-gray-800">
                                @foreach($allClasses as $value => $label)
                                    <option value="{{ $value }}" {{ $classFilter == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Input Pencarian (Search Query) --}}
                    <div class="relative w-full lg:w-64">
                        <input type="text" name="search_query" id="searchInput" placeholder="Cari data murid (Nama atau Kelas...)" value="{{ $searchQuery }}"
                            class="border-gray-300 rounded-xl shadow-md focus:border-cyan-600 focus:ring-cyan-600 transition duration-200 p-2.5 text-sm font-semibold w-full pl-10 pr-12 text-gray-800">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-cyan-600 transition duration-150" title="Terapkan Filter dan Pencarian">
                            <i class="fas fa-search text-sm"></i>
                        </button>
                    </div>
                </form>

                {{-- Tombol Aksi (Export & Link) --}}
                <div class="flex items-center space-x-4 w-full xl:w-auto justify-end">
                    <a href="{{ route('admin.data_murid.export', ['class_filter' => $classFilter]) }}" class="inline-flex items-center px-5 py-2.5 bg-cyan-600 text-white font-bold rounded-xl hover:bg-cyan-700 transition duration-300 shadow-lg shadow-cyan-500/50 text-sm transform hover:scale-[1.02] border border-cyan-700">
                        <i class="fas fa-file-download mr-2 text-lg"></i> Export Data Lengkap
                    </a>

                    <a href="https://snowmental.my.id/" target="_blank" title="Buka Formulir Murid" class="p-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-cyan-100 hover:text-cyan-600 transition duration-300 text-xl shadow-md border border-gray-200">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>

            @if(isset($totalMurid) && $totalMurid === 0)
                {{-- Pesan Kosong Data (Warna Cyan) --}}
                <div class="p-12 text-center text-gray-600 border-4 border-dashed border-cyan-300 rounded-xl bg-cyan-50 shadow-xl max-w-4xl mx-auto">
                    <i class="fas fa-info-circle text-6xl mb-4 text-cyan-600"></i>
                    <p class="font-bold text-xl text-gray-800">Data Tidak Tersedia</p>
                    <p class="text-lg mt-2">Belum ada data pengisian formulir murid {{ $classFilter !== 'all' ? 'untuk Kelas ' . $classFilter : '' }}{{ $searchQuery ? ' dengan kata kunci "' . $searchQuery . '"' : '' }} yang dapat dianalisis saat ini.</p>
                </div>
            @else
                
                {{-- Bagian Analisis Jawaban Murid (Dashboard) --}}
                <div class="bg-white overflow-hidden shadow-2xl rounded-3xl border border-gray-100 p-8 md:p-10 ring-4 ring-cyan-50">
                    
                    {{-- Heading Utama Dashboard & Metrik Total --}}
                    <h3 class="font-extrabold text-3xl mb-8 text-gray-900 border-b-4 border-cyan-500/30 pb-3 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <span class="flex items-center mb-2 sm:mb-0"><i class="fas fa-chart-line mr-3 text-cyan-600"></i> Analisis Jawaban Murid</span>
                        <span class="px-5 py-1.5 text-lg font-extrabold bg-cyan-600 text-white rounded-xl shadow-md">
                            Total Responden Ditemukan: {{ $totalMurid }}
                        </span>
                    </h3>

                    {{-- Card Ringkasan Metrik Utama (4 KOLOM) --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

                        {{-- Card 1: Total Murid Mengisi (Cyan) --}}
                        <div class="p-6 bg-cyan-600 text-white rounded-xl shadow-xl transform transition duration-300 hover:scale-[1.03] border border-cyan-700 hover:shadow-cyan-500/50">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium uppercase tracking-wider opacity-90">Total Pengisian</p>
                                <i class="fas fa-users text-4xl opacity-40"></i>
                            </div>
                            <p class="text-5xl font-black mt-2">{{ $totalMurid }}</p>
                        </div>

                        {{-- Card 2: Murid Risiko Sedang/Berat (Red) --}}
                        <div class="p-6 bg-red-600 text-white rounded-xl shadow-xl transform transition duration-300 hover:scale-[1.03] border border-red-700 hover:shadow-red-500/50">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium uppercase tracking-wider opacity-90">Total Risiko Tinggi</p>
                                <i class="fas fa-exclamation-triangle text-4xl opacity-40"></i>
                            </div>
                            <p class="text-5xl font-black mt-2">{{ $totalRiskCount }}</p>
                        </div>

                        {{-- Card 3: Rata-Rata PHQ-9 (White/Red Accent) --}}
                        <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-lg ring-1 ring-red-100 transform transition duration-300 hover:scale-[1.03] hover:shadow-red-200/50">
                            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Avg. Depresi (PHQ-9)</p>
                            <p class="text-4xl font-black text-red-800 mt-1">{{ number_format($avgPhq9, 2) }}</p>
                            <p class="text-sm text-gray-500 mt-2">Skala Max 15</p>
                        </div>

                        {{-- Card 4: Rata-Rata GAD-7 (White/Orange Accent) --}}
                        <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-lg ring-1 ring-orange-100 transform transition duration-300 hover:scale-[1.03] hover:shadow-orange-200/50">
                            <p class="text-xs font-bold text-orange-600 uppercase tracking-widest mb-1">Avg. Kecemasan (GAD-7)</p>
                            <p class="text-4xl font-black text-orange-800 mt-1">{{ number_format($avgGad7, 2) }}</p>
                            <p class="text-sm text-gray-500 mt-2">Skala Max 15</p>
                        </div>
                    </div>

                    <hr class="border-gray-200 mb-8">

                    {{--- CHART TREND & RADAR (TUMPULAN VERTIKAL) ---}}
                    <h3 class="font-bold text-2xl mb-6 text-gray-800 flex items-center">
                        <i class="fas fa-chart-area mr-3 text-cyan-600"></i> Tren dan Profil Kesehatan Global
                    </h3>

                    <div class="grid grid-cols-1 gap-8 mb-12">
                        
                        {{-- Kolom 1: LINE CHART (Tren Pengisian) - Green/Cyan Accent --}}
                        <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 transition duration-300 hover:shadow-lg hover:shadow-green-300/50 flex flex-col border-l-8 border-green-500">
                            <h4 class="text-lg font-extrabold mb-4 text-green-700 flex items-center border-b pb-2">
                                <i class="fas fa-calendar-alt mr-2 text-green-500"></i> Tren Pengisian Mingguan
                            </h4>
                            <div class="h-[400px] flex justify-center items-center">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>

                        {{-- Kolom 2: RADAR CHART (Pola Perilaku) - Cyan/Blue Accent (Diperbesar) --}}
                        <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 transition duration-300 hover:shadow-lg hover:shadow-cyan-300/50 flex flex-col border-l-8 border-cyan-500">
                            <h4 class="text-lg font-extrabold mb-4 text-cyan-700 flex items-center border-b pb-2">
                                <i class="fas fa-spiderweb mr-2 text-cyan-500"></i> Pola Perilaku KPK Rata-Rata
                            </h4>
                            <div class="h-[500px] flex justify-center items-center p-3"> 
                                <canvas id="radarChart" class="min-h-[450px]"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-200 mb-8">
                    
                    {{--- BAGIAN CHART BAR (4 KOLOM VERTICAL) ---}}
                    <h3 class="font-bold text-2xl mb-6 text-gray-800 flex items-center">
                        <i class="fas fa-chart-bar mr-3 text-cyan-600"></i> Distribusi Kategori Skala
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                        {{-- Kolom 1: PHQ-9 (Red Accent) --}}
                        <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 transition duration-300 hover:shadow-lg hover:shadow-red-200/50 flex flex-col border-t-8 border-red-500">
                            <h4 class="text-lg font-extrabold mb-4 text-red-700 flex items-center border-b pb-2">
                                <i class="fas fa-heart-broken mr-2 text-red-500"></i> Depresi (PHQ-9)
                            </h4>
                            <div class="h-[300px] flex justify-center items-center">
                                <canvas id="phq9Chart"></canvas>
                            </div>
                        </div>

                        {{-- Kolom 2: GAD-7 (Orange Accent) --}}
                        <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 transition duration-300 hover:shadow-lg hover:shadow-orange-300/50 flex flex-col border-t-8 border-orange-500">
                            <h4 class="text-lg font-extrabold mb-4 text-orange-700 flex items-center border-b pb-2">
                                <i class="fas fa-brain mr-2 text-orange-500"></i> Kecemasan (GAD-7)
                            </h4>
                            <div class="h-[300px] flex justify-center items-center">
                                <canvas id="gad7Chart"></canvas>
                            </div>
                        </div>
                        
                        {{-- Kolom 3: DASS-21 (Purple Accent) --}}
                        <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 transition duration-300 hover:shadow-lg hover:shadow-purple-300/50 flex flex-col border-t-8 border-purple-500">
                            <h4 class="text-lg font-extrabold mb-4 text-purple-700 flex items-center border-b pb-2">
                                <i class="fas fa-chart-bar mr-2 text-purple-500"></i> DASS-21 Depresi
                            </h4>
                            <div class="h-[300px] flex justify-center items-center">
                                <canvas id="dass21Chart"></canvas>
                            </div>
                        </div>

                        {{-- Kolom 4: Distribusi Pengisian per Kelas (Gray Accent) --}}
                        <div class="bg-white p-6 shadow-xl rounded-xl border border-gray-100 transition duration-300 hover:shadow-lg hover:shadow-gray-300/50 flex flex-col border-t-8 border-gray-400">
                            <h4 class="font-extrabold text-lg mb-4 text-gray-700 flex items-center border-b pb-2">
                                <i class="fas fa-school mr-2 text-gray-500"></i> Distribusi Pengisian per Kelas
                            </h4>
                            <div class="h-[300px] flex justify-center items-center">
                                <canvas id="submissionsPerClassChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200 mb-8">
                    
                    {{--- BAGIAN LIST DETAIL (4 KOLOM) ---}}
                    <h3 class="font-bold text-2xl mb-6 text-gray-800 flex items-center">
                        <i class="fas fa-list-alt mr-3 text-cyan-600"></i> Ringkasan Detail Skala
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        {{-- Kolom 1: PHQ-9 Detail List --}}
                        <div class="bg-white p-5 shadow-2xl rounded-xl border border-gray-100 hover:shadow-red-100/70 transition duration-300">
                            <h4 class="font-bold text-xl mb-4 text-red-800 flex items-center border-b-2 border-red-200 pb-2">
                                <i class="fas fa-notes-medical mr-2 text-red-500"></i> Ringkasan PHQ-9
                            </h4>
                            <ul class="space-y-3">
                                @foreach($phq9Summary as $label => $count)
                                    @php
                                        $percent = ($totalMurid > 0) ? number_format(($count / $totalMurid) * 100, 1) : 0;
                                        $bgClass = match($label) {
                                            'Berat' => 'bg-red-100 text-red-900 ring-red-400 font-extrabold',
                                            'Sedang' => 'bg-orange-100 text-orange-900 ring-orange-400',
                                            default => 'bg-gray-100 text-gray-700 ring-gray-300',
                                        };
                                    @endphp
                                    <li class="flex justify-between items-center text-sm p-3.5 rounded-lg ring-1 ring-inset {{ $bgClass }} shadow-inner transition duration-150 hover:scale-[1.02]">
                                        <span class="font-semibold text-base">{{ $label }}</span>
                                        <span class="font-black text-xl flex items-end">{{ $count }} <span class="text-xs font-medium opacity-80 ml-1">({{ $percent }}%)</span></span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Kolom 2: GAD-7 Detail List --}}
                        <div class="bg-white p-5 shadow-2xl rounded-xl border border-gray-100 hover:shadow-orange-100/70 transition duration-300">
                            <h4 class="font-bold text-xl mb-4 text-orange-800 flex items-center border-b-2 border-orange-200 pb-2">
                                <i class="fas fa-hand-paper mr-2 text-orange-500"></i> Ringkasan GAD-7
                            </h4>
                            <ul class="space-y-3">
                                @foreach($gad7Summary as $label => $count)
                                    @php
                                        $percent = ($totalMurid > 0) ? number_format(($count / $totalMurid) * 100, 1) : 0;
                                        $bgClass = match($label) {
                                            'Berat' => 'bg-red-100 text-red-900 ring-red-400 font-extrabold',
                                            'Sedang' => 'bg-yellow-100 text-yellow-900 ring-yellow-400',
                                            default => 'bg-gray-100 text-gray-700 ring-gray-300',
                                        };
                                    @endphp
                                    <li class="flex justify-between items-center text-sm p-3.5 rounded-lg ring-1 ring-inset {{ $bgClass }} shadow-inner transition duration-150 hover:scale-[1.02]">
                                        <span class="font-semibold text-base">{{ $label }}</span>
                                        <span class="font-black text-xl flex items-end">{{ $count }} <span class="text-xs font-medium opacity-80 ml-1">({{ $percent }}%)</span></span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Kolom 3: DASS-21 Detail List --}}
                        <div class="bg-white p-5 shadow-2xl rounded-xl border border-gray-100 hover:shadow-purple-100/70 transition duration-300">
                            <h4 class="font-bold text-xl mb-4 text-purple-800 flex items-center border-b-2 border-purple-200 pb-2">
                                <i class="fas fa-chart-pie mr-2 text-purple-500"></i> Ringkasan DASS-21
                            </h4>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($dass21Summary as $label => $count)
                                    @php
                                        $percent = ($totalMurid > 0) ? number_format(($count / $totalMurid) * 100, 1) : 0;
                                        $bgClass = match($label) {
                                            'Berat' => 'bg-red-100 text-red-900 ring-red-400 font-extrabold',
                                            'Sedang' => 'bg-orange-100 text-orange-900 ring-orange-400',
                                            'Ringan' => 'bg-indigo-100 text-indigo-800 ring-indigo-400',
                                            default => 'bg-blue-100 text-blue-800 ring-blue-400', // Normal
                                        };
                                    @endphp
                                    <div class="p-3 rounded-xl ring-1 ring-inset {{ $bgClass }} shadow-inner transition duration-150 hover:scale-[1.02] col-span-1">
                                        <span class="font-bold text-sm block leading-tight">{{ $label }}</span>
                                        <span class="font-black text-lg flex items-end mt-0.5">{{ $count }} <span class="text-xs font-medium opacity-80 ml-1">({{ $percent }}%)</span></span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Kolom 4: KPK Detail List (Card Hijau/Positif) --}}
                        <div class="bg-white p-5 shadow-2xl rounded-xl border border-gray-100 hover:shadow-green-100/70 transition duration-300">
                            <h4 class="font-bold text-xl mb-4 text-green-800 flex items-center border-b-2 border-green-200 pb-2">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i> Ringkasan KPK Global
                            </h4>
                            <ul class="space-y-3">
                                @foreach($kpkSummary as $label => $count)
                                    @php $percent = ($totalMurid > 0) ? number_format(($count / $totalMurid) * 100, 1) : 0; @endphp
                                    <li class="flex justify-between items-center text-sm p-3.5 rounded-lg ring-1 ring-inset {{ $label == 'Perlu Perbaikan' ? 'bg-red-100 text-red-900 ring-red-400 font-extrabold' : 'bg-green-100 text-green-900 ring-green-400' }} shadow-inner transition duration-150 hover:scale-[1.02]">
                                        <span class="font-semibold text-base">{{ $label }}</span>
                                        <span class="font-black text-xl flex items-end">{{ $count }} <span class="text-xs font-medium opacity-80 ml-1">({{ $percent }}%)</span></span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                <hr class="border-gray-200 mt-10 mb-8">

                {{-- Tabel Detail Data Murid (Tanpa NIS) --}}
                <div class="bg-white overflow-hidden shadow-2xl rounded-3xl border border-gray-100 p-8 ring-4 ring-cyan-50">
                    <h3 class="font-extrabold text-2xl mb-6 text-gray-900 border-b-2 border-gray-200 pb-3 flex items-center">
                        <i class="fas fa-table mr-3 text-cyan-600"></i> Detail Data Murid ({{ $totalMurid }} Hasil)
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table id="studentTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- HANYA NAMA MURID (Tanpa NIS) --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Murid</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori PHQ-9</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KPK Global</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GAD-7</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($studentDetails as $student)
                                    @php
                                        // Fungsi helper untuk menentukan warna badge
                                        $getBadgeColor = function($category) {
                                            return match($category) {
                                                'Berat', 'Perlu Perbaikan' => ['bg-red-100', 'text-red-800'],
                                                'Sedang' => ['bg-yellow-100', 'text-yellow-800'],
                                                'Ringan' => ['bg-indigo-100', 'text-indigo-800'],
                                                'Minimal', 'Baik', 'Normal' => ['bg-green-100', 'text-green-800'],
                                                default => ['bg-gray-100', 'text-gray-800'],
                                            };
                                        };
                                        
                                        [$phq9Bg, $phq9Text] = $getBadgeColor($student['phq9_kategori']);
                                        [$gad7Bg, $gad7Text] = $getBadgeColor($student['gad7_kategori']);
                                        [$kpkBg, $kpkText] = $getBadgeColor($student['kpk_global']);
                                    @endphp
                                    <tr class="data-row hover:bg-gray-50 transition duration-150">
                                        {{-- KOLOM PERTAMA: NAMA MURID (Tanpa NIS) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <span class="font-bold">{{ $student['name'] }}</span>
                                        </td>
                                        {{-- KOLOM KEDUA: KELAS --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['class'] }}</td>
                                        
                                        {{-- KOLOM LAINNYA --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $phq9Bg }} {{ $phq9Text }}">
                                                {{ $student['phq9_kategori'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $kpkBg }} {{ $kpkText }}">
                                                {{ $student['kpk_global'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $gad7Bg }} {{ $gad7Text }}">
                                                {{ $student['gad7_kategori'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- End of Detail Table --}}
            @endif

        </div>
    </div>

    {{-- Script Chart.js (Tetap sama) --}}
    <script>
        // Daftarkan plugin Datalabels
        Chart.register(ChartDataLabels);
        
        // =======================================================
        // DATA PHP / REAL-TIME
        // =======================================================
        const phq9Counts = @json(array_values($phq9Summary ?? []));
        const gad7Counts = @json(array_values($gad7Summary ?? []));
        const dass21Counts = @json(array_values($dass21Summary ?? []));
        const labels = @json(array_keys($phq9Summary ?? [])); 

        const submissionsPerClass = @json(array_values($submissionsPerClass ?? []));
        const classLabels = @json(array_keys($submissionsPerClass ?? []));

        const weeklySubmissions = @json($weeklySubmissions ?? []); 
        const weeklyLabels = @json($weeklyLabels ?? []);
        const kpkRadarData = @json($kpkRadarData ?? []);
        const kpkRadarLabels = @json($kpkRadarLabels ?? []);


        // =======================================================
        // CUSTOM COLORS & OPTIONS (Modern Palette)
        // =======================================================
        const classificationColors = [
            'rgba(59, 130, 246, 0.85)', // Minimal / Normal - Blue
            'rgba(126, 172, 237, 0.85)', // Ringan - Light Blue/Indigo
            'rgba(251, 191, 36, 0.85)', // Sedang - Yellow/Amber
            'rgba(239, 68, 68, 0.85)', // Berat - Red
        ];

        const classificationBorderColors = [
            '#3b82f6', 
            '#7eacff', 
            '#f59e0b', 
            '#ef4444', 
        ];


        const classColors = classLabels.map((_, i) => i % 2 === 0 ? 'rgba(45, 212, 190, 0.8)' : 'rgba(99, 102, 241, 0.8)'); // Cyan & Indigo
        const classBorderColors = classLabels.map((_, i) => i % 2 === 0 ? '#2dd4c0' : '#6366f1');


        // Fungsi Opsi Chart yang Lebih Profesional (Clean look)
        const getChartOptions = (chartType, dataArray, index, suggestedMax = null) => {
            const maxVal = Math.max(...(dataArray.length > 0 ? dataArray : [0, 5])); 
            const isVertical = index < 4; 

            const scaleOptions = {};
            if (chartType === 'bar' && isVertical) {
                scaleOptions.y = {
                    beginAtZero: true,
                    suggestedMax: suggestedMax ? suggestedMax : (maxVal > 0 ? Math.ceil(maxVal * 1.2) : 5),
                    title: { display: true, text: 'Jumlah Murid', color: '#4b5563', font: { weight: 'bold', size: 12 } },
                    grid: { color: '#e5e7eb' }, 
                    ticks: { stepSize: 1, color: '#6b7280' }
                };
                scaleOptions.x = { 
                    grid: { display: false }, 
                    ticks: { color: '#6b7280' } 
                };
            } else if (chartType === 'line') {
                scaleOptions.y = {
                    beginAtZero: true,
                    suggestedMax: suggestedMax ? suggestedMax : (maxVal > 0 ? Math.ceil(maxVal * 1.3) : 5),
                    title: { display: true, text: 'Pengisian', color: '#4b5563', font: { weight: 'bold', size: 12 } },
                    grid: { color: '#e5e7eb' },
                    ticks: { stepSize: Math.max(1, Math.floor(maxVal / 5) || 1), color: '#6b7280' }
                };
                scaleOptions.x = { grid: { display: false }, ticks: { color: '#6b7280' } };
            } else if (chartType === 'radar') {
                scaleOptions.r = {
                    angleLines: { color: '#e5e7eb' },
                    grid: { color: '#e5e7eb' },
                    suggestedMin: 0,
                    suggestedMax: 5.5, 
                    ticks: {
                        backdropColor: 'rgba(255, 255, 255, 0.8)', 
                        color: '#4b5563',
                        font: { size: 12, weight: 'bold' },
                        stepSize: 1 
                    },
                    pointLabels: {
                        font: { size: 13, weight: 'bold' }, 
                        color: '#374151',
                        padding: 15
                    },
                };
            }


            return {
                indexAxis: 'x', 
                responsive: true,
                maintainAspectRatio: false, 
                plugins: {
                    legend: { display: false },
                    title: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.95)', 
                        titleFont: { size: 14, weight: 'bold', color: '#ffffff' },
                        bodyFont: { size: 12, color: '#e2e8f0' },
                        padding: 12,
                        boxPadding: 5,
                    },
                    datalabels: {
                        color: (context) => {
                            if (chartType === 'radar') return '#374151'; 
                            if (chartType === 'line') return '#065f46';
                            
                            const color = classificationBorderColors[context.dataIndex] || '#4b5563';
                            return color;
                        },
                        anchor: 'end',
                        align: 'end',
                        offset: chartType === 'radar' ? 10 : 4, 
                        formatter: function(value) { 
                            return chartType === 'radar' ? parseFloat(value).toFixed(1) : (value > 0 ? value : ''); 
                        },
                        font: { weight: 'bold', size: 11 }
                    }
                },
                scales: chartType === 'radar' ? { r: scaleOptions.r } : scaleOptions,
                animation: { duration: 1600, easing: 'easeOutQuint' } 
            };
        };

        // =======================================================
        // INSTANSIASI CHART (Tidak ada perubahan)
        // =======================================================

        // 1. Diagram PHQ-9 (Vertical Bar Chart)
        const phq9Ctx = document.getElementById('phq9Chart').getContext('2d');
        new Chart(phq9Ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Murid',
                    data: phq9Counts,
                    backgroundColor: classificationColors,
                    borderColor: classificationBorderColors,
                    borderWidth: 1,
                    borderRadius: 6, 
                }]
            },
            options: getChartOptions('bar', phq9Counts, 0)
        });

        // 2. Diagram GAD-7 (Vertical Bar Chart)
        const gad7Ctx = document.getElementById('gad7Chart').getContext('2d');
        new Chart(gad7Ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Murid',
                    data: gad7Counts,
                    backgroundColor: classificationColors,
                    borderColor: classificationBorderColors,
                    borderWidth: 1,
                    borderRadius: 6, 
                }]
            },
            options: getChartOptions('bar', gad7Counts, 1)
        });

        // 3. Diagram DASS-21 Detail (Vertical Bar Chart)
        const dass21Ctx = document.getElementById('dass21Chart').getContext('2d');
        const dass21Labels = ['Normal', 'Ringan', 'Sedang', 'Berat']; 
        new Chart(dass21Ctx, {
            type: 'bar',
            data: {
                labels: dass21Labels,
                datasets: [{
                    label: 'Jumlah Murid',
                    data: dass21Counts, 
                    backgroundColor: classificationColors, 
                    borderColor: classificationBorderColors,
                    borderWidth: 1,
                    borderRadius: 6, 
                }]
            },
            options: getChartOptions('bar', dass21Counts, 2)
        });
        
        // 4. LINE CHART (Tren Pengisian Mingguan) - REAL-TIME
        const lineCtx = document.getElementById('lineChart');
        if (lineCtx && weeklyLabels.length > 0) {
            new Chart(lineCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: weeklyLabels, 
                    datasets: [{
                        label: 'Pengisian per Hari',
                        data: weeklySubmissions, 
                        borderColor: '#10b981', 
                        backgroundColor: 'rgba(16, 185, 129, 0.2)', 
                        borderWidth: 3,
                        tension: 0.4, 
                        fill: true,
                        pointBackgroundColor: '#047857',
                        pointRadius: 5,
                        pointHoverRadius: 8,
                    }]
                },
                options: getChartOptions('line', weeklySubmissions, 4) 
            });
        }

        // 5. RADAR CHART (Pola Perilaku KPK) - REAL-TIME
        const radarCtx = document.getElementById('radarChart');
        if (radarCtx && kpkRadarLabels.length > 0) {
            new Chart(radarCtx.getContext('2d'), {
                type: 'radar',
                data: {
                    labels: kpkRadarLabels, 
                    datasets: [{
                        label: 'Rata-rata Perilaku (Skala 0-5.5)',
                        data: kpkRadarData, 
                        borderColor: '#6366f1', 
                        backgroundColor: 'rgba(99, 102, 241, 0.25)',
                        borderWidth: 3,
                        pointBackgroundColor: '#4f46e5',
                        pointRadius: 6,
                        fill: true
                    }]
                },
                options: getChartOptions('radar', kpkRadarData, 5) 
            });
        }


        // 6. DIAGRAM Pengisian Per Kelas (Vertical Bar Chart)
        const classCtx = document.getElementById('submissionsPerClassChart');
        if (classCtx) {
            new Chart(classCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: classLabels,
                    datasets: [{
                        label: 'Jumlah Pengisian',
                        data: submissionsPerClass,
                        backgroundColor: classColors,
                        borderColor: classBorderColors,
                        borderWidth: 1,
                        borderRadius: 6, 
                    }]
                },
                options: getChartOptions('bar', submissionsPerClass, 3)
            });
        }
    </script>
</x-app-layout>