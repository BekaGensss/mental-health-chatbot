<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuesioner Kesehatan Mental Resmi</title>
    
    <link rel="icon" href="/images/mts.jpg" type="image/jpeg">
    <link rel="shortcut icon" href="/images/mts.jpg" type="image/jpeg"> 
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- PASTIKAN CSS DIKOMPILASI DIMUAT DENGAN @vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" />

    <style>
        /* ================================================= */
        /* I. GLOBAL STYLING & CORE LAYOUT */
        /* ================================================= */
        body { 
            font-family: 'Inter', sans-serif; 
            /* MENGGUNAKAN PATH DARI ASSET HELPER UNTUK KEANDALAN */
            background-image: url('{{ asset('images/bg_sekolah.png') }}'); 
            background-size: cover; 
            background-attachment: fixed; 
            background-position: center;
            background-color: #f3f4f6;
        }
        
        /* Glassmorphism Card Style for Main Container */
        .main-form-card {
            background-color: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
            border-radius: 24px; 
        }

        /* Layout Grid (Responsive untuk 2 Kolom) */
        .step-form-layout { 
            display: grid; 
            grid-template-columns: 1fr; /* Default: 1 kolom di mobile */
            gap: 2rem; 
            align-items: start; 
        }
        
        /* Desktop/Tablet: 2 Kolom (2/3 dan 1/3) */
        @media (min-width: 768px) { 
            .step-form-layout { 
                grid-template-columns: 2fr 1fr; 
                gap: 2rem;
            } 
            .sticky-nav { 
                position: sticky; 
                top: 1.5rem;
            } 
        }
        
        /* Progres Bar Styling */
        .progress-bar-inner {
            transition: width 0.5s ease-in-out;
        }

        /* Input required override */
        input:required:invalid, select:required:invalid {
            box-shadow: none; 
        }
        
        /* ================================================= */
        /* II. MULTI-STEP TRANSITIONS (Halus) */
        /* ================================================= */
        .form-step { 
            display: none; 
            opacity: 0; 
            transform: translateY(10px);
            transition: opacity 0.4s ease-out, transform 0.4s ease-out; 
        }
        .form-step.active { 
            display: block; 
            opacity: 1; 
            transform: translateY(0);
        }
        
        /* ================================================= */
        /* III. RADIO BUTTON ELEGANCE (Visual Feedback) */
        /* ================================================= */
        .question-option {
            position: relative;
            transition: all 0.2s;
        }
        .question-option.has-[:checked]:after {
            content: '✓'; 
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            color: #0ea5e9; /* sky-600 */
            font-weight: bold;
            opacity: 1;
        }
        .question-option input[type="radio"] {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 10;
        }
        .question-option div.content-wrapper {
            pointer-events: none;
        }
    </style>
