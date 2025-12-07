<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            {{-- ICON BARU: Ikon Anomali/Peringatan (Hitam/Gray 800) --}}
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{-- Hanya menyisakan Deteksi Anomali (Outlier Detection) --}}
                <span class="text-cyan-600 font-extrabold">Deteksi Anomali (Outlier Detection)</span>
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
                
                {{-- Judul Card Dihapus (sesuai permintaan) --}}
                <h3 class="text-2xl font-extrabold text-cyan-700 mb-2"></h3>
                
                <p class="mb-6 text-gray-500 border-b pb-4">Modul ini menjalankan simulasi Deteksi Anomali untuk mengidentifikasi data murid yang secara signifikan berbeda dari mayoritas (Outlier). Data ini perlu diperiksa oleh ahli Dokter Anak/Psikolog Klinis.</p>
                
                {{-- Ringkasan dan Tombol Proses --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    {{-- Ringkasan Anomali --}}
                    <div class="md:col-span-2 p-5 rounded-xl shadow-lg border border-red-200/80 bg-red-50/50">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 block mb-1">Total Data Analisis: <span class="font-bold text-cyan-700">{{ $totalResults ?? 0 }}</span></p>
                                
                                <p class="text-md text-gray-700 mt-2">
                                    Total Murid Anomali Ditemukan: 
                                    <span class="font-extrabold text-3xl mr-1 text-red-700 leading-none">{{ $anomalyCount ?? 0 }}</span> Murid 
                                    <span class="font-bold text-red-600">({{ $anomalyPercentage ?? 0 }}%)</span>
                                </p>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-4 sm:mt-0">
                                Murid berstatus anomali memiliki Skor Anomali tinggi dan membutuhkan perhatian khusus.
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="md:col-span-1 flex flex-col justify-center items-stretch p-5 bg-cyan-50/50 border border-cyan-200 rounded-xl shadow-lg">
                        <form method="POST" action="{{ route('admin.mining.sekuensing.run') }}">
                            @csrf
                            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 shadow-lg shadow-cyan-500/50 transform hover:scale-[1.02] focus:ring-4 focus:ring-cyan-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Jalankan Ulang Deteksi Anomali
                                </span>
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 text-center mt-3">Proses ini akan mengidentifikasi pola data yang menyimpang.</p>
                    </div>

                </div>

                <hr class="my-6 border-gray-200">

                {{-- BAGIAN VISUALISASI (BOX PLOT) --}}
                <div class="mt-8 mb-8">
                    <h4 class="font-extrabold text-lg text-gray-800 mb-4">Visualisasi Sebaran Skor Anomali (Box Plot)</h4>
                    <p class="text-sm text-gray-600 mb-4 border-l-4 border-cyan-400 pl-3">
                        Box Plot membantu mengidentifikasi outlier secara visual. Titik-titik di luar garis menunjukkan skor anomali yang ekstrem.
                    </p>
                    <div id="boxPlotContainer" class="w-full overflow-x-auto p-4 border rounded-xl bg-gray-50 shadow-inner" style="min-height: 400px;">
                        @if(isset($anomalySummary['anomaly_scores']) && count($anomalySummary['anomaly_scores']) > 0)
                            <div id="anomalyBoxPlot" style="width: 100%; height: 400px;"></div>
                        @else
                            <p class="text-center text-gray-500 italic py-4">Tidak ada skor anomali yang tersedia untuk divisualisasikan.</p>
                        @endif
                    </div>
                </div>
                
                {{-- Tabel Hasil Anomali --}}
                <h4 class="font-extrabold text-lg mt-10 mb-4 text-gray-800">Daftar Hasil Anomali (Urutan Skor Tertinggi)</h4>
                
                {{-- BAGIAN PENCARIAN & FILTER --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-gray-600">Filter Status:</span>
                        {{-- Dropdown Filter Status Anomali (Diasumsikan $filterAnomaly ada di Controller) --}}
                        <select onchange="window.location.href=this.value" class="border-gray-300 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 shadow-sm">
                            <option value="{{ route('admin.mining.sekuensing', ['search' => $search ?? null]) }}" @if(!($filterAnomaly ?? null)) selected @endif>Semua Status</option>
                            <option value="{{ route('admin.mining.sekuensing', ['filter_anomaly' => 'YES', 'search' => $search ?? null]) }}" @if(($filterAnomaly ?? null) === 'YES') selected @endif>Anomali (YES)</option>
                            <option value="{{ route('admin.mining.sekuensing', ['filter_anomaly' => 'NO', 'search' => $search ?? null]) }}" @if(($filterAnomaly ?? null) === 'NO') selected @endif>Normal (NO)</option>
                        </select>
                    </div>

                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ route('admin.mining.sekuensing') }}" class="flex items-center space-x-2 w-full md:w-auto">
                        <input type="hidden" name="filter_anomaly" value="{{ $filterAnomaly ?? null }}">
                        <input type="text" name="search" placeholder="Cari Nama Murid..." 
                            value="{{ $search ?? '' }}"
                            class="border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 rounded-lg shadow-sm w-full">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-150">
                            Cari
                        </button>
                        @if(($search ?? null) || ($filterAnomaly ?? null))
                            <a href="{{ route('admin.mining.sekuensing') }}" class="text-sm text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Tabel Hasil Anomali --}}
                @if($results->isNotEmpty())
                    <div class="overflow-x-auto border rounded-xl shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-cyan-600/10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Murid (Kelas)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Skor Anomali</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Alasan Anomali</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($results as $result)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-bold">{{ $result->studentData->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $result->studentData->class_level ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">
                                        <span class="font-extrabold text-red-700">{{ number_format($result->anomaly_score, 4) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                        @php
                                            // Badge Status Anomali
                                            $anomalyStatus = $result->is_anomaly;
                                            $colorClass = $anomalyStatus === 'YES' 
                                                ? 'bg-red-50 text-red-700 border-red-300' 
                                                : 'bg-emerald-50 text-emerald-700 border-emerald-300';
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-extrabold rounded-full border {{ $colorClass }} shadow-sm">
                                            {{ $anomalyStatus }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $result->anomaly_reason }}
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
                    <p class="text-gray-500 italic p-4 border rounded-lg bg-gray-50">Belum ada hasil Deteksi Anomali yang tersimpan. Silakan jalankan proses menggunakan tombol di atas.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- SCRIPT PLOTLY.JS UNTUK BOX PLOT --}}
    <script src="https://cdn.plot.ly/plotly-2.32.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mengakses elemen array menggunakan notasi []
            const anomalyScores = @json($anomalySummary['anomaly_scores'] ?? []);

            if (anomalyScores.length > 0) {
                // Konfigurasi data Box Plot
                const data = [{
                    y: anomalyScores,
                    type: 'box',
                    name: 'Skor Anomali',
                    boxpoints: 'all', 
                    jitter: 0.3, 
                    pointpos: -1.8,
                    marker: {
                        color: 'rgba(220, 38, 38, 0.7)', // Titik merah
                        size: 5,
                        line: {
                            color: 'rgba(220, 38, 38, 1)',
                            width: 1
                        }
                    },
                    line: {
                        color: '#06b6d4', // Warna garis box (Cyan 600)
                    },
                    fillcolor: 'rgba(6, 182, 212, 0.4)' // Fill box (Cyan 500)
                }];

                // Konfigurasi layout
                const layout = {
                    title: 'Sebaran Skor Anomali (Titik di luar batas = Outlier)',
                    titlefont: { size: 16, color: '#4b5563' },
                    yaxis: {
                        title: 'Skor Anomali (0.0 - 1.0)',
                        zeroline: false,
                        range: [0, 1.0],
                    },
                    margin: {
                        l: 60, r: 20, b: 80, t: 60
                    },
                    hovermode: 'closest',
                    showlegend: false,
                    // Garis threshold simulasi anomali (misalnya di 0.7)
                    shapes: [{
                        type: 'line',
                        xref: 'paper',
                        yref: 'y',
                        x0: 0, y0: 0.7, 
                        x1: 1, y1: 0.7,
                        line: {
                            color: 'rgb(255, 0, 0)',
                            width: 1,
                            dash: 'dot'
                        }
                    }],
                    annotations: [{
                        xref: 'paper',
                        yref: 'y',
                        x: 0.8, y: 0.7,
                        text: 'Threshold',
                        showarrow: false,
                        font: { color: 'rgb(255, 0, 0)', size: 10 }
                    }]
                };

                Plotly.newPlot('anomalyBoxPlot', data, layout, {responsive: true});
            } else {
                // Tampilkan pesan jika tidak ada data
                const boxPlotContainer = document.getElementById('boxPlotContainer');
                if (boxPlotContainer) {
                    boxPlotContainer.innerHTML = `
                        <p class="text-center text-gray-500 italic py-4">
                            Tidak ada skor anomali yang tersedia untuk divisualisasikan.
                        </p>
                    `;
                }
            }
        });
    </script>
</x-app-layout>