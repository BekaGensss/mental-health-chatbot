<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Kuesioner</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Pengaturan Cetak dan Font Dasar */
        @page { margin: 0.6cm; size: A4; }
        body { 
            font-family: 'Poppins', sans-serif; 
            margin: 0; 
            padding: 0; 
            font-size: 9pt; 
            color: #1f2937; 
            position: relative; 
        }
        
        .container { 
            max-width: 100%;
            padding: 0 0.6cm; 
            padding-bottom: 25px; 
        }
        .clearfix::after { content: ""; display: table; clear: both; }

        /* HEADER */
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            padding-bottom: 8px; 
            border-bottom: 3px solid #4f46e5; 
        }
        .title-container h1 { 
            color: #4f46e5; 
            font-size: 16pt; 
            margin: 0; 
            font-weight: 900; 
            line-height: 1.1;
        }
        .title-container p { 
            color: #4b5563; 
            font-size: 8pt; 
            margin-top: 3px; 
        }
        
        /* 1. INFORMASI DASAR */
        .section { margin-bottom: 20px; } 
        .section h2 { 
            font-size: 12pt; 
            color: #1e3a8a; 
            margin: 0 0 8px 0; 
            font-weight: 800; 
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 4px;
        }
        
        /* Tata Letak Kolom Info Murid (Menggunakan display: flex/table untuk stabilitas) */
        .info-grid { 
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .info-detail {
            width: 33%; /* 3 Kolom Full Width */
            padding-right: 15px;
            font-size: 10pt;
            line-height: 1.6;
        }
        .info-grid strong { color: #4f46e5; font-weight: 700; }
        .info-grid p { margin: 0; }

        .summary-box { 
            font-size: 10pt; 
            font-weight: 600; 
            color: #1f2937; 
            margin-top: 10px; 
            line-height: 1.5; 
            background-color: #f0f9ff; 
            padding: 15px; 
            border-left: 6px solid #4f46e5;
            border-radius: 6px;
        }
        
        /* 📊 VISUALISASI UTAMA: PROGRESS BAR */
        .chart-area { 
            margin-top: 15px; 
            padding: 5px 0;
        }
        .chart-area h2 { 
            font-size: 13pt; 
            color: #1f2937; 
            margin-bottom: 15px; 
            font-weight: 900; 
            text-align: left; /* Biarkan rata kiri agar tidak terlalu menonjol */
        }
        /* Struktur Bar Chart Item: Full Width */
        .chart-item { 
            margin-bottom: 12px; 
            border: 1px solid #e5e7eb;
            padding: 10px; 
            border-radius: 6px;
        }
        .chart-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 6px; 
        }
        .chart-label { 
            font-size: 10pt; 
            color: #1e3a8a; 
            font-weight: 700; 
        }
        .chart-info { 
            font-size: 9pt; 
            font-weight: 600; 
            display: flex; 
            align-items: center; 
        }
        .bar-container { 
            width: 100%; 
            height: 18px; 
            background-color: #e5e7eb; 
            border-radius: 4px; 
            overflow: hidden; 
            position: relative; 
        }
        .bar { 
            height: 100%; 
            position: absolute; 
            top: 0; 
            left: 0; 
            line-height: 18px; 
            color: white; 
            font-size: 8pt; 
            font-weight: 700; 
            box-sizing: border-box; 
            text-align: right; 
            padding-right: 4px; /* Tambah padding kanan */
        }
        .bar:empty::before { content: ""; }
        
        /* Classes Warna Kustom */
        .text-danger { color: #dc2626; font-weight: 800; } 
        .text-warning { color: #f59e0b; font-weight: 700; } 
        .text-success { color: #059669; font-weight: 700; }
        .text-info { color: #0e7490; font-weight: 700; } 
        .risk-level { 
            display: inline-block; 
            padding: 2px 6px; 
            border-radius: 4px; 
            font-size: 8pt; 
            margin-left: 10px; 
            text-transform: uppercase;
        }
        /* Style untuk background level risiko */
        .bg-danger-light { background-color: #fef2f2; border: 1px solid #dc2626; color: #dc2626; }
        .bg-warning-light { background-color: #fffbeb; border: 1px solid #f59e0b; color: #f59e0b; }
        .bg-success-light { background-color: #ecfdf5; border: 1px solid #059669; color: #059669; }
        .bg-info-light { background-color: #eff6ff; border: 1px solid #0e7490; color: #0e7490; }
        
        /* Footer */
        .footer-note {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            margin: 0 0.6cm; 
            border-top: 1px dashed #d1d5db; 
            padding-top: 5px; 
            font-size: 8pt; 
            color: #6b7280;
            text-align: center;
            background-color: white; 
        }
    </style>
</head>
<body>
    <div class="container">
        
        {{-- HEADER --}}
        <div class="header clearfix">
            <div class="title-container">
                <h1>LAPORAN HASIL KUESIONER KESEHATAN MENTAL</h1>
                <p>Dibuat pada: {{ date('d F Y') }}</p>
            </div>
        </div>
        
        {{-- INFORMASI MURID --}}
        <div class="section clearfix">
            <h2>Informasi Dasar Murid</h2>
            
            <div class="info-grid">
                
                {{-- Kolom 1: Nama --}}
                <div class="info-detail">
                    <p><strong>Nama:</strong> {{ $studentName ?? 'N/A' }}</p>
                </div>
                
                {{-- Kolom 2: Kelas --}}
                <div class="info-detail">
                    <p><strong>Kelas:</strong> {{ $studentClass ?? 'N/A' }}</p>
                </div>

                {{-- Kolom 3: ID Pengisian --}}
                <div class="info-detail">
                    <p><strong>ID Pengisian:</strong> {{ $studentId ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- RINGKASAN HASIL --}}
        <div class="section">
            <h2>Ringkasan Hasil Keseluruhan</h2>
            <p class="summary-box">
                <strong style="font-size: 11pt; color: #4f46e5;">TOTAL SKOR: {{ $totalScore ?? 'N/A' }} / 75</strong><br>
                {{ $resultSummary ?? 'Hasil analisis tidak tersedia.' }}
            </p>
        </div>

        {{-- 📊 VISUALISASI UTAMA: Progress Bar Penuh Lebar (SATU KOLOM) --}}
        <div class="chart-area">
            <h2>Analisis Risiko Keseimbangan Diri (Per Dimensi)</h2>
            
            {{-- ITEM 1: Depresi (PHQ-9) --}}
            <div class="chart-item">
                <div class="chart-header">
                    <span class="chart-label">Depresi (PHQ-9) (Max 15)</span>
                    <span class="chart-info">
                        <span class="text-danger">{{ number_format($phq9Percent ?? 0, 1) }}% Risiko</span> (Skor: {{ $phq9Score ?? 0 }})
                        <span class="risk-level bg-danger-light">{{ $phq9Level ?? 'N/A' }}</span>
                    </span>
                </div>
                <div class="bar-container">
                    <div class="bar" style="width: {{ min(100, $phq9Percent ?? 0) }}%; background-color: #dc2626;"></div>
                </div>
            </div>

            {{-- ITEM 2: Kecemasan (GAD-7) --}}
            <div class="chart-item">
                <div class="chart-header">
                    <span class="chart-label">Kecemasan (GAD-7) (Max 15)</span>
                    <span class="chart-info">
                        <span class="text-warning">{{ number_format($gad7Percent ?? 0, 1) }}% Risiko</span> (Skor: {{ $gad7Score ?? 0 }})
                        <span class="risk-level bg-warning-light">{{ $gad7Level ?? 'N/A' }}</span>
                    </span>
                </div>
                <div class="bar-container">
                    <div class="bar" style="width: {{ min(100, $gad7Percent ?? 0) }}%; background-color: #f59e0b;"></div> 
                </div>
            </div>

            {{-- ITEM 3: Kualitas Perilaku Sehat (KPK) --}}
            <div class="chart-item">
                <div class="chart-header">
                    <span class="chart-label">Perilaku Sehat (KPK) (Max 15)</span>
                    <span class="chart-info">
                        <span class="text-success">{{ number_format($kpkPercent ?? 0, 1) }}% Kualitas</span> (Skor: {{ $kpkScore ?? 0 }})
                        <span class="risk-level bg-success-light">{{ $kpkLevel ?? 'N/A' }}</span>
                    </span>
                </div>
                <div class="bar-container">
                    <div class="bar" style="width: {{ min(100, $kpkPercent ?? 0) }}%; background-color: #059669;"></div>
                </div>
            </div>

            {{-- ITEM 4: Total DASS-21 --}}
            <div class="chart-item">
                <div class="chart-header">
                    <span class="chart-label">Total DASS-21 (Max 30)</span>
                    <span class="chart-info">
                        <span class="text-info">{{ number_format($dass21Percent ?? 0, 1) }}% Risiko</span> (Skor: {{ $dass21Score ?? 0 }})
                        <span class="risk-level bg-info-light">{{ $dass21Level ?? 'N/A' }}</span>
                    </span>
                </div>
                <div class="bar-container">
                    <div class="bar" style="width: {{ min(100, $dass21Percent ?? 0) }}%; background-color: #0e7490;"></div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- FOOTER ABSOLUT DI BAGIAN BAWAH HALAMAN --}}
    <p class="footer-note">
        Dokumen ini adalah output otomatis. Hasil dapat ditinjau lebih lanjut dengan orang yang lebih berpengalaman.
    </p>
</body>
</html>