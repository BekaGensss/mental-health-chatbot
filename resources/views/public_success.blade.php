<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Hasil Analisis Pintar</title>
    
    <link rel="icon" href="{{ asset('images/mts.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('images/mts.jpg') }}" type="image/jpeg">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            /* MENGGUNAKAN WARNA ASLI BACKGROUND */
            background-image: url('{{ asset('images/bg_sekolah.png') }}');
            background-size: cover; 
            background-attachment: fixed; 
            background-position: center; 
            min-height: 100vh;
        }
        /* Glass Card diperhalus */
        .glass-card { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(15px); 
            border: 1px solid rgba(255, 255, 255, 0.6); 
            border-radius: 1.5rem; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); 
        }
        @media (min-width: 640px) {
            .glass-card { border-radius: 2.5rem; }
        }

        .chart-wrapper { position: relative; height: 160px; width: 100%; display: flex; align-items: center; justify-content: center; }
        .chart-center-text { position: absolute; font-weight: 800; text-align: center; pointer-events: none; }
        
        #map { height: 300px; width: 100%; border-radius: 1rem; border: 4px solid white; z-index: 10; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        @media (min-width: 640px) {
            #map { height: 400px; border-radius: 1.5rem; }
        }
        
        .card-dark { 
            background: rgba(15, 23, 42, 0.95); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .card-dark:hover { transform: translateY(-5px); background: #0f172a; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
        
        /* CSS Accordion */
        .collapsible-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-out, opacity 0.3s ease;
            opacity: 0;
        }
        .collapsible-content.active {
            max-height: 5000px; 
            opacity: 1;
            transition: max-height 0.8s ease-in, opacity 0.5s ease;
        }
        .rotate-icon { transition: transform 0.3s ease; }
        .rotate-icon.active { transform: rotate(180deg); }

        .markdown-content strong { color: #0891b2; font-weight: 800; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="antialiased text-slate-800">

    <div class="max-w-6xl mx-auto py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
        
        {{-- SECTION 1: SKOR --}}
        <div class="glass-card p-6 sm:p-12 mb-8 border-t-8 border-cyan-500 relative overflow-hidden">
            @php
                $maxPHQ = 15; $maxGAD = 15; $maxKPK = 15; $maxDASS = 60;
                $phqPercent = ($phq9Score > 0) ? ($phq9Score / $maxPHQ) * 100 : 0;
                $gadPercent = ($gad7Score > 0) ? ($gad7Score / $maxGAD) * 100 : 0;
                $kpkPercent = ($kpkScore > 0) ? ($kpkScore / $maxKPK) * 100 : 0;
                $dassPercent = ($dass21Score > 0) ? (($dass21Score * 2) / $maxDASS) * 100 : 0;
                $totalSkor = ($phq9Score ?? 0) + ($gad7Score ?? 0) + ($kpkScore ?? 0) + ($dass21Score ?? 0);
            @endphp

            <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-10 relative z-10">
                <div class="text-center md:text-left">
                    <h1 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-2">Laporan Hasil</h1>
                    <p class="text-slate-600 font-bold text-base sm:text-lg">Analisis rincian kesehatan mental Anda.</p>
                </div>
                <div class="bg-gradient-to-br from-cyan-600 to-cyan-800 text-white p-6 sm:p-10 rounded-2xl sm:rounded-[3rem] shadow-xl text-center min-w-full sm:min-w-[260px]">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-1 opacity-80">Total Skor Klinis</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-6xl sm:text-8xl font-black leading-none">{{ $totalSkor }}</span>
                        <span class="text-xl sm:text-2xl font-bold opacity-40">/ 75</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
                <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border-b-4 border-red-500 text-center">
                    <p class="text-[9px] sm:text-[10px] font-black text-red-500 uppercase mb-2">Depresi</p>
                    <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-1">
                        <span class="text-2xl sm:text-4xl font-black">{{ $phq9Score ?? 0 }}</span>
                        <span class="text-[10px] font-bold px-2 py-1 bg-red-100 text-red-600 rounded-lg">{{ number_format($phqPercent, 0) }}%</span>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border-b-4 border-amber-500 text-center">
                    <p class="text-[9px] sm:text-[10px] font-black text-amber-500 uppercase mb-2">Kecemasan</p>
                    <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-1">
                        <span class="text-2xl sm:text-4xl font-black">{{ $gad7Score ?? 0 }}</span>
                        <span class="text-[10px] font-bold px-2 py-1 bg-amber-100 text-amber-600 rounded-lg">{{ number_format($gadPercent, 0) }}%</span>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border-b-4 border-emerald-500 text-center">
                    <p class="text-[9px] sm:text-[10px] font-black text-emerald-500 uppercase mb-2">Fisik</p>
                    <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-1">
                        <span class="text-2xl sm:text-4xl font-black">{{ $kpkScore ?? 0 }}</span>
                        <span class="text-[10px] font-bold px-2 py-1 bg-emerald-100 text-emerald-600 rounded-lg">{{ number_format($kpkPercent, 0) }}%</span>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border-b-4 border-blue-500 text-center">
                    <p class="text-[9px] sm:text-[10px] font-black text-blue-500 uppercase mb-2">Stres</p>
                    <div class="flex flex-col sm:flex-row items-center sm:justify-between gap-1">
                        <span class="text-2xl sm:text-4xl font-black">{{ $dass21Score ?? 0 }}</span>
                        <span class="text-[10px] font-bold px-2 py-1 bg-blue-100 text-blue-600 rounded-lg">{{ number_format($dassPercent, 0) }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: AI --}}
        <div class="glass-card p-6 sm:p-12 mb-8 border-l-[8px] sm:border-l-[12px] border-cyan-600">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mb-6 flex items-center gap-3 sm:gap-4">
                <div class="p-2 sm:p-3 bg-cyan-600 rounded-xl text-white shadow-lg"><i class="fas fa-robot"></i></div>
                Analisis Gen-Z bot AI
            </h2>
            <div id="ai-analysis-render" class="markdown-content text-base sm:text-xl text-slate-700 bg-white/60 p-4 sm:p-8 rounded-xl sm:rounded-[2rem] border border-white mb-8 shadow-inner italic leading-relaxed">
                {{ $aiAnalysis ?? 'Menganalisis data...' }}
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full {{ ($mentalStatus ?? '') == 'Bahaya' ? 'bg-red-500 animate-ping' : 'bg-emerald-500' }}"></div>
                    <p class="text-2xl sm:text-3xl font-black {{ ($mentalStatus ?? '') == 'Bahaya' ? 'text-red-600' : 'text-emerald-600' }}">
                        {{ $mentalStatus ?? 'Teranalisis' }}
                    </p>
                </div>
                <div class="no-print w-full sm:w-auto text-center">
                    <a id="export-pdf-button" href="#" class="inline-flex justify-center items-center gap-3 bg-slate-900 hover:bg-black text-white px-6 py-4 rounded-xl font-black text-sm shadow-xl transition-all">
                        <i class="fas fa-file-pdf text-lg"></i> UNDUH LAPORAN PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- SECTION 3: MAP ACCORDION --}}
        <div class="glass-card no-print overflow-hidden mb-8">
            <button onclick="toggleAccordion('map-accordion')" class="w-full p-6 sm:p-8 flex items-center justify-between hover:bg-white/50 transition-all group">
                <div class="flex items-center gap-3 sm:gap-4 text-left">
                    <div class="p-2 sm:p-3 bg-slate-900 rounded-xl text-white shadow-lg"><i class="fas fa-hospital-user"></i></div>
                    <div>
                        <h3 class="text-lg sm:text-2xl font-black text-slate-900 leading-tight">Bantuan Profesional</h3>
                        <p class="text-slate-500 font-bold text-[10px] sm:text-sm uppercase tracking-wider">Klik untuk lihat lokasi bantuan</p>
                    </div>
                </div>
                <i id="accordion-icon" class="fas fa-chevron-down text-xl text-slate-400 rotate-icon"></i>
            </button>

            <div id="map-accordion" class="collapsible-content">
                <div class="p-4 sm:p-8 pt-0">
                    <div id="map" class="mb-6 sm:mb-10"></div>
                    <div id="psychologist-list" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 text-center">
                        <div class="col-span-full py-10">
                            <i class="fas fa-spinner fa-spin fa-2x text-cyan-500 mb-4"></i>
                            <p class="font-bold text-slate-400 text-sm">Mendeteksi bantuan terdekat...</p>
                        </div>
                    </div>
                    <div id="load-more-container" class="mt-8 text-center hidden">
                        <button id="load-more-btn" class="w-full sm:w-auto px-10 py-4 bg-white border-2 border-slate-200 text-slate-800 rounded-2xl font-black hover:bg-slate-900 hover:text-white transition-all shadow-md text-xs tracking-widest uppercase">
                            MUAT LEBIH BANYAK <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- BACK HOME CARD --}}
        <div class="glass-card p-4 sm:p-6 no-print flex justify-center items-center max-w-sm mx-auto border-2 border-white">
            <a href="{{ url('/') }}" class="w-full text-center inline-flex items-center justify-center gap-3 bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-4 rounded-xl font-bold transition-all shadow-lg active:scale-95">
                <i class="fas fa-home"></i> KEMBALI KE BERANDA
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function toggleAccordion(id) {
            const content = document.getElementById(id);
            const icon = document.getElementById('accordion-icon');
            content.classList.toggle('active');
            icon.classList.toggle('active');
            if(content.classList.contains('active')) {
                setTimeout(() => map.invalidateSize(), 400);
            }
        }

        const studentId = {{ $studentId ?? 0 }};
        let allPlaces = [];
        let displayedCount = 0;
        let map;

        function renderPlaces() {
            const list = document.getElementById('psychologist-list');
            const limit = 10;
            const nextBatch = allPlaces.slice(displayedCount, displayedCount + limit);
            if (displayedCount === 0) list.innerHTML = '';

            nextBatch.forEach(p => {
                list.innerHTML += `
                    <div class="card-dark p-6 sm:p-8 rounded-2xl sm:rounded-[2.5rem] flex flex-col justify-between shadow-xl border border-white/5 text-left">
                        <div>
                            <span class="text-[9px] font-black text-cyan-400 bg-cyan-400/10 px-3 py-1 rounded-full uppercase tracking-widest mb-4 inline-block">Fasilitas Kesehatan</span>
                            <h4 class="font-bold text-white text-lg sm:text-2xl mb-2 leading-tight">${p.name}</h4>
                            <p class="text-xs sm:text-sm text-slate-400 mb-6 line-clamp-2 italic opacity-80 leading-relaxed">${p.vicinity}</p>
                        </div>
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${p.geometry.location.lat},${p.geometry.location.lng}" target="_blank" 
                           class="w-full text-center py-4 bg-cyan-600 hover:bg-cyan-500 text-white text-[10px] sm:text-[11px] font-black rounded-xl transition-all shadow-lg uppercase tracking-widest">
                           <i class="fas fa-location-arrow mr-2"></i> BUKA RUTE NAVIGASI
                        </a>
                    </div>`;
            });
            displayedCount += nextBatch.length;
            document.getElementById('load-more-container').classList.toggle('hidden', displayedCount >= allPlaces.length);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const aiContent = document.getElementById('ai-analysis-render');
            if(aiContent) aiContent.innerHTML = marked.parse(aiContent.innerText);

            const pdfBtn = document.getElementById('export-pdf-button');
            if (pdfBtn && studentId > 0) {
                pdfBtn.setAttribute('href', `{{ route('form.export_pdf') }}?studentId=${studentId}&totalScore={{ $totalSkor }}&summary={{ urlencode($resultSummary ?? '') }}`);
            }

            map = L.map('map', { zoomControl: false }).setView([-6.2, 106.8], 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    const { latitude, longitude } = pos.coords;
                    map.setView([latitude, longitude], 14);
                    L.marker([latitude, longitude]).addTo(map).bindPopup('<b>Posisi Anda</b>').openPopup();

                    fetch(`/api/nearby-psychologists?lat=${latitude}&lng=${longitude}`)
                        .then(r => r.json())
                        .then(data => {
                            allPlaces = data;
                            allPlaces.forEach(p => {
                                if(p.geometry) L.marker([p.geometry.location.lat, p.geometry.location.lng]).addTo(map).bindPopup(`<b>${p.name}</b>`);
                            });
                            renderPlaces();
                        });
                });
            }
            document.getElementById('load-more-btn').addEventListener('click', renderPlaces);
        });
    </script>
</body>
</html>