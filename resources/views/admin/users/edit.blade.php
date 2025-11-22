<x-app-layout>
    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Edit Akun Admin --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            {{ __('Edit Akun Admin') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-6 sm:py-10 bg-gray-50/70">
        {{-- Lebar Kontainer Tetap --}}
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Card Kontainer Utama (Shadow elegan, rounded 3xl, Ring Cyan) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                <div class="p-6 sm:p-8 text-gray-900">
                    
                    {{-- Judul Utama dengan Gaya Konsisten --}}
                    <h3 class="font-extrabold text-3xl mb-6 text-gray-800 border-b-2 border-cyan-100 pb-3 flex items-center">
                        <i class="fas fa-user-edit mr-2 text-cyan-600"></i> Edit Akun: <span class="ml-1 text-cyan-600">{{ $user->name }}</span>
                    </h3>

                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        
                        {{-- Grid 2 Kolom untuk Nama & Email --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">

                            {{-- Kolom Kiri: Nama --}}
                            <div>
                                <label for="name" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-id-badge mr-2 text-cyan-600"></i> Nama Lengkap
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus
                                        class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 
                                               focus:border-cyan-500 focus:ring-cyan-500 transition duration-150">
                                @error('name') <p class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>

                            {{-- Kolom Kanan: Email --}}
                            <div>
                                <label for="email" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-at mr-2 text-cyan-600"></i> Email
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                        class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 
                                               focus:border-cyan-500 focus:ring-cyan-500 transition duration-150">
                                @error('email') <p class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        {{-- Ganti Password Section (Cyan Accent) --}}
                        <div class="border-t border-gray-200 pt-6 mt-6 p-4 bg-cyan-50 rounded-xl shadow-inner">
                            <h4 class="font-extrabold text-lg text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-lock mr-2 text-red-600"></i> Ganti Password (Opsional)
                            </h4>
                            <p class="text-sm text-gray-600 mb-4">Kosongkan kolom di bawah jika Anda tidak ingin mengganti password saat ini.</p>
                            
                            {{-- Grid 2 Kolom untuk Password --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                
                                {{-- 3. Password Baru --}}
                                <div>
                                    <label for="password" class="block font-medium text-sm text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" name="password" id="password" autocomplete="new-password"
                                            class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm p-2.5 
                                                   focus:border-cyan-500 focus:ring-cyan-500 transition duration-150">
                                    @error('password') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>

                                {{-- 4. Konfirmasi Password --}}
                                <div>
                                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm p-2.5 
                                                   focus:border-cyan-500 focus:ring-cyan-500 transition duration-150">
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                            {{-- Tombol Batal --}}
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-5 py-2 bg-white border border-gray-300 rounded-xl font-bold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition duration-300 transform hover:scale-[1.02] shadow-md">
                                <i class="fas fa-times-circle mr-2"></i> Batal
                            </a>
                            
                            {{-- Tombol Simpan (Green accent) --}}
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-green-500/50 hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-500/50 transition duration-300 transform hover:scale-[1.02]">
                                <i class="fas fa-save mr-2"></i> Perbarui Akun
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>