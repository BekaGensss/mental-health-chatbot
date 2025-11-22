<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-xl shadow-gray-200/50 sticky top-0 z-30 transition duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo dan Nama Aplikasi --}}
            <div class="flex items-center space-x-4">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-extrabold text-gray-800 hover:text-cyan-600 transition duration-300 tracking-tight">
                        <img src="{{ asset('images/mts.jpg') }}"
                            alt="Logo MTs Al-Wahab"
                            class="block h-8 w-8 object-contain me-3 border border-gray-200 rounded-md shadow-md shadow-gray-200/50 transition duration-300 hover:scale-105" />
                        MTs Al-Wahab
                    </a>
                </div>

                {{-- MENU NAVIGASI UTAMA (Desktop) --}}
                <div class="hidden space-x-2 sm:flex sm:items-center">

                    {{-- Nav Link Dashboard --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                        class="text-sm font-medium px-3 py-2 text-gray-700 hover:text-cyan-600 hover:bg-gray-100/70 rounded-lg transition duration-200
                                                {{ request()->routeIs('dashboard') ? 'border-b-2 border-cyan-500 bg-gray-100/70 font-semibold text-cyan-700' : 'border-b-2 border-transparent' }}">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Dropdown Manajemen Data (Desktop) --}}
                    <div class="relative">
                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-lg text-gray-700 hover:text-cyan-600 hover:bg-gray-100/70 
                                               focus:outline-none transition ease-in-out duration-150 border-b-2 
                                               {{ request()->routeIs(['admin.kelola_pertanyaan', 'admin.data_murid', 'admin.data_pengisian_murid']) ? 'border-cyan-500 bg-gray-100/70 font-semibold text-cyan-700' : 'border-transparent' }}">
                                    
                                    <div class="flex items-center">
                                        {{-- Icon Folder --}}
                                        <svg class="w-4 h-4 me-1 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10h16M4 7l4-4m12 4l-4-4M8 7l4-4m0 0l4 4M8 7h8"></path></svg>
                                        {{ __('Manajemen Data') }}
                                    </div>
                                    {{-- Ikon Chevron Down Tunggal --}}
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                {{-- Kelola Formulir & Data --}}
                                <div class="block px-4 py-2 text-xs font-bold text-cyan-600 border-b border-gray-100 mb-1">
                                    <div class="flex items-center">
                                        {{-- Icon File --}}
                                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        {{ __('Kelola Formulir & Respon') }}
                                    </div>
                                </div>
                                <x-dropdown-link :href="route('admin.kelola_pertanyaan')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Daftar Pertanyaan') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.data_murid')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Data Respon/Jawaban') }}</x-dropdown-link>

                                <div class="border-t border-gray-200 my-1"></div>

                                <x-dropdown-link :href="route('admin.data_pengisian_murid')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 transition duration-150">
                                    <span class="font-bold text-cyan-700">{{ __('Data Pengisian Formulir') }}</span>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Dropdown Konfigurasi Sistem (Desktop) --}}
                    <div class="relative">
                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-lg text-gray-700 hover:text-cyan-600 hover:bg-gray-100/70 
                                               focus:outline-none transition ease-in-out duration-150 border-b-2
                                               {{ request()->routeIs(['admin.kelas.index', 'admin.log_aktivitas', 'admin.users.index', 'admin.users.create']) ? 'border-cyan-500 bg-gray-100/70 font-semibold text-cyan-700' : 'border-transparent' }}">
                                    
                                    <div class="flex items-center">
                                        {{-- Icon Setting --}}
                                        <svg class="w-4 h-4 me-1 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.536.326 1.053.486 1.57.486s1.034-.16 1.57-.486z"></path></svg>
                                        {{ __('Konfigurasi Sistem') }}
                                    </div>
                                    {{-- Ikon Chevron Down Tunggal --}}
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                {{-- Sub-Menu: Pengaturan Sistem --}}
                                <div class="block px-4 py-2 text-xs font-bold text-cyan-600 border-b border-gray-100 mb-1">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.536.326 1.053.486 1.57.486s1.034-.16 1.57-.486z"></path></svg>
                                        {{ __('Pengaturan Sistem') }}
                                    </div>
                                </div>
                                <x-dropdown-link :href="route('admin.kelas.index')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Kelola Kelas') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('admin.log_aktivitas')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150">{{ __('Log Aktivitas') }}</x-dropdown-link>

                                <div class="border-t border-gray-200 my-1"></div>

                                {{-- Sub-Menu: Manajemen Akun --}}
                                <div class="block px-4 py-2 text-xs font-bold text-cyan-600 border-b border-gray-100 mb-1">
                                    <div class="flex items-center">
                                        {{-- Icon Users --}}
                                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
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
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center p-1 border border-gray-200/80 bg-white rounded-full text-sm leading-4 font-semibold text-gray-700 hover:text-cyan-900 hover:bg-cyan-50 focus:outline-none transition ease-in-out duration-200 shadow-md">

                            <img class="h-8 w-8 rounded-full object-cover me-2"
                                        src="{{ Auth::user()->profile_photo_url }}"
                                        alt="{{ Auth::user()->name }}">

                            <div class="hidden lg:block">{{ Auth::user()->name }}</div> {{-- Nama hanya tampil di layar besar --}}
                            <div class="ms-2 me-1">
                                <svg class="fill-current h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="block px-4 py-2 text-xs text-gray-500 font-bold border-b border-gray-100">{{ __('AKUN SAYA') }}</div>
                        {{-- PROFIL SAYA DENGAN IKON --}}
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-cyan-50 rounded-lg mx-2 px-3 py-2 text-gray-700 transition duration-150 flex items-center">
                            <svg class="w-4 h-4 me-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-200 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            {{-- LOGOUT DENGAN IKON MERAH --}}
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-red-600 hover:bg-red-50 hover:text-red-800 rounded-lg mx-2 px-3 py-2 transition duration-150 flex items-center">
                                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-10a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile Menu Trigger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Responsive Navigation Menu (Mobile) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200 transition duration-200">

        {{-- Responsive Nav Links --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 hover:bg-cyan-50/70 transition duration-150 font-medium">
                <div class="flex items-center">
                    <svg class="w-5 h-5 me-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    {{ __('Dashboard Utama') }}
                </div>
            </x-responsive-nav-link>

            {{-- DROPDOWN MANAJEMEN DATA MOBILE --}}
            <div class="space-y-1 ps-4 pt-2 border-l-4 border-cyan-500/50">
                <div class="font-bold text-cyan-700 pt-2 flex items-center">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10h16M4 7l4-4m12 4l-4-4M8 7l4-4m0 0l4 4M8 7h8"></path></svg>
                    {{ __('Manajemen Data') }}
                </div>
                <div class="text-xs text-gray-500 font-semibold px-3 pt-1 border-t border-gray-100">{{ __('Formulir & Respon') }}</div>
                <x-responsive-nav-link :href="route('admin.kelola_pertanyaan')" class="text-gray-700 hover:bg-cyan-50/70">{{ __('Daftar Pertanyaan') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.data_murid')" class="text-gray-700 hover:bg-cyan-50/70">{{ __('Data Respon/Jawaban') }}</x-responsive-nav-link>
                <div class="border-t border-gray-100 my-1"></div>
                <x-responsive-nav-link :href="route('admin.data_pengisian_murid')" class="font-bold text-cyan-700 hover:bg-cyan-50/70">{{ __('Data Pengisian Formulir') }}</x-responsive-nav-link>
            </div>

            {{-- DROPDOWN KONFIGURASI SISTEM MOBILE --}}
            <div class="space-y-1 ps-4 pt-4 border-l-4 border-cyan-500/50">
                <div class="font-bold text-cyan-700 pt-2 flex items-center">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.536.326 1.053.486 1.57.486s1.034-.16 1.57-.486z"></path></svg>
                    {{ __('Konfigurasi Sistem') }}
                </div>
                <div class="text-xs text-gray-500 font-semibold px-3 pt-1 border-t border-gray-100">{{ __('Pengaturan Umum') }}</div>
                <x-responsive-nav-link :href="route('admin.kelas.index')" class="text-gray-700 hover:bg-cyan-50/70">{{ __('Kelola Kelas') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.log_aktivitas')" class="text-gray-700 hover:bg-cyan-50/70">{{ __('Log Aktivitas') }}</x-responsive-nav-link>

                <div class="text-xs text-gray-500 font-semibold px-3 pt-3 border-t border-gray-100">{{ __('Manajemen Akun') }}</div>
                <x-responsive-nav-link :href="route('admin.users.index')" class="text-gray-700 hover:bg-cyan-50/70">{{ __('Daftar Pengguna') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.create')" class="text-gray-700 hover:bg-cyan-50/70">{{ __('Tambah Pengguna Baru') }}</x-responsive-nav-link>
            </div>
        </div>

        {{-- Responsive User Info and Logout --}}
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4">
                <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-600">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- PROFIL SAYA DENGAN IKON (MOBILE) --}}
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-700 hover:bg-cyan-50/70 flex items-center">
                    <svg class="w-5 h-5 me-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ __('Profil Saya') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    {{-- LOGOUT DENGAN IKON MERAH (MOBILE) --}}
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-red-600 hover:bg-red-50 hover:text-red-700 flex items-center">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-10a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        {{ __('Keluar (Log Out)') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>