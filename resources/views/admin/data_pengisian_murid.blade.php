<x-app-layout>
    <x-slot name="header">
        {{-- Header modern dengan aksen Cyan Elektrik (Disamakan) --}}
        {{-- Perbaikan Responsif: text-2xl di mobile, sm:text-4xl di layar besar --}}
        <h2 class="font-extrabold text-2xl sm:text-4xl text-gray-800 leading-snug border-l-4 border-cyan-500 pl-5 py-2 flex items-center">
            {{-- ICON MODERN (SVG Monokrom) untuk Data Pengisian Murid --}}
            <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2 text-cyan-500 transform -translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            {{ __('Data Pengisian Murid') }}
        </h2>
    </x-slot>

    {{-- Latar belakang netral yang lebih cerah --}}
    <div class="py-12 bg-gray-50/70">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Sukses (Warna Cyan) --}}
            @if(session('success'))
                <div class="bg-cyan-50 border-l-4 border-cyan-500 text-cyan-700 p-5 rounded-xl shadow-lg mb-8 transition duration-300" role="alert">
                    <div class="flex items-center">
                        <svg class="w-7 h-7 mr-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-bold text-lg">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Kartu Kontainer Utama (Shadow elegan, rounded 3xl) --}}
            <div class="bg-white overflow-hidden shadow-2xl shadow-gray-200 ring-4 ring-cyan-50 sm:rounded-3xl">
                <div class="p-6 sm:p-10 text-gray-900">
                    
                    {{-- Konten Kosong --}}
                    @if($dataMurid->isEmpty())
                        <div class="p-12 text-center text-gray-500 bg-gray-50 border-4 border-dashed border-cyan-300 rounded-2xl transition duration-300">
                            {{-- SVG monokrom (File / Dokumen kosong) --}}
                            <svg class="w-16 h-16 mx-auto mb-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="font-extrabold text-2xl text-gray-700 mb-3">Data Kosong</p>
                            <p class="text-lg mt-2 text-gray-500">Data pengisian formulir dari murid akan muncul di sini setelah mereka menyelesaikannya.</p>
                        </div>
                    @else
                        
                        {{-- TABEL VIEW (Desktop/Tablet) --}}
                        <div class="overflow-x-auto relative shadow-xl sm:rounded-2xl border border-gray-100 hidden sm:block">
                            <table class="min-w-full text-base text-left text-gray-700 divide-y divide-gray-200">
                                {{-- Header Tabel (Aksen Cyan Elektrik, font bold dan besar) --}}
                                <thead class="text-sm text-white uppercase bg-cyan-600 font-bold tracking-wider">
                                    <tr>
                                        <th scope="col" class="py-4 px-6 rounded-tl-2xl">No.</th>
                                        <th scope="col" class="py-4 px-6">Nama Murid</th>
                                        <th scope="col" class="py-4 px-6">Kelas</th>
                                        <th scope="col" class="py-4 px-6">Ringkasan Hasil</th>
                                        <th scope="col" class="py-4 px-6">Tanggal Isi</th>
                                        <th scope="col" class="py-4 px-6 text-center rounded-tr-2xl">Aksi</th>
                                    </tr>
                                </thead>
                                
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($dataMurid as $data)
                                    <tr class="hover:bg-cyan-50/70 transition duration-300 ease-in-out">
                                        {{-- Penomoran yang sudah benar untuk Pagination --}}
                                        <td class="py-4 px-6 font-semibold text-gray-500">{{ $loop->iteration + ($dataMurid->perPage() * ($dataMurid->currentPage() - 1)) }}</td>
                                        <th scope="row" class="py-4 px-6 font-extrabold text-gray-900 whitespace-nowrap">
                                            {{ $data->name }}
                                        </th>
                                        <td class="py-4 px-6 font-bold text-lg text-cyan-600 whitespace-nowrap">{{ $data->class_level }}</td>
                                        <td class="py-4 px-6 text-gray-600 italic">{{ $data->result_summary ?? 'Belum terhitung' }}</td>
                                        <td class="py-4 px-6 text-gray-500 font-mono text-xs">{{ $data->created_at->format('d M Y H:i') }}</td>
                                        
                                        {{-- Kolom Aksi (Desktop/Tablet) --}}
                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            <div class="flex flex-row space-x-3 justify-center items-center">
                                                {{-- Tombol Detail (Cyan) --}}
                                                <a href="{{ route('admin.student_data_detail', $data->id) }}" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-bold rounded-xl text-white bg-cyan-600 hover:bg-cyan-700 transition duration-300 shadow-lg shadow-cyan-500/50 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-cyan-500/50">
                                                    Detail
                                                </a>
                                                
                                                {{-- Tombol Hapus (Red/Outlined) --}}
                                                <form method="POST" action="{{ route('admin.student_data.destroy', $data->id) }}" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('⚠️ Anda yakin ingin menghapus data {{ $data->name }}? Tindakan ini tidak dapat dibatalkan.')" class="inline-flex items-center justify-center px-5 py-2 border border-red-300 text-sm font-bold rounded-xl text-red-600 bg-white hover:bg-red-50 transition duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-red-500/50">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- LIST VIEW (Mobile View) --}}
                        <div class="sm:hidden space-y-4">
                            @foreach($dataMurid as $data)
                                <div class="bg-white p-5 rounded-2xl shadow-xl border border-gray-100 transition duration-300 hover:ring-2 hover:ring-cyan-200">
                                    <div class="flex justify-between items-start border-b border-gray-100 pb-3 mb-3">
                                        <div class="font-extrabold text-gray-900 text-xl">{{ $data->name }}</div>
                                        <div class="font-bold text-lg text-cyan-600 bg-cyan-50 px-3 py-1 rounded-full whitespace-nowrap">{{ $data->class_level }}</div>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm">
                                        <p class="text-gray-600">
                                            <span class="font-semibold text-gray-800">No. Urut:</span> {{ $loop->iteration + ($dataMurid->perPage() * ($dataMurid->currentPage() - 1)) }}
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-semibold text-gray-800">Ringkasan:</span> <span class="italic">{{ $data->result_summary ?? 'Belum terhitung' }}</span>
                                        </p>
                                        <p class="text-gray-500 font-mono text-xs">
                                            <span class="font-semibold text-gray-800">Tanggal Isi:</span> {{ $data->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>

                                    <div class="mt-4 flex flex-col space-y-2">
                                         {{-- Tombol Detail (Warna Penuh) --}}
                                        <a href="{{ route('admin.student_data_detail', $data->id) }}" class="inline-flex w-full items-center justify-center px-4 py-2 text-sm font-bold rounded-xl text-white bg-cyan-600 hover:bg-cyan-700 transition duration-300 shadow-md">
                                            Lihat Detail
                                        </a>
                                         {{-- Tombol Hapus (Outlined) --}}
                                        <form method="POST" action="{{ route('admin.student_data.destroy', $data->id) }}" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('⚠️ Anda yakin ingin menghapus data {{ $data->name }}? Tindakan ini tidak dapat dibatalkan.')" class="inline-flex items-center justify-center w-full px-4 py-2 border border-red-300 text-sm font-bold rounded-xl text-red-600 bg-white hover:bg-red-50 transition duration-300 shadow-sm">
                                                Hapus Data
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        {{-- Pagination --}}
                        <div class="mt-10 p-5 bg-white rounded-xl shadow-lg border border-gray-100">
                            {{-- MENGGANTI ke template pagination angka penuh --}}
                            {{ $dataMurid->links('pagination::tailwind') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>