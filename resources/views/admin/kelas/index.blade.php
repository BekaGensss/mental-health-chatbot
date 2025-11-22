<x-app-layout>
    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Kelas --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.83L12 18l-6.16-3.83z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v4"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9 5 9-5"></path>
            </svg>
            {{ __('Kelola Kelas') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-6 sm:py-10 bg-gray-50/70">
        {{-- max-w-4xl tetap untuk fokus pada daftar --}}
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Success (Warna Cyan) --}}
            @if(session('success'))
                <div class="bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700 p-5 rounded-xl mb-8 shadow-lg transition duration-300" role="alert">
                    <div class="flex items-center">
                        <svg class="w-7 h-7 mr-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-lg">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Card Kontainer Utama (Shadow elegan, rounded 3xl) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                <div class="p-6 sm:p-10 text-gray-900">
                    
                    {{-- Heading Utama dan Tombol Aksi (Menggunakan Cyan) --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-2 border-cyan-100 pb-3 mb-6 space-y-4 sm:space-y-0">
                        <h3 class="font-extrabold text-3xl text-gray-800 flex items-center">
                            <i class="fas fa-school mr-2 text-cyan-600"></i> Daftar Kelas
                            {{-- Total Aktif --}}
                            <span class="ml-3 px-3 py-1 text-sm font-bold bg-cyan-100 text-cyan-700 rounded-full shadow-inner whitespace-nowrap">
                                Aktif: {{ $classes->where('is_active', true)->count() }}
                            </span>
                        </h3>
                        {{-- Tombol Tambah Kelas Baru --}}
                        <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center px-5 py-2 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 transition duration-300 transform hover:scale-[1.02]">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Kelas Baru
                        </a>
                    </div>
                    
                    @if($classes->isEmpty())
                        {{-- Konten Kosong (Warna Cyan) --}}
                        <div class="p-10 text-center text-gray-500 border-4 border-dashed border-cyan-300 rounded-xl bg-cyan-50">
                            <i class="fas fa-exclamation-circle text-4xl mb-3 text-cyan-600"></i>
                            <p class="font-extrabold text-lg text-gray-700">Belum ada data kelas yang ditambahkan.</p>
                            <p class="text-md mt-1">Silakan tambahkan kelas pertama Anda untuk mengaktifkan pengisian formulir.</p>
                        </div>
                    @else
                        {{-- Tabel Kelas (Diperindah) --}}
                        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold text-gray-700 uppercase tracking-wider">Nama Kelas</th>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="py-3 px-6 text-right text-sm font-extrabold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($classes as $class)
                                        <tr class="hover:bg-cyan-50/50 transition duration-150">
                                            <td class="py-4 px-6 font-bold text-gray-900 text-base">
                                                <i class="fas fa-layer-group mr-2 text-purple-500"></i>{{ $class->name }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{-- Status Chip --}}
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                    {{ $class->is_active ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                                                    {{ $class->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                                                </span>
                                            </td>
                                            {{-- Kolom Aksi (Responsif) --}}
                                            <td class="py-4 px-6 text-right whitespace-nowrap space-x-3">
                                                <div class="flex justify-end items-center space-x-3">
                                                    {{-- Tombol Edit --}}
                                                    <a href="{{ route('admin.kelas.edit', $class->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-lg text-sm font-bold hover:bg-blue-600 transition duration-150 shadow-md transform hover:scale-[1.05]">
                                                        <i class="fas fa-edit mr-1"></i> Edit
                                                    </a>
                                                    
                                                    {{-- Tombol Hapus --}}
                                                    <form method="POST" action="{{ route('admin.kelas.destroy', $class->id) }}" class="inline" onsubmit="return confirm('⚠️ Anda yakin ingin menghapus kelas {{ $class->name }}? Semua data terkait mungkin akan terpengaruh.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition duration-150 shadow-md transform hover:scale-[1.05]">
                                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
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