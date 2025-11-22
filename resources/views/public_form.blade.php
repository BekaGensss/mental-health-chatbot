<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Kesehatan Mental</title>
    
    <link rel="icon" href="{{ asset('images/mts.jpg') }}" type="image/jpeg">
    <link rel="shortcut icon" href="{{ asset('images/mts.jpg') }}" type="image/jpeg">
    
    {{-- MUAT ASSET KOMPILASI VIA VITE UNTUK PRODUCTION STABILITY --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
        /* II. NOTIFIKASI & ANIMASI (RESPONSIVE) */
        /* ================================================= */
        
        /* A. Container Tombol Utama (Responsive positioning) */
        .chatbot-button-container {
            position: fixed;
            bottom: 24px; 
            right: 24px; 
            z-index: 50;
            display: flex;
            flex-direction: row; 
            align-items: flex-end;
            gap: 12px; 
            transition: all 0.3s ease;
        }

        /* B. Awan Pesan Horizontal (Speech Bubble) - POSISI EFEKTIF */
        #chat-attention-bubble {
            display: none; /* Menyembunyikan elemen bubble sepenuhnya */
            /* Properti Asli yang dipertahankan sebagai referensi */
            position: absolute;
            top: 50%; 
            right: 90px; 
            transform: translateY(-50%); 
            background-color: #ffffff;
            color: #4f46e5;
            padding: 8px 12px;
            border-radius: 12px; 
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 1; 
            visibility: hidden; /* Ganti visible menjadi hidden */
            transition: all 0.3s ease;
            z-index: 5;
            white-space: nowrap; 
        }
        
        /* Ekor Bubble */
        #chat-attention-bubble::after {
            display: none; /* Menyembunyikan ekornya (panah putih) */
            /* Properti Asli yang dipertahankan sebagai referensi */
            content: '';
            position: absolute;
            top: 50%; 
            right: -8px; 
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 8px 0 8px 8px; 
            border-color: transparent transparent transparent #ffffff; /* Ekor ke Kanan */
        }
        
        /* C. Ikon Overlay (Tombol Floating) */
        .icon-overlay-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .icon-overlay-wrapper .image-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 1; 
            transition: opacity 0.3s ease;
        }
        .icon-overlay-wrapper:hover .image-overlay { opacity: 1; }


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
                flex-direction: column; /* Stack di Desktop */
                gap: 16px;
            }
            /* Menyesuaikan posisi bubble di desktop */
            #chat-attention-bubble { 
                right: 120px; /* Jarak dari kanan di desktop */
                top: 50%;
                bottom: auto;
                border-radius: 12px 12px 12px 0; /* Ekor ke Kanan */
            }
            #chat-attention-bubble::after {
                top: 50%;
                right: -8px; 
                bottom: auto;
                transform: translateY(-50%);
                border-width: 8px 0 8px 8px;
                border-color: transparent transparent transparent #ffffff; /* Ekor ke Kanan */
            }
            
            /* PERBAIKAN: Ukuran jam diperkecil di desktop */
            #floating-time-notification #clock-display {
                font-size: 2rem; 
            }
        }
        
        /* OVERRIDE MOBILE CARD */
        @media (max-width: 639px) {
            .chatbot-button-container {
                 flex-direction: row; /* Biarkan berdampingan di mobile */
            }
            #chat-attention-bubble { 
                right: 90px; /* Didekatkan ke Chatbot button */
                bottom: 35px; /* Sesuaikan agar sejajar tombol */
                top: auto; /* Jangan gunakan top di mobile */
                border-radius: 12px 12px 12px 0; /* Ekor ke Kanan */
            }
            #chat-attention-bubble::after {
                top: 50%;
                right: -8px; 
                bottom: auto;
                transform: translateY(-50%);
                border-width: 8px 0 8px 8px;
                border-color: transparent transparent transparent #ffffff; /* Ekor ke Kanan */
            }
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


    {{-- BUTTON CHATBOT & SOSMED (PERBAIKAN TATA LETAK MOBILE) --}}
    <div class="chatbot-button-container" style="position: fixed; right: 24px; bottom: 24px;"> 
        
        {{-- Bubble hanya mengarah ke tombol Chatbot AI --}}
        {{-- DIV DENGAN ID chat-attention-bubble DIHAPUS KARENA TIDAK ADA KONTEN DAN MERUPAKAN SUMBER PANAH PUTIH --}}
        
        {{-- Tombol Instagram (Ditaruh di samping kiri Chatbot di mobile) --}}
        <a href="https://www.instagram.com/suma.liebert/" target="_blank" 
            class="bg-pink-600 text-white w-12 h-12 rounded-full shadow-2xl hover:bg-pink-700 transition duration-300 transform hover:scale-110 icon-overlay-wrapper" 
            title="Kunjungi Instagram">
            <img src="{{ asset('images/ig.jpg') }}" alt="Instagram Icon" class="rounded-full image-overlay" />
        </a>

        {{-- Tombol Chatbot AI (Paling Kanan) --}}
        <button id="open-chatbot" 
            class="bg-indigo-600 text-white w-16 h-16 rounded-full shadow-2xl hover:bg-indigo-700 transition duration-300 transform hover:scale-105 icon-overlay-wrapper relative" 
            title="Mulai Chatbot">
            <img src="{{ asset('images/chatbot.jpg') }}" alt="Chatbot Icon" class="rounded-full image-overlay" />
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
    

    <script>
        // --- Variabel Global dan DOM Access ---
        const notification = document.getElementById('floating-time-notification');
        
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
        
        // --- INITIALIZATION & EVENT LISTENERS ---
        
        document.addEventListener('DOMContentLoaded', function () {
            
            // Inisiasi Jam Real-Time
            updateClock();
            setInterval(updateClock, 1000); 

            // Logika Animasi Awan Pesan (DIHAPUS LOGIC ANIMASI)
            // Biarkan Bubble Tetap Tampil (default style di CSS)
            
            // 1. Listener tombol Chatbot AI (Pembuka Modal Utama)
            document.getElementById('open-chatbot').addEventListener('click', function() {
                container.classList.remove('hidden'); // Buka modal Chatbot
                document.body.style.overflow = 'hidden';
                // Hapus toggleAttentionBubble(false); 
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
                // Hapus animateAttentionBubble();
            });
        });
    </script>
</body>
</html>