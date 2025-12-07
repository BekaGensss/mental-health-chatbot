<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            {{-- ICON BARU: Ikon Tautan/Rantai (Hitam/Gray 800) --}}
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101M16.907 17.072l3.435-3.434a4 4 0 00-5.656-5.656l-1.11 1.11"></path></svg>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{-- Hanya menyisakan Asosiasi Pola (Hubungan Jawaban) --}}
                <span class="text-cyan-600 font-extrabold">Asosiasi Pola (Hubungan Jawaban)</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Sukses --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-lg shadow-md" role="alert">
                    <p class="font-semibold flex items-center">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Analisis Berhasil!
                    </p>
                    <span class="block sm:inline text-sm">{{ session('success') }}</span>
                </div>
            @endif
            
            {{-- Panel Utama (Card Modern) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200/70 sm:rounded-xl p-6 lg:p-8 mb-8 border border-gray-100/50">
                
                {{-- Judul Card Dihapus --}}
                <h3 class="text-2xl font-extrabold text-cyan-700 mb-2"></h3>
                
                <p class="mb-6 text-gray-500 border-b pb-4">Modul ini menjalankan simulasi Algoritma Apriori untuk menemukan aturan asosiasi (pola hubungan) yang kuat antar jawaban murid.</p>
                
                {{-- Ringkasan dan Tombol Proses --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    {{-- Ringkasan Aturan --}}
                    <div class="md:col-span-2 p-5 rounded-xl shadow-lg border border-cyan-200/80 bg-cyan-50/50">
                        <span class="text-sm font-semibold text-gray-600 block mb-2">Total Aturan Ditemukan: <span class="font-bold text-cyan-700">{{ $totalRules ?? 0 }}</span></span>
                        <h4 class="font-bold text-xl text-gray-800 mt-2 mb-4">Ringkasan Aturan Kuat</h4>
                        
                        <p class="text-md text-gray-700 mt-2">
                            Aturan dengan Confidence (Keyakinan) &ge; 80%: 
                            <span class="font-extrabold text-3xl mr-1 text-cyan-600 leading-none">{{ $highConfidenceRules ?? 0 }}</span> Aturan.
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            Angka Lift (Peningkatan) di atas 1.0 menunjukkan adanya korelasi positif antar item.
                        </p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="md:col-span-1 flex flex-col justify-center items-stretch p-5 bg-cyan-50/50 border border-cyan-200 rounded-xl shadow-lg">
                        <form method="POST" action="{{ route('admin.mining.asosiasi.run') }}">
                            @csrf
                            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 shadow-lg shadow-cyan-500/50 transform hover:scale-[1.02] focus:ring-4 focus:ring-cyan-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Jalankan Ulang Asosiasi Pola
                                </span>
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 text-center mt-3">Proses ini akan menemukan pola hubungan terbaru.</p>
                    </div>

                </div>

                <hr class="my-6 border-gray-200">

                {{-- BAGIAN VISUALISASI (HEATMAP) --}}
                <div class="mt-8 mb-8">
                    <h4 class="font-extrabold text-lg text-gray-800 mb-4">Peta Panas (Heatmap) Confidence Matriks</h4>
                    <p class="text-sm text-gray-600 mb-4 border-l-4 border-cyan-400 pl-3">
                        Peta Panas menunjukkan seberapa besar keyakinan (Confidence) dari hubungan antar pasangan item. Warna yang lebih gelap menunjukkan hubungan yang lebih kuat.
                    </p>
                    
                    {{-- Kontainer Plotly --}}
                    <div id="heatmapContainer" class="w-full overflow-x-auto p-4 border rounded-xl bg-gray-50 shadow-inner" style="min-height: 500px;">
                        {{-- Cek jika ada data sebelum mencoba merender Plotly --}}
                        @if(($totalRules ?? 0) > 0)
                            <div id="associationHeatmap" style="width: 100%; height: 500px;"></div>
                        @else
                            <p class="text-center text-gray-500 italic py-4">Tidak ada aturan asosiasi yang tersedia untuk divisualisasikan.</p>
                        @endif
                    </div>
                </div>
                
                {{-- Tabel Hasil Asosiasi (Rules) --}}
                <h4 class="font-extrabold text-lg mt-10 mb-4 text-gray-800">Daftar Aturan Asosiasi (Rules) Ditemukan</h4>

                {{-- BAGIAN PENCARIAN & FILTER --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-gray-600">Filter Confidence:</span>
                        {{-- Dropdown Filter Confidence (Diasumsikan $filterConfidence ada di Controller) --}}
                        <select onchange="window.location.href=this.value" class="border-gray-300 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 shadow-sm">
                            <option value="{{ route('admin.mining.asosiasi', ['search' => $search ?? null]) }}" @if(!($filterConfidence ?? null)) selected @endif>Semua Aturan</option>
                            <option value="{{ route('admin.mining.asosiasi', ['filter_confidence' => 'Tinggi', 'search' => $search ?? null]) }}" @if(($filterConfidence ?? null) === 'Tinggi') selected @endif>Confidence Tinggi (>= 80%)</option>
                            <option value="{{ route('admin.mining.asosiasi', ['filter_confidence' => 'Rendah', 'search' => $search ?? null]) }}" @if(($filterConfidence ?? null) === 'Rendah') selected @endif>Confidence Rendah (< 80%)</option>
                        </select>
                    </div>

                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ route('admin.mining.asosiasi') }}" class="flex items-center space-x-2 w-full md:w-auto">
                        <input type="hidden" name="filter_confidence" value="{{ $filterConfidence ?? null }}">
                        <input type="text" name="search" placeholder="Cari Antecedent/Consequent..." 
                            value="{{ $search ?? '' }}"
                            class="border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 rounded-lg shadow-sm w-full">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-150">
                            Cari
                        </button>
                        @if(($search ?? null) || ($filterConfidence ?? null))
                            <a href="{{ route('admin.mining.asosiasi') }}" class="text-sm text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50">Reset</a>
                        @endif
                    </form>
                </div>
                
                @if($results->isNotEmpty())
                    <div class="overflow-x-auto border rounded-xl shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-cyan-600/10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Aturan (IF &rarr; THEN)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Support (Dukungan)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Confidence (Keyakinan)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Lift (Peningkatan)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($results as $result)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        IF (<span class="font-semibold">{{ $result->antecedent }}</span>) <span class="font-extrabold text-cyan-600 text-lg">&rarr;</span> THEN (<span class="font-semibold">{{ $result->consequent }}</span>)
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">
                                        {{ number_format($result->support_value * 100, 2) }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                        @php
                                            // Badge Confidence
                                            $confClass = $result->confidence_value >= 0.8 
                                                ? 'bg-cyan-50 text-cyan-700 border-cyan-300' 
                                                : 'bg-gray-100 text-gray-600 border-gray-300';
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-extrabold rounded-full border {{ $confClass }} shadow-sm">
                                            {{ number_format($result->confidence_value * 100, 2) }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">
                                        <span class="{{ $result->lift_value > 1.0 ? 'text-emerald-600 font-extrabold' : 'text-gray-500' }}">
                                            {{ number_format($result->lift_value, 2) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        {{ $results->links() }}
                    </div>
                @else
                    <p class="text-gray-500 italic p-4 border rounded-lg bg-gray-50">Belum ada hasil Asosiasi Pola yang tersimpan. Silakan jalankan proses menggunakan tombol di atas.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- SCRIPT PLOTLY.JS UNTUK HEATMAP --}}
    <script src="https://cdn.plot.ly/plotly-2.32.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const results = @json($results->items() ?? []);
            
            if (results.length > 0) {
                // 1. Ekstraksi dan Pembersihan Data untuk Matriks Heatmap
                let items = new Set();
                results.forEach(rule => {
                    items.add(rule.antecedent);
                    items.add(rule.consequent);
                });
                const itemLabels = Array.from(items);
                const size = itemLabels.length;

                // Inisialisasi Matriks Confidence [size x size]
                let confidenceMatrix = Array(size).fill(0).map(() => Array(size).fill(0));

                // Isi matriks dengan nilai Confidence
                results.forEach(rule => {
                    const i = itemLabels.indexOf(rule.antecedent);
                    const j = itemLabels.indexOf(rule.consequent);
                    if (i !== -1 && j !== -1) {
                        confidenceMatrix[i][j] = rule.confidence_value;
                    }
                });

                // 2. Konfigurasi Plotly Heatmap
                const data = [{
                    z: confidenceMatrix, // Nilai Confidence
                    x: itemLabels, // Label sumbu X (Consequent)
                    y: itemLabels, // Label sumbu Y (Antecedent)
                    type: 'heatmap',
                    hoverongaps: false,
                    colorscale: [
                        [0, 'rgb(240, 240, 240)'], // Nilai 0 = Abu-abu terang/putih
                        [0.5, 'rgb(245, 191, 11)'], // Nilai Sedang = Orange/Amber
                        [1, 'rgb(6, 182, 212)'] // Nilai 1 = Cyan (Confidence Tinggi)
                    ],
                    colorbar: {
                        title: 'Confidence Value',
                        titleside: 'right',
                        tickvals: [0, 0.5, 1],
                        ticktext: ['0%', '50%', '100%']
                    }
                }];

                // 3. Konfigurasi Layout
                const layout = {
                    title: 'Confidence Matriks (Antecedent vs Consequent)',
                    font: {
                        family: 'Arial, sans-serif',
                        size: 14,
                        color: '#4b5563'
                    },
                    xaxis: {
                        title: 'Consequent (THEN)',
                        automargin: true, 
                        tickangle: 45 // Sudut label X agar terbaca
                    },
                    yaxis: {
                        title: 'Antecedent (IF)',
                        automargin: true,
                        tickangle: 0 // Label Y horizontal
                    },
                    margin: {
                        l: 250, // Margin kiri besar untuk label Y
                        r: 50,
                        b: 200, // Margin bawah besar untuk label X
                        t: 50
                    },
                    height: 600, // Menambah tinggi sedikit
                    width: 1000 // Lebar tetap untuk Heatmap
                };

                Plotly.newPlot('associationHeatmap', data, layout, {responsive: true});
            } else {
                // Tampilkan pesan jika tidak ada data
                document.getElementById('heatmapContainer').innerHTML = `
                    <p class="text-center text-gray-500 italic py-4">
                        Tidak ada aturan asosiasi yang tersedia untuk divisualisasikan.
                    </p>
                `;
            }
        });
    </script>
</x-app-layout>