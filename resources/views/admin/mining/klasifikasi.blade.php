<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            {{-- ICON BARU: Graph/Chart (Hitam/Gray 800) --}}
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.05v2m0 4v2m0 4v2m0 4v2m8.618-12.764L21 9.5M3 9.5l1.382.736M21 16.5l-1.382-.736M3 16.5l1.382.736M12 21a9 9 0 100-18 9 9 0 000 18z"></path></svg>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{-- Judul Hanya Klasifikasi (Prediksi Risiko) --}}
                <span class="text-cyan-600 font-extrabold">Klasifikasi (Prediksi Risiko)</span>
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
                
                <p class="mb-6 text-gray-500 border-b pb-4">Modul ini menjalankan algoritma klasifikasi (simulasi) untuk memprediksi tingkat risiko murid berdasarkan kombinasi skor PHQ-9, GAD-7, DASS-21, dan KPK.</p>
                
                {{-- Statistik Risiko dan Tombol Aksi (Dua Kolom di Desktop) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    {{-- Statistik Risiko --}}
                    <div class="md:col-span-2 p-5 rounded-xl shadow-lg border border-red-200/80 bg-red-50/50">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 block mb-1">Total Data Hasil Analisis: <span class="font-bold text-cyan-700">{{ $totalResults ?? 0 }}</span> murid</p>
                                <span class="text-5xl font-extrabold text-red-700 leading-none">{{ ($highRiskCount ?? 0) + ($mediumRiskCount ?? 0) }}</span>
                                <span class="text-xl font-bold text-red-600 ml-2">({{ $riskPercentage ?? 0 }}%)</span>
                                <p class="text-lg font-semibold text-gray-800 mt-2">Murid Terdeteksi Risiko Tinggi & Sedang</p>
                            </div>
                            
                            <div class="mt-4 sm:mt-0 sm:text-right">
                                <p class="text-sm font-semibold text-red-700">Detail Risiko:</p>
                                <p class="text-sm text-red-500">Risiko Tinggi: <span class="font-bold">{{ $highRiskCount ?? 0 }}</span></p>
                                <p class="text-sm text-yellow-600">Risiko Sedang: <span class="font-bold">{{ $mediumRiskCount ?? 0 }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="md:col-span-1 flex flex-col justify-center items-stretch p-5 bg-cyan-50/50 border border-cyan-200 rounded-xl shadow-lg">
                        <form method="POST" action="{{ route('admin.mining.klasifikasi.run') }}">
                            @csrf
                            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 shadow-lg shadow-cyan-500/50 transform hover:scale-[1.02] focus:ring-4 focus:ring-cyan-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Jalankan Ulang Klasifikasi
                                </span>
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 text-center mt-3">Proses ini akan memperbarui data prediksi terbaru.</p>
                    </div>

                </div>

                {{-- Visualisasi Data --}}
                <h4 class="font-extrabold text-lg text-gray-800 mt-6 mb-4 pt-4 border-t border-gray-100">Distribusi Tingkat Risiko (Visualisasi)</h4>
                <div class="bg-gray-50 border border-gray-200/80 p-6 rounded-xl shadow-inner mb-8">
                    <div class="w-full md:w-4/5 lg:w-3/4 mx-auto"> 
                        <canvas id="riskBarChart"></canvas>
                    </div>
                </div>

                {{-- Tabel Data --}}
                <h4 class="font-extrabold text-lg text-gray-800 mt-6 mb-4">Daftar Hasil Klasifikasi Terbaru</h4>
                
                {{-- BAGIAN PENCARIAN & FILTER --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-gray-600">Filter Risiko:</span>
                        {{-- Dropdown Filter Risiko --}}
                        <select onchange="window.location.href=this.value" class="border-gray-300 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 shadow-sm">
                            <option value="{{ route('admin.mining.klasifikasi', ['search' => $search ?? null]) }}" @if(!($filterRisk ?? null)) selected @endif>Semua Risiko</option>
                            <option value="{{ route('admin.mining.klasifikasi', ['filter_risk' => 'Risiko Tinggi', 'search' => $search ?? null]) }}" @if(($filterRisk ?? null) === 'Risiko Tinggi') selected @endif>Risiko Tinggi</option>
                            <option value="{{ route('admin.mining.klasifikasi', ['filter_risk' => 'Risiko Sedang', 'search' => $search ?? null]) }}" @if(($filterRisk ?? null) === 'Risiko Sedang') selected @endif>Risiko Sedang</option>
                            <option value="{{ route('admin.mining.klasifikasi', ['filter_risk' => 'Risiko Rendah', 'search' => $search ?? null]) }}" @if(($filterRisk ?? null) === 'Risiko Rendah') selected @endif>Risiko Rendah</option>
                        </select>
                    </div>

                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ route('admin.mining.klasifikasi') }}" class="flex items-center space-x-2 w-full md:w-auto">
                        <input type="hidden" name="filter_risk" value="{{ $filterRisk ?? null }}">
                        <input type="text" name="search" placeholder="Cari Nama Murid..." 
                            value="{{ $search ?? '' }}"
                            class="border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 rounded-lg shadow-sm w-full">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-150">
                            Cari
                        </button>
                        @if(($search ?? null) || ($filterRisk ?? null))
                            <a href="{{ route('admin.mining.klasifikasi') }}" class="text-sm text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50">Reset</a>
                        @endif
                    </form>
                </div>
                
                {{-- Tabel Hasil Klasifikasi --}}
                @if($results->isNotEmpty())
                    <div class="overflow-x-auto border rounded-xl shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-cyan-600/10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Murid (Kelas)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">4 Skor Input</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Probabilitas Risiko</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-cyan-800 uppercase tracking-wider">Label Prediksi</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">
                                        <span class="font-bold">{{ number_format($result->risk_probability * 100, 2) }}%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold">
                                        @php
                                            $label = $result->predicted_label;
                                            $colorClass = match ($label) {
                                                'Risiko Tinggi' => 'bg-red-50 text-red-700 border border-red-300',
                                                'Risiko Sedang' => 'bg-yellow-50 text-yellow-700 border border-yellow-300',
                                                default => 'bg-emerald-50 text-emerald-700 border border-emerald-300',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-extrabold rounded-full border {{ $colorClass }} shadow-sm">
                                            {{ $label }}
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
                    <p class="text-gray-500 italic p-4 border rounded-lg bg-gray-50">Belum ada hasil Klasifikasi yang tersimpan atau tidak ditemukan data dengan kriteria pencarian/filter tersebut. Silakan Jalankan Ulang Klasifikasi di bagian atas.</p>
                @endif
                
                <hr class="my-6 border-gray-200">

                {{-- KESIMPULAN DAN REKOMENDASI --}}
                <div class="pt-6">
                    <h4 class="font-extrabold text-lg text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Kesimpulan & Rekomendasi Tindak Lanjut
                    </h4>
                    
                    <div class="space-y-4">
                        
                        {{-- Risiko Tinggi --}}
                        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
                            <p class="font-bold text-red-700 mb-1">Risiko Tinggi</p>
                            <p class="text-gray-700 text-sm">
                                Murid dalam kategori ini menunjukkan skor yang sangat tinggi pada beberapa indikator kesehatan mental, menandakan adanya kemungkinan gangguan serius atau krisis.
                            </p>
                            <p class="mt-2 text-sm">
                                Tindakan: Segera lakukan konsultasi mendalam (tatap muka) dengan Psikolog Klinis, Konselor berpengalaman, atau pihak profesional eksternal yang memiliki kualifikasi tinggi. Prioritaskan tindak lanjut dalam waktu 1-3 hari kerja.
                            </p>
                        </div>

                        {{-- Risiko Sedang --}}
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg shadow-sm">
                            <p class="font-bold text-yellow-700 mb-1">Risiko Sedang</p>
                            <p class="text-gray-700 text-sm">
                                Murid menunjukkan adanya gejala signifikan yang mulai mengganggu fungsi sehari-hari. Risiko ini perlu perhatian dan intervensi cepat.
                            </p>
                            <p class="mt-2 text-sm">
                                Tindakan: Lakukan wawancara konseling terstruktur oleh Guru Bimbingan Konseling (BK) sekolah. Susun rencana intervensi berbasis sekolah (seperti program mentoring, mindfulness) dan pantau perkembangan skor dalam 2 minggu.
                            </p>
                        </div>

                        {{-- Risiko Rendah --}}
                        <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-lg shadow-sm">
                            <p class="font-bold text-emerald-700 mb-1">Risiko Rendah</p>
                            <p class="text-gray-700 text-sm">
                                Murid berada dalam kondisi psikologis yang stabil dan sehat. Gejala yang ada bersifat ringan atau tidak ada.
                            </p>
                            <p class="mt-2 text-sm">
                                Tindakan: Lanjutkan pemantauan rutin melalui program kesehatan mental sekolah umum (misalnya edukasi, kegiatan positif). Intervensi individual tidak diperlukan saat ini.
                            </p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    
    {{-- SCRIPT CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Menggunakan operator null coalescing untuk menghindari error jika $riskSummary null
            const riskSummary = @json($riskSummary ?? ['labels' => [], 'data' => []]); 

            if (riskSummary.data.reduce((a, b) => a + b, 0) > 0) {
                const ctx = document.getElementById('riskBarChart').getContext('2d');
                
                // Warna profesional yang konsisten dengan tema
                const colors = [
                    'rgba(220, 38, 38, 0.9)',      // Red-700 (Risiko Tinggi)
                    'rgba(245, 158, 11, 0.9)',     // Amber-600 (Risiko Sedang)
                    'rgba(16, 185, 129, 0.9)',     // Emerald-600 (Risiko Rendah)
                ];
                const borders = [
                    'rgba(220, 38, 38, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(16, 185, 129, 1)',
                ];

                new Chart(ctx, {
                    type: 'bar', 
                    data: {
                        labels: riskSummary.labels,
                        datasets: [{
                            label: 'Jumlah Murid',
                            data: riskSummary.data,
                            backgroundColor: colors,
                            borderColor: borders,
                            borderWidth: 1.5,
                            borderRadius: 8, // Sudut bar yang lebih membulat
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            x: {
                                grid: {
                                    display: false 
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Murid',
                                    font: { weight: 'bold' }
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (value % 1 === 0) {
                                            return value;
                                        }
                                    }
                                },
                                grid: {
                                    color: 'rgba(200, 200, 200, 0.4)' 
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                            title: {
                                display: true,
                                text: 'Perbandingan Jumlah Murid Berdasarkan Tingkat Risiko',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                color: '#374151' 
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += context.parsed.y + ' Murid';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                const chartContainer = document.getElementById('riskBarChart');
                if (chartContainer) {
                    chartContainer.style.display = 'none';
                    const chartArea = chartContainer.closest('.bg-gray-50');
                    if(chartArea) {
                        chartArea.innerHTML = '<p class="text-center text-gray-500 italic py-4">Tidak ada data hasil analisis untuk divisualisasikan.</p>';
                    }
                }
            }
        });
    </script>
</x-app-layout>