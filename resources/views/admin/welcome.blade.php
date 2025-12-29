<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting->title }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        :root { scroll-behavior: smooth; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: url('{{ asset('images/bg_sekolah.png') }}');
            background-size: cover; 
            background-attachment: fixed; 
            background-position: center; 
            min-height: 100vh;
            color: #0f172a;
        }
        .bg-dots {
            position: fixed; inset: 0; z-index: -1;
            background-image: radial-gradient(#4f46e5 0.5px, transparent 0.5px);
            background-size: 32px 32px; opacity: 0.05;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95); /* Opacity tinggi agar teks terbaca */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }
        .step-number {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white; width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px; font-weight: 800;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        
        .text-shadow-strong {
            text-shadow: 0 2px 4px rgba(255,255,255,0.9), 0 1px 2px rgba(0,0,0,0.1);
        }

        .theory-card { border-left: 6px solid transparent; }
        .theory-phq { border-left-color: #ef4444; }
        .theory-gad { border-left-color: #f59e0b; }
        .theory-dass { border-left-color: #a855f7; }
        .theory-kpk { border-left-color: #10b981; }

        .tech-tag {
            background: #ffffff;
            border: 2px solid #334155;
            padding: 6px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 900;
            color: #0f172a;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="antialiased relative">

    <div class="bg-dots"></div>
    
    <nav class="sticky top-0 w-full z-50 px-4 sm:px-6 py-4 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-2.5 h-8 bg-indigo-600 rounded-full"></div>
                <span class="font-extrabold text-sm tracking-tight uppercase text-slate-900">Portal — UNM & MTS</span>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <a href="#panduan" class="hidden md:block text-slate-600 hover:text-indigo-600 text-[11px] font-bold uppercase tracking-widest transition-colors mr-2">Panduan</a>
                <a href="{{ route('form.show') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 sm:px-5 py-2.5 rounded-xl text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest shadow-lg shadow-indigo-100 transition-all active:scale-95 leading-none">
                    Mulai Tes
                </a>
                <a href="{{ route('login') }}" class="bg-white text-slate-700 border border-slate-200 px-4 sm:px-5 py-2.5 rounded-xl text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest hover:bg-slate-50 transition-all leading-none shadow-sm text-center">
                    Login
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <section class="pt-16 pb-20 text-center">
            <div class="flex flex-col items-center justify-center mb-10">
                <div class="flex items-center space-x-6 mb-8">
                    <img src="{{ asset('images/mts.jpg') }}" alt="Logo MTS" class="h-16 sm:h-20 w-auto rounded-lg shadow-md border-2 border-white">
                    <div class="h-10 w-px bg-slate-400 rotate-12"></div>
                    <img src="{{ asset('images/unm.png') }}" alt="Logo UNM" class="h-16 sm:h-20 w-auto drop-shadow-sm">
                </div>
                <div class="max-w-4xl">
                    <h1 class="text-3xl md:text-6xl font-black text-slate-900 tracking-tight mb-6 text-shadow-strong leading-tight">
                        Implementasi Web Skrining <br>
                        <span class="text-indigo-700">Kesehatan Mental</span>
                    </h1>
                    
                    <div class="inline-block p-1 px-4 rounded-full bg-indigo-600 mb-8 shadow-md border border-indigo-500">
                        <span class="text-white font-black text-xs uppercase tracking-widest">Penelitian Kolaborasi Siswa</span>
                    </div>
                </div>

                <div class="glass-card p-6 sm:p-12 bg-indigo-700 text-white border-none shadow-2xl max-w-3xl mx-auto" style="background: rgba(67, 56, 202, 0.98);">
                    <p class="text-base md:text-xl font-bold leading-relaxed italic">
                        "Kolaborasi ini merupakan implementasi sistem berbasis web untuk deteksi dini kesehatan mental siswa sebagai upaya mitigasi gangguan psikologis di lingkungan sekolah MTS AL-WAHAB."
                    </p>
                </div>
            </div>
        </section>

        <section class="mb-24">
            <div class="flex flex-col items-center mb-12">
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4 text-center uppercase text-shadow-strong">Landasan Teori & Metodologi</h2>
                <div class="h-2 w-16 bg-amber-500 rounded-full shadow-sm"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass-card p-6 sm:p-8 theory-card theory-phq">
                    <h4 class="font-black text-red-600 uppercase text-xs sm:text-sm tracking-tight mb-2">01. PHQ-9 (Kuesioner Kesehatan Pasien)</h4>
                    <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-bold">Skrining objektif tingkat keparahan gejala depresi berdasarkan kriteria klinis 14 hari terakhir.</p>
                </div>
                <div class="glass-card p-6 sm:p-8 theory-card theory-gad">
                    <h4 class="font-black text-amber-600 uppercase text-xs sm:text-sm tracking-tight mb-2">02. GAD-7 (Skala Kecemasan Umum)</h4>
                    <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-bold">Alat ukur validitas medis untuk mengidentifikasi gangguan kecemasan umum dan respon emosional.</p>
                </div>
                <div class="glass-card p-6 sm:p-8 theory-card theory-dass">
                    <h4 class="font-black text-purple-600 uppercase text-xs sm:text-sm tracking-tight mb-2">03. DASS-21 (Depresi, Cemas, & Stres)</h4>
                    <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-bold">Mengukur tiga kondisi emosional negatif secara bersamaan untuk gambaran psikologis komprehensif.</p>
                </div>
                <div class="glass-card p-6 sm:p-8 theory-card theory-kpk">
                    <h4 class="font-black text-emerald-600 uppercase text-xs sm:text-sm tracking-tight mb-2">04. KPK (Kriteria Penilaian Klinis)</h4>
                    <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-bold">Algoritma akumulasi skor untuk menentukan tingkat urgensi rujukan atau tindakan preventif.</p>
                </div>
            </div>
        </section>

        <section id="panduan" class="mb-24 scroll-mt-24">
            <div class="flex flex-col items-center mb-12">
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight mb-4 uppercase text-center text-shadow-strong">Alur Penggunaan Sistem</h2>
                <div class="h-2 w-16 bg-indigo-600 rounded-full shadow-sm"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="glass-card p-8 flex flex-col items-center border-b-4 border-indigo-200">
                    <div class="step-number mb-6">1</div>
                    <h4 class="font-black text-slate-900 mb-3 uppercase text-xs tracking-widest">Pengisian Tes</h4>
                    <p class="text-xs sm:text-sm text-slate-700 font-bold leading-relaxed">Siswa menjawab pertanyaan secara jujur tanpa proses login rumit.</p>
                </div>
                <div class="glass-card p-8 flex flex-col items-center border-indigo-300 bg-indigo-50/50">
                    <div class="step-number mb-6">2</div>
                    <h4 class="font-black text-slate-900 mb-3 uppercase text-xs tracking-widest">Analisis Otomatis</h4>
                    <p class="text-xs sm:text-sm text-slate-700 font-bold leading-relaxed">Sistem memproses skor real-time berdasarkan metodologi klinis terpercaya.</p>
                </div>
                <div class="glass-card p-8 flex flex-col items-center border-b-4 border-indigo-200">
                    <div class="step-number mb-6">3</div>
                    <h4 class="font-black text-slate-900 mb-3 uppercase text-xs tracking-widest">Hasil Diagnosa</h4>
                    <p class="text-xs sm:text-sm text-slate-700 font-bold leading-relaxed">Penyajian hasil evaluasi beserta rekomendasi awal untuk bimbingan konseling.</p>
                </div>
            </div>
        </section>

        <section class="mb-20">
            <div class="glass-card p-8 sm:p-12 border-indigo-300 bg-white relative shadow-xl border-t-4 border-t-indigo-600">
                <div class="flex items-center space-x-4 mb-6">
                    <span class="flex items-center justify-center w-12 h-12 bg-indigo-600 text-white rounded-xl shadow-lg font-black italic text-xl">i</span>
                    <h3 class="text-xl sm:text-3xl font-black text-slate-900 uppercase tracking-tight">Jaminan Kerahasiaan Data</h3>
                </div>
                <p class="text-slate-800 font-bold leading-relaxed italic text-base sm:text-xl">
                    "Dalam etika penelitian skripsi ini, seluruh data responden dijaga kerahasiaannya. Data hanya digunakan untuk kepentingan analisis ilmiah tanpa mempublikasikan identitas pribadi siswa secara terbuka."
                </p>
            </div>
        </section>

        <footer class="text-center py-16 border-t border-slate-300/50 bg-white/30 backdrop-blur-sm">
            <div class="mb-10">
                <p class="text-slate-800 text-[12px] font-black tracking-[0.3em] uppercase mb-8 px-4 leading-loose drop-shadow-sm">
                    MTS AL-WAHAB JAKARTA BARAT & UNIVERSITAS NUSA MANDIRI
                </p>
                
                <div class="flex flex-wrap justify-center gap-3">
                    <div class="tech-tag italic">Laravel v11</div>
                    <div class="tech-tag italic">Tailwind CSS</div>
                    <div class="tech-tag italic">MySQL</div>
                    <div class="tech-tag italic">Vite Build</div>
                </div>
            </div>
            
            <p class="text-[11px] font-black text-slate-700 uppercase tracking-widest px-2 pb-10">
                © 2025 Research & Thesis Development Project
            </p>
        </footer>

    </main>
</body>
</html>