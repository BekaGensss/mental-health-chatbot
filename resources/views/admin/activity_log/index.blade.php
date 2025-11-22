<x-app-layout>
    <x-slot name="header">
        {{-- Header disamakan: font-extrabold, border-l-4 Cyan --}}
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight border-l-4 border-cyan-500 pl-4 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Log Aktivitas Admin --}}
            <svg class="w-6 h-6 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ __('Log Aktivitas Admin') }}
        </h2>
    </x-slot>

    {{-- Layout dan Background Konsisten dengan Dashboard --}}
    <div class="py-6 sm:py-10 bg-gray-50/70">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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
                    
                    {{-- Heading Utama dan Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-2 border-cyan-100 pb-3 mb-6 space-y-4 sm:space-y-0">
                        <h3 class="font-extrabold text-3xl text-gray-800 flex items-center">
                            <i class="fas fa-history mr-2 text-cyan-600"></i> Riwayat Perubahan Sistem
                        </h3>
                        
                        @if(!$logs->isEmpty())
                            <form method="POST" action="{{ route('admin.log_aktivitas.clear') }}" onsubmit="return confirm('⚠️ PERINGATAN! Anda yakin ingin menghapus SEMUA riwayat log? Tindakan ini TIDAK DAPAT DIBATALKAN.')">
                                @csrf
                                {{-- Tombol Hapus Log (Aksen Merah) --}}
                                <button type="submit" class="inline-flex items-center px-5 py-2 bg-red-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider shadow-lg shadow-red-500/50 hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/50 transition duration-300 transform hover:scale-[1.02]">
                                    <i class="fas fa-trash-alt mr-2"></i> Hapus Semua Log
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($logs->isEmpty())
                        {{-- Konten Kosong (Warna Cyan) --}}
                        <div class="p-10 text-center text-gray-500 border-4 border-dashed border-cyan-300 rounded-xl bg-cyan-50">
                            <i class="fas fa-exclamation-circle text-4xl mb-3 text-cyan-600"></i>
                            <p class="font-extrabold text-lg text-gray-700">Belum ada aktivitas yang dicatat.</p>
                        </div>
                    @else
                        {{-- Tabel Log (Diperindah) --}}
                        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-cyan-600 text-white">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold uppercase tracking-wider">Waktu</th>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold uppercase tracking-wider">Admin</th>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold uppercase tracking-wider">Aktivitas</th>
                                        <th class="py-3 px-6 text-left text-sm font-extrabold uppercase tracking-wider">Detail Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($logs as $log)
                                        <tr class="hover:bg-cyan-50/50 transition duration-150">
                                            <td class="py-4 px-6 text-sm text-gray-600 font-mono">
                                                {{ $log->created_at->format('d M Y H:i:s') }}
                                            </td>
                                            <td class="py-4 px-6 font-bold text-gray-900 flex items-center space-x-2">
                                                <span>
                                                    <i class="fas fa-user-shield mr-1 text-cyan-500"></i> {{ $log->user ? $log->user->name : 'System' }}
                                                </span>
                                                
                                                {{-- BADGE TEKS "1st" untuk Admin Utama --}}
                                                @if($log->user && $log->user->id == 1)
                                                    <span class="px-2 py-0.5 text-xs font-extrabold text-yellow-900 bg-yellow-300 rounded-full shadow-md whitespace-nowrap">
                                                        1st
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                @php
                                                    $color = match($log->activity_type) {
                                                        'CREATE' => 'bg-green-100 text-green-800 border-green-300',
                                                        'UPDATE' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                        'DELETE' => 'bg-red-100 text-red-800 border-red-300',
                                                        default => 'bg-blue-100 text-blue-800 border-blue-300',
                                                    };
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $color }}">
                                                    {{ $log->activity_type }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-700">
                                                {{ $log->description }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{-- Pagination --}}
                            {{ $logs->links('pagination::tailwind') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>