</head>
{{-- MENGANDALKAN CSS BLOCK DI ATAS UNTUK BACKGROUND --}}
<body class="font-sans antialiased min-h-screen p-4 sm:p-8">
    
    <div class="max-w-5xl mx-auto py-4 sm:py-10">
        
        <div class="main-form-card p-6 sm:p-10">
            <header class="mb-8">
                <h1 class="text-4xl font-extrabold text-gray-800 mb-2 tracking-tight">📝 Kuesioner Kesehatan Mental</h1>
                <p class="text-gray-600 text-lg border-b pb-4">
                    Formulir multi-langkah ini wajib diisi untuk mendapatkan hasil skoring yang akurat dan valid.
                </p>
            </header>

            {{--- PROGRESS BAR ELEGAN & SISTEMATIS ---}}
            <div class="mb-10">
                <div class="text-sm font-semibold text-sky-600 mb-2 flex justify-between">
                    <span>Langkah <span id="current-step-display">1</span> dari <span id="total-steps-display">{{ count($questions) + 2 }}</span></span>
                    <span id="progress-percent">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div id="progress-bar" class="progress-bar-inner bg-sky-600 h-3 rounded-full shadow-lg" style="width: 0%"></div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-lg animate-pulse" role="alert">
                    <strong class="font-bold">🚨 Perhatian, Data Kurang Lengkap!</strong>
                    <span class="block sm:inline">Harap lengkapi semua isian (termasuk Nama, Kelas, dan jawaban pertanyaan) sebelum mengirimkan formulir.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('form.submit') }}" id="scoring-form">
                @csrf
                
                {{--- STEP 1: DATA DIRI ---}}
                <div class="form-step active" data-step="1">
                    <h2 class="text-2xl font-bold text-sky-700 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Langkah 1: Informasi Personal
                    </h2>
                    <div class="step-form-layout">
                        <div class="w-full space-y-6 p-8 bg-sky-50 rounded-xl border-l-4 border-sky-500 shadow-xl">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required
                                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-inner focus:border-sky-500 focus:ring-sky-500 p-3 transition" placeholder="Masukkan Nama Lengkap Anda">
                            </div>
                            <div>
                                <label for="class_level" class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                                <select name="class_level" id="class_level" required
                                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-inner focus:border-sky-500 focus:ring-sky-500 p-3 appearance-none transition">
                                    <option value="">-- Pilih Kelas Anda --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class }}">{{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        {{--- KOTAK NAVIGASI UNTUK LANGKAH 1 ---}}
                        <div class="sticky-nav flex flex-col items-end justify-between p-6 bg-gray-100 rounded-xl shadow-xl border border-gray-200">
                            <div class="w-full mb-auto">
                                <p class="text-base font-medium text-gray-700 mb-6 text-left w-full border-b pb-3">Setelah mengisi data, klik tombol di bawah untuk memulai kuesioner.</p>
                            </div>
                            
                            <div class="w-full space-y-3 mt-auto">
                                <button type="button" onclick="navigateStep(1)" class="w-full px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition duration-200 transform hover:scale-[1.02] shadow-lg flex items-center justify-center gap-2">
                                    Mulai Pertanyaan <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                                
                                {{-- --- QUICK EXIT BUTTON (Keluar Cepat) --- --}}
                                <div class="pt-4 border-t border-gray-300">
                                    <button onclick="window.location.href='/'" 
                                        class="w-full px-4 py-2 text-red-600 border border-red-300 bg-white rounded-xl text-sm hover:bg-red-50 transition duration-150 shadow-md">
                                        Batalkan / Keluar Cepat
                                    </button>
                                </div>
                                {{-- --- END QUICK EXIT BUTTON --- --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{--- LOOP PERTANYAAN (MULAI DARI STEP 2) ---}}
                @foreach($questions as $question)
                    <div class="form-step" data-step="{{ $question->order + 1 }}">
                        <h2 class="text-xl font-extrabold text-gray-800 mb-4 flex items-center border-b pb-2">
                            <span class="bg-sky-600 text-white rounded-full h-7 w-7 flex items-center justify-center text-sm font-extrabold mr-3 shadow-md">{{ $question->order }}</span>
                            {{ $question->content }}
                            <span class="ml-4 text-sm font-semibold text-gray-500">({{ strtoupper($question->type) }})</span>
                        </h2>
                        
                        <div class="step-form-layout">
                            <div class="w-full p-0">
                                
                                {{-- OPSI JAWABAN (RADIO BUTTONS ELEGANT) --}}
                                <div class="space-y-4">
                                    @foreach($question->options as $option)
                                        <label class="question-option flex justify-between p-4 rounded-xl border border-gray-300 shadow-md cursor-pointer hover:bg-sky-50 has-[:checked]:bg-sky-100 has-[:checked]:border-sky-500">
                                            <input type="radio" 
                                                    name="answers[{{ $question->id }}]" 
                                                    value="{{ $option->id }}" 
                                                    required 
                                                    class="text-sky-600 border-gray-400 focus:ring-sky-500">
                                            
                                            <div class="content-wrapper flex items-start space-x-4">
                                                <span class="text-base font-medium text-gray-800 leading-relaxed">{{ $option->text }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            {{--- KOTAK NAVIGASI UNTUK PERTANYAAN ---}}
                            <div class="sticky-nav flex flex-col items-end justify-between p-6 bg-gray-100 rounded-xl shadow-xl border border-gray-200 h-full min-h-[250px]">
                                <div class="w-full mb-auto">
                                    <p class="text-base font-semibold text-gray-700 mb-3">Kontrol Langkah</p>
                                    <p class="text-sm text-gray-600 italic border-b pb-3">Langkah: {{ $question->order + 1 }} dari {{ count($questions) + 2 }}</p>
                                </div>
                                
                                <div class="w-full space-y-3 mt-auto">
                                    <button type="button" onclick="navigateStep(-1)" class="w-full px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-400 transition duration-200 shadow-md flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Sebelumnya
                                    </button>
                                    
                                    {{--- Tombol Lanjut / Finalisasi ---}}
                                    <button type="button" onclick="navigateStep(1)" 
                                            class="w-full font-bold py-3 px-4 rounded-xl transition duration-200 shadow-xl 
                                                    {{ $question->order < count($questions) ? 'bg-sky-600 hover:bg-sky-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }} flex items-center justify-center gap-1">
                                        {{ $question->order < count($questions) ? 'Lanjut →' : 'Finalisasi Skoring →' }}
                                    </button>
                                    
                                    <div class="pt-4 border-t border-gray-300">
                                        <button onclick="window.location.href='/'" 
                                            class="w-full px-4 py-2 text-red-600 border border-red-300 bg-white rounded-xl text-sm hover:bg-red-50 transition duration-150 shadow-md">
                                            Batalkan / Keluar Cepat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                {{--- STEP TERAKHIR (HALAMAN SUBMIT) ---}}
                <div class="form-step" data-step="{{ count($questions) + 2 }}">
                    <h2 class="text-2xl font-bold text-green-700 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Langkah Terakhir: Selesai!
                    </h2>
                    
                    <div class="p-8 bg-green-50 rounded-xl border-l-4 border-green-500 shadow-lg">
                        <p class="text-xl text-gray-700 mb-8 leading-relaxed font-medium">✨ Terima kasih telah melengkapi semua pertanyaan. Data Anda siap diproses. Tekan tombol di bawah untuk melihat hasil analisis dan rekomendasi Anda secara instan.</p>
                        <div class="w-full space-y-3">
                            <button type="button" onclick="navigateStep(-1)" class="w-full px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-400 transition duration-200 shadow-md flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Koreksi Jawaban
                            </button>
                            {{-- TOMBOL KRITIS: TYPE HARUS SUBMIT! --}}
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-xl mt-4 transform hover:scale-[1.01] flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Lihat Hasil Skoring Final →
                            </button>
                            
                            {{-- --- QUICK EXIT BUTTON (Di halaman final) --- --}}
                            <div class="pt-4 border-t border-gray-300">
                                <button onclick="window.location.href='/'" 
                                    class="w-full px-4 py-2 text-red-600 border border-red-300 bg-white rounded-xl text-sm hover:bg-red-50 transition duration-150 shadow-md">
                                    Keluar ke Beranda
                                </button>
                            </div>
                            {{-- --- END QUICK EXIT BUTTON --- --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{--- LOGIKA JAVASCRIPT NAVIGASI STEP (PERBAIKAN SCROLL) ---}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentStepIndex = 1;
            const totalQuestions = {{ count($questions) }};
            const totalSteps = totalQuestions + 2; // Data Diri (1) + Pertanyaan (N) + Halaman Submit (1)
            const progressBar = document.getElementById('progress-bar');
            const currentStepDisplay = document.getElementById('current-step-display');
            const progressPercent = document.getElementById('progress-percent');

            // --- FUNGSI UTILITY ---
            
            // 1. Logic untuk pre-fill data diri dari local storage
            const storedData = localStorage.getItem('collectedData');
            if (storedData) {
                try {
                    const data = JSON.parse(storedData);
                    if (document.getElementById('name')) document.getElementById('name').value = data.name || '';
                    if (document.getElementById('class_level')) document.getElementById('class_level').value = data.class_level || '';
                    localStorage.removeItem('collectedData'); 
                } catch (e) {
                    console.error("Error parsing stored data:", e);
                }
            }
            
            // 2. Update Progress Bar
            function updateProgressBar(step) {
                const completedSteps = step - 1; 
                const percentage = Math.round((completedSteps / (totalSteps - 1)) * 100);
                
                progressBar.style.width = percentage + '%';
                currentStepDisplay.textContent = step;
                progressPercent.textContent = percentage + '%';
            }

            // 3. Tampilkan Step (Scroll ke atas HANYA saat pindah step)
            function showStep(step) {
                document.querySelectorAll('.form-step').forEach(el => {
                    el.classList.remove('active');
                });
                const stepElement = document.querySelector(`.form-step[data-step="${step}"]`);
                if (stepElement) {
                    stepElement.classList.add('active');
                    currentStepIndex = step;
                    updateProgressBar(step);
                    // Scroll ke atas hanya saat pindah step (pencet tombol Lanjut/Sebelumnya)
                    window.scrollTo({ top: 0, behavior: 'smooth' }); 
                }
            }
            
            // 4. Validasi Step Saat Ini (Kritis)
            function validateCurrentStep() {
                const activeStep = document.querySelector('.form-step.active');
                if (!activeStep) return true;

                if (currentStepIndex === 1) {
                    const name = document.getElementById('name').value;
                    const classLevel = document.getElementById('class_level').value;
                    return name.trim() !== '' && classLevel.trim() !== ''; 
                } else if (currentStepIndex > 1 && currentStepIndex <= totalSteps - 1) {
                    // Cek radio button pada step pertanyaan
                    const radioInput = activeStep.querySelector(`input[name^="answers"]:checked`);
                    return radioInput !== null;
                }
                return true; 
            }
            
            // 5. Fungsi Navigasi Utama
            window.navigateStep = function(direction) {
                const nextStep = currentStepIndex + direction;
                
                // Validasi hanya saat melangkah maju (direction === 1)
                if (direction === 1 && currentStepIndex <= totalSteps - 1 && !validateCurrentStep()) {
                    alert('⚠️ Harap lengkapi isian pada langkah ini sebelum melanjutkan.');
                    return;
                }
                
                // Jika sedang di langkah terakhir pertanyaan dan menekan Lanjut/Finalisasi
                if (direction === 1 && currentStepIndex === totalSteps - 1) {
                    showStep(totalSteps); // Pindah ke halaman 'Selesai!'
                    return; 
                }
                
                // Navigasi normal (mundur atau maju ke pertanyaan sebelum final)
                if (nextStep >= 1 && nextStep <= totalSteps) {
                    showStep(nextStep);
                }
            }

            // 6. Listener untuk mencegah scroll saat memilih Radio Button (Sudah dioptimalkan)
            document.querySelectorAll('.question-option input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Logic di sini sudah memastikan radio terpilih, tidak perlu panggil navigateStep
                });
            });


            // Inisialisasi: Tampilkan langkah pertama
            showStep(1);
        });
    </script>
</body>
</html>