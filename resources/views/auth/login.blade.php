<x-guest-layout>

    {{-- Custom CSS untuk Animasi (Jika tidak menggunakan plugin @keyframes Tailwind) --}}
    <style>
        @keyframes slideDown {
            0% { transform: translateY(-100%); opacity: 0; }
            5% { transform: translateY(0); opacity: 1; }
            95% { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(-100%); opacity: 0; }
        }
        .animate-slide-down {
            animation: slideDown 5s ease-in-out forwards;
        }
    </style>

    {{-- Partikel Biru Muda sebagai Background --}}
    <div id="particles-js" class="fixed inset-0 w-full h-full z-10 bg-blue-100"></div> 
    
    {{-- PESAN NOTIFIKASI SELAMAT DATANG YANG PREMIUM --}}
    <div id="welcome-notification" 
         class="fixed top-0 left-0 right-0 z-50 p-4 transform -translate-y-full opacity-0"> 
        <div class="max-w-sm mx-auto rounded-xl shadow-2xl shadow-blue-500/50 overflow-hidden transition duration-300
                    bg-gradient-to-r from-indigo-600 to-blue-500">
            <div class="flex items-start p-4">
                {{-- MODIFIKASI: Mengganti ikon tangan menjadi ikon Lonceng/Notifikasi --}}
                <svg class="w-6 h-6 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.42 5.397 6 7.778 6 11v3.159c0 .538-.214 1.055-.595 1.405L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <div class="ml-3 text-base font-semibold text-white">
                    <span class="block text-lg font-extrabold -mt-1">Selamat Datang!</span> 
                    <span class="block text-xs font-normal opacity-90">Anda berhasil mengakses Portal Admin.</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Konten Utama (Header dan Form Langsung) --}}
    <div class="min-h-screen flex items-center justify-center relative z-20 p-4"> 
        
        {{-- Pembungkus Konten - Frosted Glass Semi-Gelap --}}
        <div class="w-full max-w-sm p-6 sm:p-8 backdrop-blur-md bg-gray-900/60 rounded-2xl shadow-2xl shadow-gray-900/50">
            
            {{-- Header Logo dan Judul --}}
            <div class="flex flex-col items-center mb-8">
                <div class="flex items-center space-x-4 mb-3">
                    <img src="{{ asset('images/mts.jpg') }}" alt="Logo MTs Al-Wahab" 
                        class="h-14 w-14 object-contain rounded-xl shadow-md ring-2 ring-white/30">
                    <img src="{{ asset('images/unm.png') }}" alt="Logo Universitas" 
                        class="h-14 w-14 object-contain rounded-xl shadow-md ring-2 ring-white/30">
                </div>
                
                <h1 class="text-2xl font-extrabold text-white tracking-tight text-center mt-1">
                    MTs Al-Wahab
                </h1>
                <p class="text-sm text-blue-300 font-medium mt-1 text-center">Monitoring Kesehatan Mental</p>
            </div>
            
            <x-auth-session-status class="mb-4 text-center text-green-400" :status="session('status')" />

            {{-- Form Login --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4"> 
                @csrf

                {{-- Input Email --}}
                <div>
                    <x-input-label for="email" value="Alamat Email" class="text-xs font-semibold text-gray-200 mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                        </div>
                        <x-text-input id="email" 
                            class="block w-full ps-9 p-2.5 border-gray-700 focus:border-blue-500 focus:ring-blue-500 rounded-lg bg-gray-800/80 text-sm text-white placeholder-gray-400" 
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-400 text-xs" />
                </div>

                {{-- Input Password (Dengan Tombol Show/Hide) --}}
                <div>
                    <x-input-label for="password" value="Kata Sandi" class="text-xs font-semibold text-gray-200 mb-1" />
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        </div>
                        <x-text-input id="password" 
                            class="block w-full ps-9 pe-10 p-2.5 border-gray-700 focus:border-blue-500 focus:ring-blue-500 rounded-lg bg-gray-800/80 text-sm text-white placeholder-gray-400"
                            type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi Anda" />
                        
                        <div id="togglePassword" class="absolute inset-y-0 end-0 flex items-center pe-3 cursor-pointer">
                            <svg class="w-4 h-4 text-gray-400 hover:text-white transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path id="eyeOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path id="eyeClosed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>

                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-400 text-xs" />
                </div>

                {{-- Ingat Saya & Lupa Password --}}
                <div class="flex items-center justify-between mt-5">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" 
                            class="rounded border-gray-700 text-blue-500 shadow-sm focus:ring-blue-500 bg-gray-800 h-4 w-4">
                        <span class="ms-2 text-xs text-gray-200 select-none">{{ __('Ingat Saya') }}</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a class="text-xs font-medium text-blue-400 hover:text-blue-200 transition duration-150" 
                            href="{{ route('password.request') }}">
                            {{ __('Lupa Password?') }}
                        </a>
                    @endif
                </div>

                {{-- Tombol Masuk --}}
                <div class="pt-4">
                    <x-primary-button class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/50 
                                            text-white font-bold text-base py-2.5 rounded-xl shadow-lg shadow-blue-500/30 
                                            transition duration-300 transform hover:scale-[1.005] active:scale-[0.99]">
                        {{ __('MASUK') }}
                    </x-primary-button>
                </div>
            </form>
            
        </div>
    </div>
    
    {{-- SCRIPT PARTICLES.JS & NOTIFICATION --}}
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        // FUNGSI NOTIFIKASI SELAMAT DATANG
        document.addEventListener('DOMContentLoaded', () => {
            const notification = document.getElementById('welcome-notification');
            
            // Tampilkan notifikasi dengan animasi slide down
            if (notification) {
                // Hapus translate-y-full dan opacity-0
                notification.classList.remove('transform', '-translate-y-full', 'opacity-0');
                
                // Tambahkan kelas animasi (menggunakan CSS @keyframes yang ditambahkan di <style>)
                notification.classList.add('animate-slide-down');
                
                // Hapus notifikasi setelah 5 detik (waktu yang sama dengan durasi animasi)
                setTimeout(() => {
                    notification.classList.remove('animate-slide-down');
                    notification.classList.add('transform', '-translate-y-full', 'opacity-0', 'transition', 'duration-500');
                }, 4800); // 4.8 detik sebelum mulai menghilang
            }
        });
        
        // FUNGSI TOGGLE PASSWORD
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Simple visual toggle
            const eyeOpen = this.querySelector('#eyeOpen');
            const eyeClosed = this.querySelector('#eyeClosed');

            if (type === 'text') {
                eyeOpen.setAttribute('d', 'M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
                eyeClosed.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
            } else {
                eyeOpen.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
                eyeClosed.setAttribute('d', 'M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
            }
        });

        // Konfigurasi Partikel (Warna Biru Cerah/Blue 300)
        particlesJS('particles-js', {
            "particles": {
                "number": { "value": 120, "density": { "enable": true, "value_area": 1000 } }, 
                "color": { "value": "#60a5fa" }, 
                "shape": { "type": "circle" },
                "opacity": { "value": 1, "random": true, "anim": { "enable": true, "speed": 1, "opacity_min": 0.4, "sync": false } }, 
                "size": { "value": 5, "random": true, "anim": { "enable": true, "speed": 40, "size_min": 0.1, "sync": false } },
                "line_linked": { "enable": true, "distance": 150, "color": "#93c5fd", "opacity": 0.7, "width": 1.5 }, 
                "move": { "enable": true, "speed": 5, "direction": "none", "random": true, "straight": false, "out_mode": "out", "bounce": false }
            },
            "interactivity": { 
                "detect_on": "canvas", 
                "events": { 
                    "onhover": { "enable": true, "mode": "grab" },
                    "onclick": { "enable": true, "mode": "push" },
                    "resize": true
                },
                "modes": {
                    "grab": { "distance": 180, "line_linked": { "opacity": 1 } },
                    "push": { "particles_number": 4 }
                }
            },
            "retina_detect": true
        });
    </script>
</x-guest-layout>