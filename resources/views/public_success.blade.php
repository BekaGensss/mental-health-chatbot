<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Hasil Analisis</title>
    
    <link rel="icon" href="{{ asset('images/mts.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('images/mts.jpg') }}" type="image/jpeg">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />
    
    <style>
        
        body { 
            font-family: 'Inter', sans-serif; 
            background-image: url('{{ asset('images/bg_sekolah.png') }}');
            background-size: cover; 
            background-attachment: fixed; 
            background-position: center;
            background-color: #f3f4f6;
        }
        
        /* Glass Card umum (Hanya digunakan sebagai fallback/referensi) */
        .glass-card {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        }
        
        /* FOOTER CARD / CARD TRANSAPARAN (Diterapkan ke semua container besar) */
        .glass-footer-card {
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .score-box-modern {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: default;
        }
        .score-box-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.2);
        }

        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; background-image: none !important; }
            .glass-card { box-shadow: none !important; border: none !important; background-color: white !important; backdrop-filter: none !important; }
            .chart-container-box canvas { max-height: 250px !important; }
            .glass-footer-card { box-shadow: none !important; border: none !important; background-color: white !important; backdrop-filter: none !important; }
        }
        
        /* KRITIS: Batasan Tinggi Container Chart Donat */
        .chart-container-box {
            position: relative;
            height: 250px; 
            max-height: 250px;
            width: 100%;
        }

        /* PERBAIKAN KRITIS UNTUK IKON SVG */
        svg.mx-auto {
            width: 80px; 
            height: 80px; 
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

    </style>
</head>
<body class="bg-gray-100"> 
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        {{--- CARD UTAMA: PESAN SUKSES & RINGKASAN (Transparan, menggunakan glass-footer-card style) ---}}
        <div class="glass-footer-card p-8 sm:p-10 text-center border-t-8 border-cyan-500">
            
            {{--- Ikon Sukses (Ganti ke Bullseye/Target) ---}}
            <svg class="mx-auto text-cyan-600" style="width: 80px; height: 80px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764m-4.764 0L12 16.5m-2.764-6.5L4 16.5m4.764 0L12 10l-4.764 6.5z"></path>
            </svg>
            
            <h1 class="text-4xl font-extrabold text-gray-800 mt-4 mb-3 tracking-tight">Pengisian Berhasil!</h1>
            <p class="text-lg text-gray-800 mb-6 border-b pb-4">Data kuesioner Anda telah berhasil disimpan dan sedang diproses.</p>

            {{--- INFORMASI MURID UNTUK KONFIRMASI (Transparan Child) ---}}
            <div class="p-6 bg-transparent border-transparent rounded-xl mb-6 text-left shadow-none">
                <h2 class="text-xl font-bold text-cyan-700 mb-2 flex items-center">
                    <i class="fas fa-user-check mr-2"></i> Data Murid
                </h2>
                <p class="text-lg text-gray-800 font-semibold">
                    Nama: <span class="font-extrabold">{{ $studentName ?? 'Data Tidak Ditemukan' }}</span>
                </p>
                <p class="text-lg text-gray-800 font-semibold">
                    Kelas: <span class="font-extrabold">{{ $studentClass ?? 'N/A' }}</span>
                </p>
            </div>
            
            {{--- Ringkasan Hasil (Transparan Child) ---}}
            <div class="p-6 bg-transparent border-transparent rounded-xl mb-6 shadow-none">
                <h2 class="text-2xl font-bold text-blue-700 mb-3">Ringkasan Analisis Awal</h2>
                <p class="text-xl text-gray-800 font-extrabold leading-relaxed">{{ $resultSummary }}</p>
                <p class="text-sm text-gray-800 mt-3">Total Skor Keseluruhan: <span class="font-black text-blue-600">{{ $totalScore }}</span> / <span class="font-black text-gray-500">75</span></p>
            </div>
        </div>

        {{--- CARD ANALISIS VISUAL (Menggunakan glass-footer-card yang transparan) ---}}
        <div class="mt-8 glass-footer-card p-8 sm:p-10">
            <h3 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                {{-- Ganti icon Analisis Visual Skor ke Chart Pie --}}
                <i class="fas fa-chart-pie mr-3 text-cyan-500"></i> Analisis Visual Skor
            </h3>
            
            {{--- Box Skor Numerik (4 SKALA) ---}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                {{--- PHQ-9 (Depresi) - Red Accent ---}}
                <div class="p-4 border border-red-300 rounded-xl bg-red-50 text-center shadow-lg score-box-modern">
                    <p class="text-sm font-semibold text-red-700 mb-1 leading-tight">Depresi (PHQ-9) <span class="font-normal text-gray-500">(Max 15)</span></p>
                    <p class="text-4xl font-black text-red-800 mt-2 leading-none">{{ $phq9Score }}</p>
                    <p class="text-xs text-gray-500 mt-1">Risiko: <span class="font-black text-red-600">{{ number_format($phq9Percent, 1) ?? 0 }}%</span></p>
                </div>
                
                {{--- GAD-7 (Kecemasan) - Amber Accent ---}}
                <div class="p-4 border border-amber-300 rounded-xl bg-amber-50 text-center shadow-lg score-box-modern">
                    <p class="text-sm font-semibold text-amber-700 mb-1 leading-tight">Kecemasan (GAD-7) <span class="font-normal text-gray-500">(Max 15)</span></p>
                    <p class="text-4xl font-black text-amber-800 mt-2 leading-none">{{ $gad7Score }}</p>
                    <p class="text-xs text-gray-500 mt-1">Risiko: <span class="font-black text-amber-600">{{ number_format($gad7Percent, 1) ?? 0 }}%</span></p>
                </div>

                {{--- KPK (Perilaku Kesehatan) - Green Accent ---}}
                <div class="p-4 border border-green-300 rounded-xl bg-green-50 text-center shadow-lg score-box-modern">
                    <p class="text-sm font-semibold text-green-700 mb-1 leading-tight">KPK <span class="font-normal text-gray-500">(Max 15)</span></p>
                    <p class="text-4xl font-black text-green-800 mt-2 leading-none">{{ $kpkScore ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kualitas: <span class="font-black text-green-600">{{ number_format($kpkPercent ?? 0, 1) }}%</span></p>
                </div>
                
                {{--- DASS-21 (Depresi/Cemas/Stres) - Blue Accent ---}}
                <div class="p-4 border border-blue-300 rounded-xl bg-blue-50 text-center shadow-lg score-box-modern">
                    <p class="text-sm font-semibold text-blue-700 mb-1 leading-tight">DASS-21 <span class="font-normal text-gray-500">(Max 30)</span></p>
                    <p class="text-4xl font-black text-blue-800 mt-2 leading-none">{{ $dass21Score ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Risiko Total: <span class="font-black text-blue-600">{{ number_format($dass21Percent ?? 0, 1) }}%</span></p>
                </div>
            </div>

            {{--- Chart Donut (PHQ-9 & GAD-7) ---}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 min-h-96">
                
                {{-- Donut Chart 1 Container --}}
                <div class="p-4 border border-gray-200 rounded-xl shadow-lg bg-white flex flex-col justify-center items-center">
                    <h4 class="font-bold text-xl mb-6 text-gray-800 border-b pb-2 w-full text-center">Tingkat Risiko Depresi</h4>
                    <div class="chart-container-box">
                            <canvas id="phq9Chart"></canvas>
                    </div>
                </div>
                
                {{-- Donut Chart 2 Container --}}
                <div class="p-4 border border-gray-200 rounded-xl shadow-lg bg-white flex flex-col justify-center items-center">
                    <h4 class="font-bold text-xl mb-6 text-gray-800 border-b pb-2 w-full text-center">Tingkat Risiko Kecemasan</h4>
                    <div class="chart-container-box">
                            <canvas id="gad7Chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        {{--- FOOTER & AKSI ---}}
        <div class="mt-10 text-center no-print">
            
            {{-- TOMBOL EXPORT PDF DENGAN STUDENT ID (href diisi oleh JS) --}}
            <a id="export-pdf-button" 
                href="#" 
                class="px-8 py-4 bg-cyan-600 text-white font-extrabold rounded-xl hover:bg-cyan-700 transition duration-300 shadow-xl inline-block text-lg transform hover:scale-[1.02] mb-6">
                <i class="fas fa-file-pdf mr-3"></i> Export / Download Laporan PDF
            </a>
            
            {{--- CARD TRANSPARAN UNTUK PESAN TINDAK LANJUT ---}}
            <div class="glass-footer-card p-6 w-full max-w-lg mx-auto">
                <p class="text-base text-gray-800 font-semibold"> Terima Kasih Sudah Bersedia Mengisi Formulir. Anda dapat keluar dari halaman ini.</p>
                <a href="{{ url('/') }}" class="mt-4 inline-block text-cyan-800 hover:text-cyan-900 hover:underline font-extrabold transition text-lg">
                    &larr; Kembali ke Halaman Utama
                </a>
            </div>
        </div>
        
    </div>

    <script>
        // Data dari PHP Controller
        const phq9Percent = {{ $phq9Percent ?? 0 }};
        const gad7Percent = {{ $gad7Percent ?? 0 }};
        // Data Murid ID (FIXED: Menggunakan $studentId)
        const studentId = {{ $studentId ?? 0 }}; 

        // Fungsi untuk membuat diagram Donut
        function createDoughnutChart(ctxId, percent, title) {
            const riskColor = percent >= 50 ? 'rgba(239, 68, 68, 0.9)' : 'rgba(251, 191, 36, 0.9)';
            const data = {
                labels: ['Tingkat Risiko', 'Risiko Minimal'],
                datasets: [{
                    // Data disinkronkan dengan total 100%
                    data: [Math.min(100, percent).toFixed(1), Math.max(0, 100 - percent).toFixed(1)], 
                    backgroundColor: [riskColor, 'rgba(14, 165, 233, 0.5)'],
                    hoverBackgroundColor: ['#dc2626', '#0284c7'],
                    borderWidth: 0
                }]
            };

            new Chart(document.getElementById(ctxId), {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%', 
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true },
                        title: { display: false }
                    }
                }
            });

            // Teks Persentase di Tengah
            const canvasContainer = document.getElementById(ctxId).parentNode;
            
            // Hapus teks persentase lama jika ada
            let percentageText = canvasContainer.querySelector('.percentage-text');
            if (percentageText) {
                percentageText.remove();
            }

            percentageText = document.createElement('div');
            const textColor = percent >= 50 ? 'text-red-600' : 'text-amber-600';

            percentageText.textContent = `${percent.toFixed(1)}%`;
            percentageText.className = `absolute text-4xl font-extrabold ${textColor} top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 leading-none percentage-text`;
            
            // Menambahkan teks persentase ke parent container box (bukan parent canvas)
            const chartBox = document.getElementById(ctxId).closest('.chart-container-box');
            if (chartBox) {
                chartBox.style.position = 'relative'; 
                chartBox.appendChild(percentageText);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Kita sudah tahu Controller mengirim persentase yang benar (maks 100%)
            createDoughnutChart('phq9Chart', phq9Percent, 'Depresi');
            createDoughnutChart('gad7Chart', gad7Percent, 'Kecemasan');

            // --- LOGIC UTAMA UNTUK TOMBOL EXPORT PDF ---
            const pdfButton = document.getElementById('export-pdf-button');
            
            if (pdfButton && studentId > 0) {
                // URL FINAL: Menggunakan semua skor yang dikirim melalui Controller
                const baseUrl = `{{ route('form.export_pdf') }}?studentId=${studentId}&totalScore={{ $totalScore }}&phq9Score={{ $phq9Score }}&gad7Score={{ $gad7Score }}&kpkScore={{ $kpkScore }}&dass21Score={{ $dass21Score }}&summary={{ urlencode($resultSummary) }}`;
                pdfButton.setAttribute('href', baseUrl);
            } else {
                // Jika ID murid 0, tombol dinonaktifkan
                pdfButton.classList.add('opacity-50', 'cursor-not-allowed');
                pdfButton.onclick = function(e) { e.preventDefault(); alert('Error: ID Murid tidak ditemukan. Export dibatalkan.'); };
            }
        });
    </script>
</body>
</html>