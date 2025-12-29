<nav x-data="{ open: false }" class="bg-white border-b border-gray-200/80 shadow-lg shadow-gray-200/50 sticky top-0 z-30 transition duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo dan Nama Aplikasi --}}
            <div class="flex items-center space-x-4">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-extrabold text-gray-800 hover:text-cyan-700 transition duration-300 tracking-tight">
                        <img src="{{ asset('images/mts.jpg') }}"
                            alt="Logo MTs Al-Wahab"
                            class="block h-9 w-9 object-contain me-3 border-2 border-cyan-100 rounded-full shadow-md shadow-cyan-200/50 transition duration-300 hover:scale-105 hover:shadow-lg" />
                        <span class="text-cyan-700">MTs</span> <span class="text-gray-800 ms-1">Al-Wahab</span>
                    </a>
                </div>

                {{-- MENU NAVIGASI UTAMA (Desktop) --}}
                <div class="hidden space-x-1 sm:flex sm:items-center">

                    {{-- Nav Link Dashboard --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                class="text-sm font-medium px-4 py-2 text-gray-700 hover:text-cyan-700 hover:bg-cyan-50/70 rounded-xl transition duration-300 
                                      {{ request()->routeIs('dashboard') ? 'border-b-2 border-cyan-600 bg-cyan-50/70 font-bold text-cyan-700' : 'border-b-2 border-transparent' }}">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Dropdown Manajemen Data --}}
                    <div class="relative">
                        <x-dropdown align="left" width="64" class="rounded-xl shadow-xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 text-sm leading-4 font-medium rounded-xl text-gray-700 hover:text-cyan-700 hover:bg-cyan-50/70 
                                                focus:outline-none transition ease-in-out duration-300 border-b-2 
                                                {{ request()->routeIs(['admin.kelola_pertanyaan', 'admin.data_murid', 'admin.data_pengisian_murid']) ? 'border-cyan-600 bg-cyan-50/70 font-bold text-cyan-700' : 'border-transparent' }}">
                                    
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                        {{ __('Manajemen Data') }}
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs font-bold text-cyan-700 border-b border-cyan-100/80 mb-1 bg-cyan-50/50">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        {{ __('Kelola Formulir & Respon') }}
                                    </div>
                                </div>
                                <x-dropdown-link :href="route('admin.kelola_pertanyaan')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Daftar Pertanyaan') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.data_murid')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Data Respon/Jawaban') }}</x-dropdown-link>

                                <div class="border-t border-gray-200 my-2"></div>

                                <x-dropdown-link :href="route('admin.data_pengisian_murid')" class="hover:bg-cyan-50/70 rounded-lg mx-2 px-3 py-2 transition duration-150 font-bold text-cyan-700">
                                    {{ __('Data Pengisian Formulir') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    {{-- Dropdown Data Mining --}}
                    <div class="relative">
                        @php
                            $isMiningActive = request()->routeIs(['admin.mining.klasifikasi', 'admin.mining.regresi', 'admin.mining.clustering', 'admin.mining.asosiasi', 'admin.mining.sekuensing']);
                        @endphp
                        <x-dropdown align="left" width="72" class="rounded-xl shadow-xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 text-sm leading-4 font-medium rounded-xl text-gray-700 hover:text-cyan-700 hover:bg-cyan-50/70 
                                                focus:outline-none transition ease-in-out duration-300 border-b-2
                                                {{ $isMiningActive ? 'border-cyan-600 bg-cyan-50/70 font-bold text-cyan-700' : 'border-transparent' }}">
                                    
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 10l.94-1.88a2 2 0 011.88-1.06h8.36a2 2 0 011.88 1.06L19 10m-2 4h-2M7 14h2m4-4H9m-2 4H5a2 2 0 00-2 2v2a2 2 0 002 2h14a2 2 0 002-2v-2a2 2 0 00-2-2h-2"></path></svg>
                                        {{ __('Data Mining') }}
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs font-bold text-cyan-700 border-b border-cyan-100/80 mb-1 bg-cyan-50/50">
                                    {{ __('Teknik Analisis Lanjut (Proses)') }}
                                </div>
                                <x-dropdown-link :href="route('admin.mining.klasifikasi')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('1. Klasifikasi (Prediksi Risiko)') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.mining.regresi')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('2. Regresi (Prediksi Nilai Kontinu)') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.mining.clustering')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('3. Clustering (Pengelompokan Profil)') }}</x-dropdown-link>
                                <div class="border-t border-gray-200 my-1"></div>
                                <x-dropdown-link :href="route('admin.mining.asosiasi')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('4. Asosiasi Pola (Hubungan Jawaban)') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.mining.sekuensing')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('5. Deteksi Anomali/Sekuensing') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- --- MENU BARU: KECERDASAN BUATAN (Desktop) --- --}}
                    <div class="relative">
                        <x-dropdown align="left" width="64" class="rounded-xl shadow-xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 text-sm leading-4 font-medium rounded-xl text-gray-700 hover:text-cyan-700 hover:bg-cyan-50/70 
                                                focus:outline-none transition ease-in-out duration-300 border-b-2
                                                {{ request()->routeIs('admin.reports.ai') ? 'border-cyan-600 bg-cyan-50/70 font-bold text-cyan-700' : 'border-transparent' }}">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        {{ __('AI Gemini') }}
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs font-bold text-cyan-700 border-b border-cyan-100/80 mb-1 bg-cyan-50/50 uppercase tracking-widest">
                                    {{ __('Analisis NLP') }}
                                </div>
                                <x-dropdown-link :href="route('admin.reports.ai')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">
                                    {{ __('Hasil Analisis NLP Gemini') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    {{-- Dropdown Konfigurasi Sistem --}}
                    <div class="relative">
                        <x-dropdown align="left" width="64" class="rounded-xl shadow-xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 text-sm leading-4 font-medium rounded-xl text-gray-700 hover:text-cyan-700 hover:bg-cyan-50/70 
                                                focus:outline-none transition ease-in-out duration-300 border-b-2
                                                {{ request()->routeIs(['admin.kelas.index', 'admin.log_aktivitas', 'admin.users.index', 'admin.users.create']) ? 'border-cyan-600 bg-cyan-50/70 font-bold text-cyan-700' : 'border-transparent' }}">
                                    
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.536.326 1.053.486 1.57.486s1.034-.16 1.57-.486z"></path></svg>
                                        {{ __('Konfigurasi Sistem') }}
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs font-bold text-cyan-700 border-b border-cyan-100/80 mb-1 bg-cyan-50/50">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.536.326 1.053.486 1.57.486s1.034-.16 1.57-.486z"></path></svg>
                                        {{ __('Pengaturan Umum') }}
                                    </div>
                                </div>
                                <x-dropdown-link :href="route('admin.kelas.index')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Kelola Kelas') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.log_aktivitas')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Log Aktivitas') }}</x-dropdown-link>

                                <div class="border-t border-gray-200 my-2"></div>

                                <div class="block px-4 py-2 text-xs font-bold text-cyan-700 border-b border-cyan-100/80 mb-1 bg-cyan-50/50">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        {{ __('Manajemen Akun') }}
                                    </div>
                                </div>
                                <x-dropdown-link :href="route('admin.users.index')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Daftar Pengguna') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.users.create')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Tambah Pengguna Baru') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                </div>
            </div>

            {{-- PROFIL PENGGUNA (Desktop) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48" class="rounded-xl shadow-xl">
                    <x-slot name="trigger">
                        <button class="flex items-center p-1 border-2 border-cyan-100 bg-white rounded-full text-sm leading-4 font-semibold text-gray-700 hover:text-cyan-900 hover:bg-cyan-50 focus:outline-none transition ease-in-out duration-300 shadow-md hover:shadow-lg">
                            <img class="h-9 w-9 rounded-full object-cover me-2 border border-gray-100"
                                src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}">
                            <div class="hidden lg:block me-2">{{ Auth::user()->name }}</div>
                            <div class="ms-0 me-1 text-cyan-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="block px-4 py-2 text-xs text-gray-500 font-bold border-b border-gray-100 bg-gray-50/50">{{ __('AKUN SAYA') }}</div>
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150 flex items-center">
                            <svg class="w-4 h-4 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>
                        <div class="border-t border-gray-200 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();" class="text-red-600 hover:bg-red-100 rounded-lg mx-2 px-3 py-2 transition duration-150 flex items-center font-semibold">
                                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-10a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile Menu Trigger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-cyan-700 hover:bg-cyan-50/70 focus:outline-none focus:bg-cyan-100/70 focus:text-cyan-700 transition duration-300 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Responsive Navigation Menu (Mobile) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200 transition duration-300">

        {{-- Responsive Nav Links --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 hover:bg-cyan-50/70 transition duration-150 font-medium">
                <div class="flex items-center">
                    <svg class="w-5 h-5 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    {{ __('Dashboard Utama') }}
                </div>
            </x-responsive-nav-link>

            {{-- DROPDOWN MANAJEMEN DATA MOBILE --}}
            <div class="space-y-1 ps-4 pt-2 border-l-4 border-cyan-600/70 bg-cyan-50/20">
                <div class="font-bold text-cyan-700 pt-2 flex items-center">
                    <svg class="w-5 h-5 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10h16M4 7l4-4m12 4l-4-4M8 7l4-4m0 0l4 4M8 7h8"></path></svg>
                    {{ __('Manajemen Data') }}
                </div>
                <div class="text-xs text-gray-600 font-semibold px-3 pt-1 border-t border-cyan-100/80">{{ __('Formulir & Respon') }}</div>
                <x-responsive-nav-link :href="route('admin.kelola_pertanyaan')" class="text-gray-700 hover:bg-cyan-100">{{ __('Daftar Pertanyaan') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.data_murid')" class="text-gray-700 hover:bg-cyan-100">{{ __('Data Respon/Jawaban') }}</x-responsive-nav-link>
                <div class="border-t border-gray-100 my-1"></div>
                <x-responsive-nav-link :href="route('admin.data_pengisian_murid')" class="font-bold text-cyan-700 hover:bg-cyan-100">{{ __('Data Pengisian Formulir') }}</x-responsive-nav-link>
            </div>
            
            {{-- DROPDOWN DATA MINING MOBILE (PROSES) --}}
            <div class="space-y-1 ps-4 pt-4 border-l-4 border-cyan-600/70 bg-cyan-50/20">
                <div class="font-bold text-cyan-700 pt-2 flex items-center">
                    <svg class="w-5 h-5 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l3-3 3 3v13M9 19c-3.14 0-5 2.14-5 5h10c0-2.86-1.86-5-5-5zM15 19c3.14 0 5 2.14 5 5h-10c0-2.86 1.86-5 5-5z"></path></svg>
                    {{ __('Data Mining & Prediksi') }}
                </div>
                <div class="text-xs text-gray-600 font-semibold px-3 pt-1 border-t border-cyan-100/80">{{ __('Teknik Analisis Lanjut (Proses)') }}</div>
                <x-responsive-nav-link :href="route('admin.mining.klasifikasi')" class="text-gray-700 hover:bg-cyan-100">{{ __('1. Klasifikasi (Prediksi Risiko)') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.mining.regresi')" class="text-gray-700 hover:bg-cyan-100">{{ __('2. Regresi (Prediksi Nilai Kontinu)') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.mining.clustering')" class="text-gray-700 hover:bg-cyan-100">{{ __('3. Clustering (Pengelompokan Profil)') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.mining.asosiasi')" class="text-gray-700 hover:bg-cyan-100">{{ __('4. Asosiasi Pola (Hubungan Jawaban)') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.mining.sekuensing')" class="text-gray-700 hover:bg-cyan-100">{{ __('5. Deteksi Anomali/Sekuensing') }}</x-responsive-nav-link>
            </div>

            {{-- --- MENU BARU: KECERDASAN BUATAN MOBILE --- --}}
            <div class="space-y-1 ps-4 pt-4 border-l-4 border-cyan-600/70 bg-cyan-50/20">
                <div class="font-bold text-cyan-700 pt-2 flex items-center">
                    <svg class="w-5 h-5 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    {{ __('Kecerdasan Buatan') }}
                </div>
                <div class="text-xs text-gray-600 font-semibold px-3 pt-1 border-t border-cyan-100/80">{{ __('Analisis NLP') }}</div>
                <x-responsive-nav-link :href="route('admin.reports.ai')" :active="request()->routeIs('admin.reports.ai')" 
                    class="text-gray-700 hover:bg-cyan-100 transition rounded-xl">
                    {{ __('Hasil Analisis NLP Gemini') }}
                </x-responsive-nav-link>
            </div>
            
            {{-- DROPDOWN KONFIGURASI SISTEM MOBILE --}}
            <div class="space-y-1 ps-4 pt-4 border-l-4 border-cyan-600/70 bg-cyan-50/20">
                <div class="font-bold text-cyan-700 pt-2 flex items-center">
                    <svg class="w-5 h-5 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.536.326 1.053.486 1.57.486s1.034-.16 1.57-.486z"></path></svg>
                    {{ __('Konfigurasi Sistem') }}
                </div>
                <div class="text-xs text-gray-600 font-semibold px-3 pt-1 border-t border-cyan-100/80">{{ __('Pengaturan Umum') }}</div>
                <x-responsive-nav-link :href="route('admin.kelas.index')" class="text-gray-700 hover:bg-cyan-100">{{ __('Kelola Kelas') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.log_aktivitas')" class="text-gray-700 hover:bg-cyan-100">{{ __('Log Aktivitas') }}</x-responsive-nav-link>

                <div class="text-xs text-gray-600 font-semibold px-3 pt-3 border-t border-cyan-100/80">{{ __('Manajemen Akun') }}</div>
                <x-responsive-nav-link :href="route('admin.users.index')" class="text-gray-700 hover:bg-cyan-100">{{ __('Daftar Pengguna') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.create')" class="text-gray-700 hover:bg-cyan-100">{{ __('Tambah Pengguna Baru') }}</x-responsive-nav-link>
            </div>
        </div>

        {{-- Responsive User Info and Logout --}}
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4">
                <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-600">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-700 hover:bg-cyan-50/70 flex items-center">
                    <svg class="w-5 h-5 me-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ __('Profil Saya') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                            this.closest('form').submit();" class="text-red-600 hover:bg-red-100 flex items-center font-semibold">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-10a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        {{ __('Keluar (Log Out)') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>