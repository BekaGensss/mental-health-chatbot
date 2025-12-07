<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            {{-- ICON BARU: Ikon Users/Kelompok (Hitam/Gray 800) --}}
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{-- Hanya menyisakan Clustering (Pengelompokan Profil) --}}
                <span class="text-cyan-600 font-extrabold">Clustering (Pengelompokan Profil)</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Sukses - Diubah ke Palet Emerald/Cyan yang Konsisten --}}
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
                
                <p class="mb-6 text-gray-500 border-b pb-4">Modul ini menjalankan simulasi Clustering K-Means untuk mengelompokkan murid berdasarkan pola skor mereka (PHQ-9, GAD-7, DASS-21, KPK) ke dalam beberapa profil yang berbeda.</p>
                
                {{-- Ringkasan dan Tombol Proses --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    {{-- Ringkasan Cluster --}}
                    <div class="md:col-span-2 p-5 rounded-xl shadow-lg border border-cyan-200/80 bg-cyan-50/50">
                        <span class="text-sm font-semibold text-gray-600 block mb-2">Total Data Murid yang Dianalisis: <span class="font-bold text-cyan-700">{{ $totalResults ?? 0 }}</span></span>
                        <h4 class="font-bold text-xl text-gray-800 mt-2 mb-4">Ringkasan Kelompok (Clusters) Terakhir</h4>
                        
                        <div class="space-y-3">
                            @forelse ($summary as $cluster)
                                @php
                                    // Mapping warna berdasarkan ID kluster (sesuai logika di Controller: 3=High, 2=Moderate, 1=Low)
                                    $colorClass = match ($cluster->cluster_id) {
                                        3 => 'text-red-700',
                                        2 => 'text-orange-600',
                                        default => 'text-emerald-600',
                                    };
                                @endphp
                                <div class="p-3 border-l-4 border-gray-200 bg-white rounded-lg shadow-sm">
                                    <p class="text-md text-gray-700 flex items-baseline">
                                        <span class="font-extrabold text-3xl mr-3 {{ $colorClass }} leading-none">{{ $cluster->count }}</span>
                                        Murid dikelompokkan sebagai 
                                        <span class="font-extrabold {{ $colorClass }} ml-1">{{ $cluster->cluster_label }}</span>
                                    </p>
                                    <span class="text-xs text-gray-500 block ml-10">
                                        (Rata-rata PHQ: {{ round($cluster->avg_phq9, 1) }}, Rata-rata GAD: {{ round($cluster->avg_gad7, 1) }})
                                    </span>
                                </div>
                            @empty
                                <p class="text-gray-500 italic text-sm">Belum ada data cluster yang diproses. Silakan jalankan proses Clustering di bawah.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tombol Aksi (Warna Cyan) --}}
                    <div class="md:col-span-1 flex flex-col justify-center items-stretch p-5 bg-cyan-50/50 border border-cyan-200 rounded-xl shadow-lg">
                        <form method="POST" action="{{ route('admin.mining.clustering.run') }}">
                            @csrf
                            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-6 rounded-xl transition duration-300 shadow-lg shadow-cyan-500/50 transform hover:scale-[1.02] focus:ring-4 focus:ring-cyan-300">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Jalankan Ulang Clustering
                                </span>
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 text-center mt-3">Proses ini akan mengelompokkan ulang semua data murid.</p>
                    </div>

                </div>

                <hr class="my-6 border-gray-200">

                {{-- BAGIAN VISUALISASI (SCATTER PLOT) --}}
                <div class="mt-8 mb-8">
                    <h4 class="font-extrabold text-lg text-gray-800 mb-4">Visualisasi Pengelompokan (PHQ vs GAD)</h4>
                    <div class="w-full mx-auto p-4 border rounded-xl bg-gray-50 shadow-inner">
                        <canvas id="clusteringScatterPlot"></canvas>
                    </div>
                </div>
                
                <h4 class="font-extrabold text-lg mt-10 mb-4 text-gray-800">Daftar Anggota Kelompok Terbaru</h4>
                
                {{-- BAGIAN PENCARIAN & FILTER --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-gray-600">Filter Kelompok:</span>
                        {{-- Dropdown Filter Cluster (Diasumsikan $filterCluster ada di Controller) --}}
                        <select onchange="window.location.href=this.value" class="border-gray-300 rounded-lg text-sm focus:border-cyan-500 focus:ring-cyan-500 shadow-sm">
                            <option value="{{ route('admin.mining.clustering', ['search' => $search ?? null]) }}" @if(!($filterCluster ?? null)) selected @endif>Semua Kelompok</option>
                            @if(isset($summary))
                                @foreach($summary as $cluster)
                                    <option value="{{ route('admin.mining.clustering', ['filter_cluster' => $cluster->cluster_label, 'search' => $search ?? null]) }}" @if(($filterCluster ?? null) === $cluster->cluster_label) selected @endif>{{ $cluster->cluster_label }} ({{ $cluster->count }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Form Pencarian --}}
                    <form method="GET" action="{{ route('admin.mining.clustering') }}" class="flex items-center space-x-2 w-full md:w-auto">
                        <input type="hidden" name="filter_cluster" value="{{ $filterCluster ?? null }}">
                        <input type="text" name="search" placeholder="Cari Nama Murid..." 
                            value="{{ $search ?? '' }}"
                            class="border-gray-300 focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 rounded-lg shadow-sm w-full">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-150">
                            Cari
                        </button>
                        @if(($search ?? null) || ($filterCluster ?? null))
                            <a href="{{ route('admin.mining.clustering') }}" class="text-sm text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50">Reset</a>
                        @endif
                    </form>
                </div>
                
                {{-- Tabel Hasil Clustering --}}
                @if($results->isNotEmpty())
                    <div class="overflow-x-auto border rounded-xl shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-cyan-600/10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Murid (Kelas)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Skor PHQ & GAD</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Kelompok Hasil (ID)</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Jarak ke Centroid</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-cyan-800 uppercase tracking-wider">Terakhir Dianalisis</th>
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
                                        PHQ: <span class="font-bold text-cyan-600">{{ $result->phq9_score }}</span> | GAD: <span class="font-bold text-cyan-600">{{ $result->gad7_score }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                        @php
                                            $label = $result->cluster_label;
                                            $colorClass = match ($result->cluster_id) {
                                                3 => 'bg-red-50 text-red-700 border border-red-300',
                                                2 => 'bg-orange-50 text-orange-700 border border-orange-300',
                                                default => 'bg-emerald-50 text-emerald-700 border border-emerald-300',
                                            };
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-extrabold rounded-full border {{ $colorClass }} shadow-sm">
                                            {{ $label }} (ID: {{ $result->cluster_id }})
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">
                                        {{ number_format($result->distance_to_centroid, 4) }}
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
                    <p class="text-gray-500 italic p-4 border rounded-lg bg-gray-50">Belum ada hasil Clustering yang tersimpan. Silakan jalankan proses Clustering menggunakan tombol di atas.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- SCRIPT CHART.JS (SCATTER PLOT) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const results = @json($results->items() ?? []); // Ambil data hasil yang sudah difilter/dipaginasi
            
            // 1. Kelompokkan data per Cluster ID
            const groupedData = results.reduce((acc, item) => {
                const studentName = item.student_data ? item.student_data.name : 'Unknown';
                
                if (!acc[item.cluster_id]) {
                    acc[item.cluster_id] = {
                        label: item.cluster_label,
                        data: [],
                    };
                }
                acc[item.cluster_id].data.push({ 
                    x: item.phq9_score, 
                    y: item.gad7_score, 
                    name: studentName // Nama Murid (untuk Tooltip)
                });
                return acc;
            }, {});

            // 2. Tentukan Warna
            const clusterColors = {
                1: { color: 'rgba(16, 185, 129, 0.8)', label: 'Profil Sehat' }, 
                2: { color: 'rgba(245, 158, 11, 0.8)', label: 'Profil Perlu Perhatian' }, 
                3: { color: 'rgba(239, 68, 68, 0.8)', label: 'Profil Risiko Tinggi (Rentan)' }, 
            };

            // 3. Buat Dataset Chart.js
            const datasets = Object.keys(groupedData).sort((a, b) => a - b).map(clusterId => {
                const colorInfo = clusterColors[clusterId] || clusterColors[1];
                return {
                    label: groupedData[clusterId].label,
                    data: groupedData[clusterId].data,
                    backgroundColor: colorInfo.color,
                    borderColor: colorInfo.color.replace('0.8', '1'),
                    pointRadius: 6,
                    pointHoverRadius: 9,
                };
            });
            
            if (datasets.length > 0) {
                const ctx = document.getElementById('clusteringScatterPlot').getContext('2d');
                
                new Chart(ctx, {
                    type: 'scatter',
                    data: {
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'PHQ-9 Score (Depresi)',
                                    font: { size: 14, weight: 'bold' }
                                },
                                max: 27, 
                                grid: { color: 'rgba(200, 200, 200, 0.3)' }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'GAD-7 Score (Kecemasan)',
                                    font: { size: 14, weight: 'bold' }
                                },
                                max: 21, 
                                grid: { color: 'rgba(200, 200, 200, 0.3)' }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Visualisasi K-Means Clustering: PHQ-9 vs GAD-7',
                                font: { size: 16, weight: 'bold' },
                                color: '#374151' 
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const point = context.dataset.data[context.dataIndex];
                                        return `${point.name} (PHQ: ${point.x}, GAD: ${point.y})`;
                                    },
                                    title: function(context) {
                                        return context[0].dataset.label;
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                const chartContainer = document.getElementById('clusteringScatterPlot').closest('.bg-gray-50');
                if (chartContainer) {
                    chartContainer.innerHTML = `<p class="text-center text-gray-500 italic py-4">
                        Tidak ada data hasil clustering untuk divisualisasikan. Silakan jalankan proses clustering.
                        </p>`;
                }
            }
        });
    </script>
</x-app-layout>