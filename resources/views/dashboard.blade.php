<x-app-layout>
    {{-- Memuat Chart.js (tetap dipertahankan untuk kebutuhan future proofing/jika ada widget yang memerlukan) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script> 

    <x-slot name="header">
        {{-- Header: Dashboard Singkat --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4">
            {{ __('Dashboard Singkat') }}
        </h2>
    </x-slot>
    
    {{-- PHP FUNCTION untuk mendapatkan sapaan realtime --}}
    @php
        function getAdminGreeting() { 
            $hour = date('H');
            if ($hour >= 4 && $hour < 10) return 'Pagi'; 
            else if ($hour >= 10 && $hour < 15) return 'Siang'; 
            else if ($hour >= 15 && $hour < 18) return 'Sore'; 
            else return 'Malam'; 
        }
        // Pastikan Auth::user() ada sebelum mengakses name
        $adminName = Auth::user()->name ?? 'Admin'; 
        $greeting = getAdminGreeting();
    @endphp
    
    <div class="py-6 sm:py-10 bg-gray-50/70">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- --- SAPAAN REALTIME & WAKTU --- --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end pb-6 space-y-4 md:space-y-0">
                
                {{-- SAPAAN BESAR --}}
                <div class="order-2 md:order-1 mt-4 md:mt-0">
                    <p class="font-extrabold text-3xl sm:text-4xl text-cyan-600">Selamat {{ $greeting }}!</p>
                    <p class="text-xl font-semibold text-gray-800 mt-1">
                        Selamat datang kembali, {{ $adminName }}
                    </p>
                </div>

                {{-- WAKTU REAL-TIME (KANAN ATAS KONTEN) --}}
                <div class="order-1 md:order-2 w-full md:w-auto text-left md:text-right border-b md:border-b-0 border-gray-200 pb-2 md:pb-0">
                    <div id="realtime-time" class="text-4xl font-extrabold text-gray-800 tracking-tight">00:00:00</div>
                    <div id="realtime-date" class="text-md text-gray-500 font-medium">Kamis, 13 November 2025</div>
                </div>
            </div>
            {{-- --- END SAPAAN REALTIME & WAKTU --- --}}
            
            @php
                // --- INISIALISASI VARIABEL DENGAN FALLBACK AMAN ---
                $totalMurid = $totalMurid ?? 0;
                $totalPengisianHariIni = $totalPengisianHariIni ?? 0;
                $totalPertanyaan = $totalPertanyaan ?? 0;
                $totalAkunAdmin = $totalAkunAdmin ?? 0; 
                $totalKelasAktif = $totalKelasAktif ?? 0; 
                $totalLogAktivitas = $totalLogAktivitas ?? 0; 
            @endphp
            
            {{-- TEKS "Ringkasan Statistik Utama" telah dihapus di sini --}}

            {{-- KARTU UTAMA (6 Kolom Responsive) --}}
            {{-- Grid ditingkatkan menjadi 2 kolom di mobile, 3 di tablet, dan 6 di desktop besar --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4"> 
                
                {{-- KARTU 1: TOTAL MURID (Cyan) --}}
                <div class="bg-white p-4 rounded-2xl shadow-xl ring-1 ring-cyan-100 transition duration-300 hover:shadow-cyan-300/50 transform hover:scale-[1.03] cursor-pointer">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-cyan-600 uppercase tracking-widest">TOTAL MURID</p>
                        <i class="fas fa-users text-lg text-cyan-400"></i>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalMurid }}</p> 
                    <p class="text-xs text-gray-500 mt-1">Pengisian Kumulatif.</p>
                </div>
                
                {{-- KARTU 2: HARI INI (Orange - Perhatian) --}}
                <div class="bg-white p-4 rounded-2xl shadow-xl ring-1 ring-orange-100 transition duration-300 hover:shadow-orange-300/50 transform hover:scale-[1.03] cursor-pointer">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-orange-600 uppercase tracking-widest">HARI INI</p>
                        <i class="fas fa-calendar-check text-lg text-orange-400"></i>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalPengisianHariIni }}</p>
                    <p class="text-xs text-gray-500 mt-1">Aktivitas hari ini.</p>
                </div>
                
                {{-- KARTU 3: PERTANYAAN AKTIF (Green - Sukses) --}}
                <div class="bg-white p-4 rounded-2xl shadow-xl ring-1 ring-green-100 transition duration-300 hover:shadow-green-300/50 transform hover:scale-[1.03] cursor-pointer" onclick="window.location='{{ route('admin.kelola_pertanyaan') ?? '#' }}'">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-green-600 uppercase tracking-widest">PERTANYAAN</p>
                        <i class="fas fa-question-circle text-lg text-green-400"></i>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalPertanyaan }}</p>
                    <p class="text-xs text-gray-500 mt-1">Jumlah kuesioner aktif.</p>
                </div>

                {{-- KARTU 4: TOTAL ADMIN (Blue) --}}
                <div class="bg-white p-4 rounded-2xl shadow-xl ring-1 ring-blue-100 transition duration-300 hover:shadow-blue-300/50 transform hover:scale-[1.03] cursor-pointer" onclick="window.location='{{ route('admin.users.index') ?? '#' }}'">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">ADMIN</p>
                        <i class="fas fa-user-shield text-lg text-blue-400"></i>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalAkunAdmin }}</p>
                    <p class="text-xs text-gray-500 mt-1">Admin terdaftar.</p>
                </div>

                {{-- KARTU 5: KELAS AKTIF (Purple) --}}
                <div class="bg-white p-4 rounded-2xl shadow-xl ring-1 ring-purple-100 transition duration-300 hover:shadow-purple-300/50 transform hover:scale-[1.03] cursor-pointer" onclick="window.location='{{ route('admin.kelas.index') ?? '#' }}'">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-purple-600 uppercase tracking-widest">KELAS AKTIF</p>
                        <i class="fas fa-school text-lg text-purple-400"></i>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalKelasAktif }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kelas yang muncul.</p>
                </div>

                {{-- KARTU 6: TOTAL LOG (Gray) --}}
                <div class="bg-white p-4 rounded-2xl shadow-xl ring-1 ring-gray-200 transition duration-300 hover:shadow-gray-300/50 transform hover:scale-[1.03] cursor-pointer" onclick="window.location='{{ route('admin.log_aktivitas') ?? '#' }}'">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-gray-600 uppercase tracking-widest">TOTAL LOG</p>
                        <i class="fas fa-history text-lg text-gray-400"></i>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $totalLogAktivitas }}</p>
                    <p class="text-xs text-gray-500 mt-1">Aksi sistem terekam.</p>
                </div>
            </div>

            {{-- CARD PENGALIH KE HALAMAN HASIL JAWABAN MURID --}}
            <div class="bg-white p-8 shadow-2xl rounded-3xl ring-4 ring-cyan-100 text-center mt-8">
                <h4 class="font-extrabold text-2xl mb-4 text-cyan-700 flex items-center justify-center">
                    <i class="fas fa-chart-line mr-3"></i> Buka Laporan Detail & Analisis
                </h4>
                <p class="text-gray-600 mb-6 max-w-lg mx-auto">Lihat statistik terperinci, tren, dan grafik hasil pengisian formulir secara lengkap.</p>
                
                {{-- Tombol Utama Cyan --}}
                <a href="{{ route('admin.data_murid') ?? '#' }}" class="inline-flex items-center px-8 py-3 bg-cyan-600 text-white font-extrabold rounded-xl hover:bg-cyan-700 transition duration-300 shadow-xl shadow-cyan-500/50 text-base transform hover:scale-[1.05]">
                    <i class="fas fa-arrow-right mr-3"></i> Buka Hasil Jawaban Murid
                </a>
            </div>

        </div>
    </div>

{{-- SCRIPT JAVASCRIPT UNTUK REAL-TIME CLOCK (Tidak Berubah) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateRealtimeClock() {
            const now = new Date();
            
            // Format Waktu (Contoh: 01:11:47)
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const timeString = now.toLocaleTimeString('id-ID', timeOptions);
            
            // Format Tanggal (Contoh: Kamis, 13 November 2025)
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', dateOptions);

            const timeElement = document.getElementById('realtime-time');
            const dateElement = document.getElementById('realtime-date');

            if (timeElement) timeElement.textContent = timeString;
            if (dateElement) dateElement.textContent = dateString;
        }

        // Jalankan sekali saat DOM dimuat
        updateRealtimeClock();
        // Update setiap 1 detik
        setInterval(updateRealtimeClock, 1000);
    });
</script>

</x-app-layout>