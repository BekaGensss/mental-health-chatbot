<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            {{-- ICON BARU: Ikon Chart/Regresi (Hitam/Gray 800) --}}
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{-- Hanya menyisakan Regresi (Prediksi Nilai Kontinu) --}}
                <span class="text-cyan-600 font-extrabold">Regresi (Prediksi Nilai Kontinu)</span>
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
                
                <p class="mb-6 text-gray-500 border-b pb-4">Modul ini menjalankan simulasi Regresi untuk memprediksi nilai akademik murid (variabel kontinu) berdasarkan skor kesehatan mental dan perilaku mereka (PHQ-9, GAD-7, DASS-21, KPK).</p>
                
                {{-- Statistik Evaluasi dan Tombol Aksi (Dua Kolom di Desktop) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    {{-- Statistik Evaluasi --}}
                    <div class="md:col-span-2 p-5 border border-cyan-200/80 bg-cyan-50/50 rounded-xl shadow-lg">
                        <span class="text-sm font-semibold text-gray-600 block mb-3">Evaluasi Model Regresi (Rata-rata dari {{ $totalResults ?? 0 }} Data):</span>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-8">
                            
                            {{-- R-Squared --}}
                            <div>
                                <span class="text-5xl font-extrabold text-cyan-700 leading-none">{{ $avgRsq ?? '0.0000' }}</span>
                                <span class="text-gray-700 block text-sm font-semibold mt-1">R-Squared (Kualitas Model)</span>
                            </div>
                            
                            {{-- MSE --}}
                            <div>
                                <span class="text-5xl font-extrabold text-cyan-700 leading-none">{{ $avgMse ?? '0.0000' }}</span>
                                <span class="text-gray-700 block text-sm font-semibold mt-1">MSE (Rata-rata Error)</span>
                            </div>

                        </div>
                        <p class="text-xs text-gray-500 mt-4">R-Squared mendekati 1.0 menunjukkan model yang lebih baik dalam menjelaskan variasi data.</p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="md:col-span-1 flex flex-col justify-center items-stretch p-5 bg-cyan-50/50 border border-cyan-200 rounded-xl shadow-lg">
                        <form method="POST" action="{{ route('admin.mining.regresi.run') }}">
                            @csrf
                            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 shadow-lg shadow-cyan-500/50 transform hover:scale-[1.02] focus:ring-4 focus:ring-cyan-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Jalankan Ulang Regresi
                                </span>
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 text-center mt-3">Proses ini akan menghitung ulang nilai prediksi.</p>
                    </div>

                </div>
                
                {{--- BAGIAN VISUALISASI (SCATTER PLOT) ---}}
                <h4 class="font-extrabold text-lg text-gray-800 mt-6 mb-4 pt-4 border-t border-gray-100">Distribusi Nilai Prediksi (Scatter Plot)</h4>
                <div class="bg-gray-50 border border-gray-200/80 p-6 rounded-xl shadow-inner mb-8">
                    <div class="w-full md:w-4/5 lg:w-3/4 mx-auto">
                         @if(isset($chartData) && count($chartData) > 0)
                            <canvas id="regressionScatterPlot"></canvas>
                        @else
                            <p class="text-center text-gray-500 italic py-4">Tidak ada data prediksi untuk visualisasi.</p>
                        @endif
                    </div>
                </div>
                
                <h4 class="font-extrabold text-lg text-gray-800 mt-6 mb-4">Daftar Hasil Prediksi Nilai Akademik Terbaru</h4>

                {{-- BAGIAN PENCARIAN & FILTER --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-gray-600">Filter Nilai:</span>
                        {{-- Dropdown Filter Nilai --}}
                        <select onchange="window.location.href=this.value" class="border-gray-300 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 shadow-sm">
                            <option value="{{ route('admin.mining.regresi', ['search' => $search ?? null]) }}" @if(!($filterValue ?? null)) selected @endif>Semua Nilai</option>
                            <option value="{{ route('admin.mining.regresi', ['filter_value' => 'Tinggi', 'search' => $search ?? null]) }}" @if(($filterValue ?? null) === 'Tinggi') selected @endif>Nilai Tinggi (>= 80)</option>
                            <option value="{{ route('admin.mining.regresi', ['filter_value' => 'Rendah', 'search' => $search ?? null]) }}" @if(($filterValue ?? null) === 'Rendah') selected @endif>Nilai Rendah (< 65)</option>
                        </select>
                    </div>

                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ route('admin.mining.regresi') }}" class="flex items-center space-x-2 w-full md:w-auto">
                        <input type="hidden" name="filter_value" value="{{ $filterValue ?? null }}">
                        <input type="text" name="search" placeholder="Cari Nama Murid..." 
                            value="{{ $search ?? '' }}"
                            class="border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 rounded-lg shadow-sm w-full">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-150">
                            Cari
                        </button>
                        @if(($search ?? null) || ($filterValue ?? null))
                            <a href="{{ route('admin.mining.regresi') }}" class="text-sm text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Tabel Hasil Regresi --}}
                @if($results->isNotEmpty())
                    <div class="overflow-x-auto border rounded-xl shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-cyan-600/10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Murid (Kelas)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">4 Skor Input Utama</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Nilai Prediksi</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Waktu Analisis</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($results as $result)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-bold">{{ $result->studentData->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $result->studentData->class_level ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700 font-medium">
                                        PHQ: <span class="font-bold text-cyan-600">{{ $result->phq9_score }}</span> | GAD: <span class="font-bold text-cyan-600">{{ $result->gad7_score }}</span><br>
                                        DASS: <span class="font-bold text-cyan-600">{{ $result->dass21_score }}</span> | KPK: <span class="font-bold text-cyan-600">{{ $result->kpk_score }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                        @php
                                            $value = number_format($result->predicted_value, 2);
                                            $badgeClass = 'bg-cyan-50 text-cyan-700 border border-cyan-300';
                                            if ($result->predicted_value >= 80) {
                                                $badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-300';
                                            } elseif ($result->predicted_value < 65) {
                                                $badgeClass = 'bg-red-50 text-red-700 border border-red-300';
                                            }
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-extrabold rounded-full border {{ $badgeClass }} shadow-sm">
                                            {{ $value }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 italic">
                                        {{ $result->created_at->diffForHumans() }}
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
                    <p class="text-gray-500 italic p-4 border rounded-lg bg-gray-50">Belum ada hasil Regresi yang tersimpan. Silakan jalankan proses di atas.</p>
                @endif

            </div>
        </div>
    </div>

    {{-- SCRIPT CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const predictedValues = @json($chartData ?? []); // Menggunakan null coalescing untuk keamanan
            
            const scatterData = predictedValues.map((value, index) => ({
                x: index + 1, // Urutan Data
                y: value     // Nilai Prediksi
            }));
            
            if (scatterData.length > 0) {
                const ctx = document.getElementById('regressionScatterPlot').getContext('2d');
                
                new Chart(ctx, {
                    type: 'scatter', 
                    data: {
                        datasets: [{
                            label: 'Nilai Prediksi',
                            data: scatterData,
                            backgroundColor: 'rgba(6, 182, 212, 0.8)', // Cyan 500/600
                            borderColor: 'rgba(6, 182, 212, 1)',
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointStyle: 'circle', // Ganti menjadi lingkaran solid
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                         plugins: {
                            legend: {
                                display: false,
                            },
                            title: {
                                display: true,
                                text: 'Distribusi Nilai Prediksi Terhadap Urutan Data',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                color: '#374151' 
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Nilai Prediksi: ${context.parsed.y.toFixed(2)}`;
                                    },
                                    title: function(context) {
                                        return `Data ke-${context[0].parsed.x}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                type: 'linear',
                                position: 'bottom',
                                title: {
                                    display: true,
                                    text: 'Urutan Data Terbaru (Index)', 
                                    font: { weight: 'bold' }
                                },
                                grid: {
                                    color: 'rgba(200, 200, 200, 0.4)'
                                }
                            },
                            y: {
                                beginAtZero: false,
                                title: {
                                    display: true,
                                    text: 'Nilai Prediksi',
                                    font: { weight: 'bold' }
                                },
                                min: 50, 
                                max: 100,
                                grid: {
                                    color: 'rgba(200, 200, 200, 0.4)'
                                }
                            }
                        },
                    }
                });
            } else {
                const chartContainer = document.getElementById('regressionScatterPlot');
                if (chartContainer) {
                     chartContainer.closest('.bg-gray-50').innerHTML = '<p class="text-center text-gray-500 italic py-4">Tidak ada data prediksi untuk visualisasi.</p>';
                }
            }
        });
    </script>
</x-app-layout>