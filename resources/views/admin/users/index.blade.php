<x-app-layout>
    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Akun Admin --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2c0-.656-.126-1.283-.356-1.857M20 10V8a6 6 0 10-12 0v2"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01"></path>
            </svg>
            {{ __('Kelola Akun Admin') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-6 sm:py-10 bg-gray-50/70">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Success dan Error dengan style Cyan/Red yang diperbagus --}}
            @if(session('success'))
                <div class="bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700 p-5 rounded-xl mb-8 shadow-lg transition duration-300" role="alert">
                    <div class="flex items-center">
                        <svg class="w-7 h-7 mr-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-lg">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-5 rounded-xl mb-8 shadow-lg transition duration-300" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle mr-3 text-lg text-red-500"></i>
                        <span class="font-bold text-lg">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- Card Kontainer Utama (Shadow elegan, rounded 3xl) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                <div class="p-6 sm:p-10 text-gray-900">
                    
                    {{-- Heading Utama dan Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-2 border-cyan-100 pb-3 mb-6 space-y-4 sm:space-y-0">
                        <h3 class="font-extrabold text-3xl text-gray-800 flex items-center">
                            <i class="fas fa-user-shield mr-2 text-cyan-600"></i> Daftar Akun Admin
                        </h3>
                        
                        {{-- Tombol Tambah Akun Baru (Cyan) --}}
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-5 py-2 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 transition duration-300 transform hover:scale-[1.02]">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Akun Baru
                        </a>
                    </div>
                    
                    @if($users->isEmpty())
                        {{-- Konten Kosong (Warna Cyan) --}}
                        <div class="p-10 text-center text-gray-500 border-4 border-dashed border-cyan-300 rounded-xl bg-cyan-50">
                            <i class="fas fa-exclamation-circle text-4xl mb-3 text-cyan-600"></i>
                            <p class="font-extrabold text-lg text-gray-700">Belum ada akun admin yang terdaftar.</p>
                        </div>
                    @else
                        {{-- Tabel Admin (Diperindah) --}}
                        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-cyan-600 text-white">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold uppercase tracking-wider">Nama Admin</th>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold uppercase tracking-wider">Email</th>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold uppercase tracking-wider">Terdaftar Sejak</th>
                                        <th class="py-3 px-6 text-right text-sm font-extrabold uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-cyan-50/50 transition duration-150">
                                            <td class="py-4 px-6 font-bold text-gray-900 text-base flex items-center space-x-2">
                                                <i class="fas fa-id-badge mr-2 text-cyan-600"></i> {{ $user->name }}
                                                
                                                {{-- BADGE ANDA --}}
                                                @if(auth()->id() == $user->id)
                                                    <span class="ml-2 px-2 py-0.5 text-xs font-bold rounded-full bg-cyan-600 text-white shadow-md">Anda</span>
                                                @endif

                                                {{-- BADGE ADMIN UTAMA (1st) --}}
                                                @if($user->id == 1)
                                                    <span class="ml-2 px-2 py-0.5 text-xs font-extrabold text-yellow-900 bg-yellow-300 rounded-full shadow-md">
                                                        1st
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-600 font-mono">{{ $user->email }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                                            
                                            {{-- Kolom Aksi --}}
                                            <td class="py-4 px-6 text-right whitespace-nowrap space-x-3">
                                                @php $isDisabled = auth()->id() == $user->id; @endphp
                                                
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-bold transition duration-150 transform hover:scale-[1.05]">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                
                                                {{-- Tombol Hapus (Disabled jika sedang login) --}}
                                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('⚠️ Yakin ingin menghapus akun {{ $user->name }}?')" 
                                                            class="inline-flex items-center text-red-600 hover:text-red-800 text-sm font-bold transition duration-150 {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>