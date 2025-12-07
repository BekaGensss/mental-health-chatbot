<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Kesehatan Mental</title>
    
    <link rel="icon" href="{{ asset('images/mts.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('images/mts.jpg') }}" type="image/jpeg">
    
    {{-- MUAT ASSET KOMPILASI VIA VITE UNTUK PRODUCTION STABILITY --}}
     @php
    $isProduction = app()->environment('production');
    $manifestPath = $isProduction ? '../public_html/build/manifest.json' : public_path('build/manifest.json');
 @endphp
 
  @if ($isProduction && file_exists($manifestPath))
   @php
    $manifest = json_decode(file_get_contents($manifestPath), true);
   @endphp
    <link rel="stylesheet" href="{{ config('app.url') }}/build/{{ $manifest['resources/css/app.css']['file'] }}">
    <script type="module" src="{{ config('app.url') }}/build/{{ $manifest['resources/js/app.js']['file'] }}"></script>
  @else
    @viteReactRefresh
    @vite(['resources/js/app.js', 'resources/css/app.css'])
  @endif
    
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    
    <style>
        /* ================================================= */
        /* I. GLOBAL CHATBOT STYLING & RESPONSIVE STRUCTURE */
        /* ================================================= */
        
        .chatbot-window { 
            display: flex; 
            flex-direction: column; 
            border-radius: 16px; 
            overflow: hidden; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.25); 
            resize: both; 
            min-height: 450px; 
            min-width: 350px; 
            width: 90%; 
            max-width: 500px; 
        }
        @media (min-width: 768px) {
            .chatbot-window {
                max-width: 600px; 
            }
        }
        
        .chat-body { 
            flex-grow: 1; 
            overflow-y: auto; 
            padding: 1rem; 
            background-color: #ffffff; 
        } 
        .chat-bubble { 
            max-width: 95%; 
            padding: 12px 16px; 
            border-radius: 18px; 
            margin-bottom: 12px; 
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); 
            display: flex; 
            align-items: flex-start; 
        }
        .bot-bubble { 
            background-color: #e0f2fe; 
            border-radius: 18px 18px 18px 4px; 
            margin-right: auto; 
            color: #075985; 
            font-size: 0.95rem; 
        }
        .user-bubble { 
            background-color: #4f46e5; 
            color: white; 
            border-radius: 18px 18px 4px 18px; 
            margin-left: auto; 
        }
        .loading-dot { 
            display: inline-block; 
            width: 8px; 
            height: 8px; 
            background-color: #4f46e5; 
            border-radius: 50%; 
            animation: dot-loading 1.2s infinite ease-in-out; 
            margin: 0 2px; 
        }
        @keyframes dot-loading { 
            0%, 80%, 100% { transform: scale(0); } 
            40% { transform: scale(1); } 
        }
        #chat-input-row { display: flex; }
        .hidden { display: none !important; }

        /* Card Transparan (Glassmorphism) */
        .modern-backdrop-card {
            background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.18); box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4); color: white; 
        }

        /* Input Fields (Hitam) */
        #data-diri-fields input, #data-diri-fields select { color: #1f2937; border: 1px solid #d1d5db; }
        #data-diri-fields select option { color: #1f2937; background-color: white; }
        
        /* ================================================= */
        /* II. FLOATING BUTTONS & RESPONSIVE POSITIONING */
        /* ================================================= */
        
        /* A. Container Tombol KANAN BAWAH (Hanya Chatbot) */
        .chatbot-button-container {
            position: fixed;
            bottom: 24px; 
            right: 24px; 
            z-index: 50;
            display: flex;
            /* Di sini hanya ada satu tombol, biarkan flex-direction row/column tidak terlalu penting */
            flex-direction: column; 
            align-items: flex-end;
            gap: 12px; 
            transition: all 0.3s ease;
        }

        /* B. Container Tombol KIRI BAWAH (About/Info) */
        .about-fixed-container {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: 50;
        }

        .about-button-left-side {
            background-color: #10b981; /* Green 500 */
            color: white;
            padding: 10px 15px;
            border-radius: 9999px;
            font-weight: bold;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center; /* Pusat ikon saat teks hilang */
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        /* PERUBAHAN: Hanya ikon di kiri bawah */
        .about-button-icon-only {
            width: 48px; /* Ukuran standar floating button */
            height: 48px;
            padding: 0;
            border-radius: 9999px; /* Jadikan bulat */
        }
        .about-button-icon-only span {
            display: none;
        }


        .about-button-left-side:hover {
            background-color: #059669; /* Green 600 */
            transform: translateY(-2px);
        }

        /* C. Ikon Overlay (Tombol Floating) */
        .icon-overlay-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: none; 
            border-radius: 9999px; 
            transition: all 0.3s ease;
        }
        
        .icon-overlay-wrapper .image-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 1; 
            transition: opacity 0.3s ease;
            border-radius: 9999px; 
        }

        /* Styling Chatbot */
        #open-chatbot {
            width: 48px; 
            height: 48px;
        }
        #open-chatbot:hover {
            transform: translateY(-2px);
        }

        /* HAPUS SEMUA LOGIC INSTAGRAM/BUG REPORT POPUP DARI CSS */
        /* Hapus .bug-report-popup-wrapper, .bug-report-popup, dll. */

        /* Styling Notifikasi Waktu - Di Atas Kiri & Hitam Transparan */
        #floating-time-notification { 
            position: fixed; 
            top: 20px; 
            left: 20px; 
            transform: translateX(0); 
            transition: top 0.5s ease-out; 
            z-index: 100; 
            background-color: rgba(0, 0, 0, 0.4); 
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive Desktop adjustments */
        @media (min-width: 640px) {
            .chatbot-button-container { 
                right: 32px; 
                bottom: 32px; 
                flex-direction: column; 
                gap: 16px;
            }
            .about-fixed-container {
                left: 32px;
                bottom: 32px;
            }
            .about-button-icon-only {
                width: 56px; 
                height: 56px;
            }
            /* PERBAIKAN: Ukuran jam diperkecil di desktop */
            #floating-time-notification #clock-display {
                font-size: 2rem; 
            }
            #open-chatbot {
                width: 64px; 
                height: 64px;
            }
        }
        
        /* OVERRIDE MOBILE CARD */
        @media (max-width: 639px) {
            .chatbot-button-container {
                 flex-direction: row; 
            }
            .about-button-left-side {
                padding: 0;
                width: 48px;
                height: 48px;
                border-radius: 9999px;
            }
            .about-button-left-side span {
                display: none;
            }
        }

        /* Styling Modal Informasi Konsultasi */
        .info-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none; /* Default hidden */
            justify-content: center;
            align-items: center;
            z-index: 100;
        }

        .info-modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            animation: fadeInScale 0.3s ease-out;
            color: #1f2937;
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .info-modal-content h2 {
            color: #4f46e5;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .info-modal-content p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .info-modal-content .privacy-guarantee {
            font-weight: normal; 
            color: #059669; /* Green 600 */
        }

        .info-modal-content .social-link {
             background-color: #e1306c; /* Instagram Pink */
             margin-top: 15px;
        }

        .info-modal-content .social-link:hover {
            background-color: #c13584;
        }

        .info-modal-content a {
            background-color: #10b981;
            color: white;
            padding: 10px 20px;
            border-radius: 9999px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .info-modal-content a:hover {
            background-color: #059669;
            transform: translateY(-2px);
        }
        /* Penyesuaian tombol tutup di kanan atas modal */
        .close-info-modal {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #9ca3af;
            cursor: pointer;
            transition: color 0.3s;
        }
        .close-info-modal:hover {
            color: #4f46e5;
        }
        /* Wrapper untuk posisi relatif pada tombol tutup */
        .info-modal-relative-wrapper {
            position: relative;
        }
    </style>
</head>
{{-- MENGGUNAKAN INLINE STYLE DENGAN HELPER ASSET --}}
<body style="background-image: url('{{ asset('images/bg_sekolah.png') }}');" class="bg-cover bg-fixed bg-center font-sans antialiased min-h-screen"> 
    
    {{-- NOTIFIKASI WAKTU REAL-TIME (ATAS KIRI) --}}
    <div id="floating-time-notification" class="modern-backdrop-card px-4 py-2 sm:px-6 sm:py-3 rounded-xl text-left">
        <div id="clock-display" class="text-white font-black text-2xl sm:text-3xl tracking-wider leading-none"></div> 
        <div id="date-display-notif" class="text-gray-300 font-semibold text-xs sm:text-sm mt-1"></div>
    </div>


    {{-- HEADER KANAN ATAS (Logo MTS dan UNM) --}}
    <div class="fixed top-5 right-5 z-20 flex items-center space-x-3">
        {{-- Logo MTS --}}
        <img src="{{ asset('images/mts.jpg') }}" alt="Logo MTs Al-Wahab" class="h-8 w-8 sm:h-10 sm:w-10 object-contain rounded-lg border border-gray-200">
        {{-- Logo UNM --}}
        <img src="{{ asset('images/unm.png') }}" alt="Logo UNM" class="h-8 w-8 sm:h-10 sm:w-10 object-contain rounded-lg border border-gray-200">
        
        {{-- Teks (Hanya terlihat di Desktop) --}}
        <div class="p-3 bg-white/70 backdrop-blur-md rounded-xl shadow-lg border border-gray-100 text-right hidden sm:block">
            <p class="text-sm font-semibold text-indigo-700">Penelitian UNM</p>
            <p class="text-xs text-gray-500">Sistem Kuesioner Mandiri</p>
        </div>
    </div>
    
    {{-- BARU: TOMBOL INFORMASI PENTING (KIRI BAWAH - HANYA ICON) --}}
    <div class="about-fixed-container">
        <button id="about-button-left-side"
            class="about-button-left-side about-button-icon-only" 
            title="Informasi Penting dan Kerahasiaan Data">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{-- Teks dihapus sesuai permintaan --}}
        </button>
    </div>


    {{-- BUTTON CHATBOT (KANAN BAWAH) --}}
    <div class="chatbot-button-container"> 
        {{-- Tombol Chatbot AI --}}
        <button id="open-chatbot" 
            class="bg-indigo-600 text-white rounded-full shadow-2xl hover:bg-indigo-700 transition duration-300 transform icon-overlay-wrapper relative" 
            title="Mulai Chatbot">
            <img src="{{ asset('images/chatbot.jpg') }}" alt="Chatbot Icon" class="image-overlay" />
        </button>
    </div>
    
    {{--- SAMBUTAN UTAMA ---}}
    
    <div class="min-h-screen flex flex-col justify-center items-center bg-black bg-opacity-10 px-4">
        {{-- Gunakan class p-4 untuk mobile agar lebih kecil --}}
        <div class="modern-backdrop-card p-4 sm:p-10 rounded-3xl shadow-2xl w-11/12 md:max-w-xl lg:max-w-3xl text-center" style="max-width: 900px; width: 90%;">
            
            {{-- Teks Scaling Mobile/Desktop --}}
            <h1 class="text-3xl sm:text-5xl font-black text-white mt-4 sm:mt-6 tracking-tight">SELAMAT DATANG!</h1>
            <div class="flex flex-wrap justify-center items-center mt-4 gap-3">
                <p class="text-sm sm:text-xl text-indigo-100 font-medium leading-relaxed">
                    Kami siap mendampingi. Untuk memulai sesi pengisian form kesehatan mental, silakan klik ikon pesan di kanan bawah samping kanan atau mulai scoring langsung.
                </p>
                {{-- TOMBOL BARU UNTUK MELANJUTKAN LANGSUNG --}}
                <button id="open-chatbot-direct" 
                        class="bg-green-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-full font-bold text-sm sm:text-base shadow-lg hover:bg-green-700 transition duration-300 transform hover:scale-105 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> Mulai Skoring Langsung
                </button>
            </div>
            
        </div>
    </div>
    
    {{--- CHATBOT MODAL ---}}
    
    <div id="chatbot-container" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 overflow-y-auto">
        <div class="flex justify-center items-center p-4 sm:p-10 min-h-screen">
            <div id="chatbot-modal-content" 
                 class="modern-backdrop-card chatbot-window w-full mx-auto my-8 rounded-lg h-[80vh]" 
                 style="background-color: rgba(255, 255, 255, 0.95); position: relative;">
                
                <div class="p-2 bg-indigo-700 text-white flex justify-between items-center rounded-t-xl shrink-0">
                    <h1 class="text-lg font-bold">💬 GenZ bot Interaktif</h1>
                    <button id="close-chatbot" type="button" class="text-indigo-200 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="chat-body" id="chat-body"></div>
                
                <div class="p-4 border-t user-input-area shrink-0" style="background-color: #f9f9f9;">
                    <form id="chat-input-form" onsubmit="event.preventDefault(); handleInput();">
                        <div id="data-diri-fields" class="space-y-3 mb-3"></div>

                        <div class="flex space-x-2" id="chat-input-row">
                            <input type="text" id="user-input" class="w-full border border-gray-300 rounded-xl p-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-inner text-gray-900" 
                                disabled placeholder='Ketik "Mulai form" untuk melanjutkan...' autofocus style="color: #1f2937 !important;">
                            <button type="submit" id="send-btn" class="bg-indigo-600 text-white rounded-xl px-4 hover:bg-indigo-700 transition shadow-md" disabled>Kirim</button>
                        </div>
                        
                        <div id="final-scoring-form-wrapper" class="hidden mt-3">
                            <button type="button" id="final-submit-button-trigger" class="w-full bg-green-600 text-white py-2 rounded-lg font-bold hover:bg-green-700">
                                Mulai Formulir Skoring Resmi →
                            </button>
                            <form id="final-scoring-form" method="POST" action="{{ route('form.submit') }}" class="hidden">
                                @csrf
                                <div id="hidden-scoring-inputs"></div>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

    {{--- MODAL INFORMASI KONSULTASI (Digunakan oleh tombol Kiri Bawah) ---}}
    <div id="info-modal" class="info-modal">
        <div class="info-modal-content info-modal-relative-wrapper">
            <button class="close-info-modal" id="close-info-modal">&times;</button>
            <h2 class="text-2xl">ℹ️ Informasi Penting</h2>
            <p>
                Formulir kuesioner mandiri ini dirancang hanya untuk tujuan pembelajaran, penelitian, dan edukasi awal. 
                Hasil yang didapat bersifat indikatif dan tidak dapat menggantikan diagnosis profesional.
            </p>
            <p class="privacy-guarantee">
                Kami menjamin kerahasiaan data pribadi Anda.
            </p>
            
            <p>
                Jika Anda merasa membutuhkan bantuan atau ingin melakukan konsultasi lebih lanjut dengan ahli yang berpengalaman, 
                silakan klik tombol di bawah ini:
            </p>
            <div class="text-center space-y-3">
                <a href="https://puspa.jakarta.go.id/tentang-puspa-jakarta" target="_blank" title="Kunjungi Profesional">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M12 20.573V16m-4-7a4 4 0 00-4 4v2a2 2 0 002 2h8a2 2 0 002-2v-2a4 4 0 00-4-4zm4-7a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                    Konsultasi dengan Profesional
                </a>
                
                {{-- TOMBOL INSTAGRAM BARU DI DALAM MODAL --}}
                <a href="https://www.instagram.com/suma.liebert/" target="_blank" title="Laporkan Bug ke Developer" class="social-link">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2M9 16H5a2 2 0 01-2-2v-6a2 2 0 012-2h4M9 16h6m-3 3v-3m0-3h.01M20 12h-8m-8-4h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4a2 2 0 012-2z"></path>
                    </svg>
                    Laporkan Bug (via Instagram Developer)
                </a>
            </div>
        </div>
    </div>
    
    <script>
        // --- Variabel Global dan DOM Access ---
        const notification = document.getElementById('floating-time-notification');
        const infoModal = document.getElementById('info-modal');
        const aboutButtonLeftSide = document.getElementById('about-button-left-side');
        const closeInfoModal = document.getElementById('close-info-modal');
        
        // --- Variabel DOM Chatbot AI (Dipertahankan) ---
        const container = document.getElementById('chatbot-container');
        const attentionBubble = document.getElementById('chat-attention-bubble');
        const chatBody = document.getElementById('chat-body');
        const dataDiriFields = document.getElementById('data-diri-fields');
        const userInputField = document.getElementById('user-input');
        const sendBtn = document.getElementById('send-btn');
        const finalScoringFormWrapper = document.getElementById('final-scoring-form-wrapper');
        const chatInputRow = document.getElementById('chat-input-row');
        
        // --- Variabel Data & Status ---
        const classes = {!! json_encode(isset($classes) ? $classes : ['X-A', 'X-B', 'XI-IPA', 'XII-IPS']) !!}; 
        let currentStep = 0; 
        let collectedData = {}; 
        let isAwaitingChatResponse = false;
        let isAwaitingDataDiri = false;
        
        // --- KONSTANTA LINK ---
        const DIRECT_SCORING_LINK = '{{ route("form.scoring_form") }}';
        
        // --- UTILITIES (Chatbot AI) ---
        function getGreeting() { 
            const hour = new Date().getHours();
            if (hour >= 4 && hour < 10) return 'Pagi'; 
            else if (hour >= 10 && hour < 15) return 'Siang'; 
            else if (hour >= 15 && hour < 18) return 'Sore'; 
            else return 'Malam'; 
        }
        
        function addMessage(content, sender = 'bot') {
            const msgDiv = document.createElement('div');
            msgDiv.className = `chat-bubble ${sender === 'bot' ? 'bot-bubble' : 'user-bubble'}`;
            msgDiv.innerHTML = content;
            chatBody.appendChild(msgDiv);
            chatBody.scrollTop = chatBody.scrollHeight;
        }
        
        function toggleAttentionBubble(show) {
            if (show) {
                attentionBubble.classList.add('show-bubble');
            } else {
                if (document.getElementById('chatbot-container').classList.contains('hidden')) {
                   attentionBubble.classList.remove('show-bubble');
                }
            }
        }
        
        function animateAttentionBubble() {
            if (!container.classList.contains('hidden')) return;
            // Dihapus logic animasi time-out karena bubble selalu tampil
        }
        
        function showTypingIndicator() {
            const indicator = document.createElement('div');
            indicator.id = 'typing-indicator';
            indicator.className = 'chat-bubble bot-bubble';
            indicator.innerHTML = `<div class="loading-dot" style="background-color: #075985;"></div><div class="loading-dot" style="background-color: #075985; animation-delay: 0.2s;"></div><div class="loading-dot" style="background-color: #075985; animation-delay: 0.4s;"></div>`;
            chatBody.appendChild(indicator);
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function hideTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) indicator.remove();
        }

        function renderFinalSubmitButton() {
            chatInputRow.style.display = 'none'; 
            finalScoringFormWrapper.classList.remove('hidden');
            
            document.getElementById('final-submit-button-trigger').onclick = function() {
                localStorage.setItem('collectedData', JSON.stringify(collectedData));
                window.location.href = DIRECT_SCORING_LINK; 
            };
        }

        function renderDataDiriFields() {
            isAwaitingDataDiri = true;
            userInputField.disabled = true;
            sendBtn.disabled = true;
            chatInputRow.style.display = 'none'; 
            
            dataDiriFields.innerHTML = `
                <div>
                    <input type="text" id="name-input" class="w-full border border-gray-300 rounded-xl p-3 mb-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-sm" placeholder="Nama Lengkap" value="${collectedData.name || ''}">
                    
                    <select id="class-level-select" class="w-full border border-gray-300 rounded-xl p-3 text-gray-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition shadow-sm">
                        <option value="" class="text-gray-900">-- Pilih Kelas --</option>
                        ${classes.map(c => `<option value="${c}" class="text-gray-900" ${collectedData.class_level === c ? 'selected' : ''}>${c}</option>`).join('')}
                    </select>
                    
                    <button type="button" id="submit-data-diri" class="mt-4 w-full bg-indigo-600 text-white rounded-xl py-3 font-semibold hover:bg-indigo-700 transition shadow-lg">Lanjut</button>
                </div>
            `;
            const nameInput = document.getElementById('name-input');
            const classSelect = document.getElementById('class-level-select');

            // Menambahkan listener Enter key untuk UX data diri
            const submitDataDiri = document.getElementById('submit-data-diri');

            nameInput.addEventListener('keydown', (e) => {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    submitDataDiri.click();
                }
            });

            classSelect.addEventListener('keydown', (e) => {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    submitDataDiri.click();
                }
            });
            
            document.getElementById('submit-data-diri').onclick = handleDataDiriSubmit;
        }
        
        function handleDataDiriSubmit() {
            const nameInput = document.getElementById('name-input').value.trim();
            const classInput = document.getElementById('class-level-select').value.trim();

            if (nameInput === '' || classInput === '') {
                alert('Nama lengkap dan Kelas wajib diisi!');
                return;
            }
            
            collectedData['name'] = nameInput;
            collectedData['class_level'] = classInput;
            
            dataDiriFields.innerHTML = ''; 
            isAwaitingDataDiri = false;
            currentStep = 2; 
            
            addMessage(`Nama: ${nameInput}, Kelas: ${classInput}`, 'user');
            
            chatInputRow.style.display = 'flex';
            userInputField.disabled = false; 
            sendBtn.disabled = false; 
            userInputField.focus(); 
            
            // Mengirim pesan inisial yang lebih ringkas dan profesional
            sendMessageToAI(`Data diri telah lengkap. Mohon tunggu, GenZbot akan segera memulai sesi interaktif.`, true);
        }

        function sendMessageToAI(message, isInitial = false) {
            isAwaitingChatResponse = true;
            showTypingIndicator();

            const payload = { 
                message: message, 
                step: currentStep,
                name: collectedData.name,
                class_level: collectedData.class_level
            };

            fetch('{{ route("chatbot.interact") }}', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                hideTypingIndicator();
                isAwaitingChatResponse = false;
                
                if (data.action === 'START_SCORING_FORM') {
                    renderFinalSubmitButton();
                } 
                
                addMessage(data.bot_message || "Server tidak memberikan respons.", 'bot');
                
                chatBody.scrollTop = chatBody.scrollHeight; 
            })
            .catch(error => {
                hideTypingIndicator();
                isAwaitingChatResponse = false;
                addMessage('Koneksi ke server AI gagal. Mohon coba lagi atau ketik MULAI FORM.', 'bot');
                console.error('Fetch Error:', error);
            });
        }
        
        function handleInput() {
            if (isAwaitingChatResponse || isAwaitingDataDiri) return;
            
            const message = userInputField.value.trim();
            if (message === '') return;

            addMessage(message, 'user');
            userInputField.value = '';
            
            sendMessageToAI(message);
        }

        function startChatSession() {
            chatBody.innerHTML = '';
            currentStep = 1;
            document.getElementById('final-scoring-form-wrapper').classList.add('hidden'); 
            userInputField.value = ''; 

            // Muat data dari localStorage jika ada
            const storedData = localStorage.getItem('collectedData');
            if (storedData) {
                try {
                    collectedData = JSON.parse(storedData);
                } catch (e) {
                    console.error("Error parsing stored data:", e);
                }
            }
            
            // PERBAIKAN DIALOG Sapaan Awal (DIRAPIHKAN)
            const greeting = getGreeting();
            // Pesan Sapaan dipecah per paragraf untuk memastikan pemotongan kata tidak terjadi
            const welcomeMessage = `
                <p><strong>Selamat ${greeting}!</strong></p>
                <p>Saya GenZbot, asisten pengisian kuesioner mandiri Anda.</p>
                
                <ol class="list-decimal list-inside pl-4 mt-2 space-y-1 text-sm">
                    <li>Mohon lengkapi data diri Anda di formulir bawah ini.</li>
                    <li>Data Anda kami jamin kerahasiaannya.</li>
                    <li>Setelah data terisi, ketik <strong>"Mulai form"</strong> atau tekan <strong>'Lanjut'</strong> untuk memulai kuesioner.</li>
                </ol>
            `;
            
            addMessage(welcomeMessage, 'bot');
            renderDataDiriFields(); 
        }

        // --- JAVASCRIPT UNTUK JAM REAL-TIME ---
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            
            const timeString = now.toLocaleTimeString('id-ID', timeOptions);
            const dateString = now.toLocaleDateString('id-ID', dateOptions);

            const clockElement = document.getElementById('clock-display');
            const dateElement = document.getElementById('date-display-notif');
            
            if (clockElement) clockElement.textContent = timeString; 
            if (dateElement) dateElement.textContent = dateString;
        }
        
        function showNotification() {
            updateClock();
            // Dihapus pengecekan 'show' di sini, karena selalu tampil
        }

        // Fungsi untuk menampilkan/menyembunyikan modal info
        function toggleInfoModal(show) {
            infoModal.style.display = show ? 'flex' : 'none';
            document.body.style.overflow = show ? 'hidden' : 'auto';
        }
        
        // --- INITIALIZATION & EVENT LISTENERS ---
        
        document.addEventListener('DOMContentLoaded', function () {
            
            // Inisiasi Jam Real-Time
            updateClock();
            setInterval(updateClock, 1000); 

            // 1. Listener tombol Chatbot AI (Pembuka Modal Utama)
            document.getElementById('open-chatbot').addEventListener('click', function() {
                container.classList.remove('hidden'); // Buka modal Chatbot
                document.body.style.overflow = 'hidden';
                startChatSession(); // Mulai sesi Chatbot
            });

            // 2. Listener tombol Mulai Skoring Langsung
            document.getElementById('open-chatbot-direct').addEventListener('click', function() {
                window.location.href = DIRECT_SCORING_LINK;
            });

            // 3. Listener form Chatbot AI
            document.getElementById('chat-input-form').addEventListener('submit', function(e) {
                e.preventDefault();
                handleInput(); 
            });
            
            // 4. Listener tombol Tutup Chatbot AI
            document.getElementById('close-chatbot').addEventListener('click', function() {
                container.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });

            // 5. Listener tombol Informasi Penting (Kiri Bawah)
            aboutButtonLeftSide.addEventListener('click', function() {
                toggleInfoModal(true);
            });

            // 6. Listener tombol Tutup Modal Info
            closeInfoModal.addEventListener('click', function() {
                toggleInfoModal(false);
            });

            // 7. Listener klik di luar Modal Info untuk menutup
            infoModal.addEventListener('click', function(e) {
                if (e.target === infoModal) {
                    toggleInfoModal(false);
                }
            });
        });
    </script>
</body>
</html>