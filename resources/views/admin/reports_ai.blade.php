<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <i class="fas fa-robot mr-2 text-cyan-600"></i>
            {{ __('Hasil Analisis NLP Gemini AI') }}
        </h2>
    </x-slot>

    {{-- Tambahkan Marked.js untuk merender Markdown (Teks Tebal) --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl p-6 sm:p-8 border border-gray-100">
                
                <div class="mb-8 border-b pb-4">
                    <h3 class="text-2xl font-black text-gray-800">Monitoring Kesehatan Mental Murid</h3>
                    <p class="text-gray-500 text-sm">Daftar murid yang telah dianalisis menggunakan Natural Language Processing (NLP).</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-gray-400 text-xs uppercase tracking-widest">
                                <th class="px-4 py-2">Murid & Kelas</th>
                                <th class="px-4 py-2 text-center">Status Mental</th>
                                <th class="px-4 py-2">Ringkasan Analisis AI</th>
                                <th class="px-4 py-2 text-right">Waktu Pengisian</th>
                                <th class="px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr class="bg-gray-50/50 hover:bg-cyan-50 transition-colors duration-200 rounded-2xl">
                                <td class="px-4 py-4 rounded-l-2xl border-y border-l border-transparent">
                                    <div class="font-bold text-gray-800 text-lg">{{ $student->name }}</div>
                                    <div class="text-xs font-semibold text-cyan-600 uppercase">{{ $student->class_level }}</div>
                                </td>

                                <td class="px-4 py-4 text-center border-y border-transparent">
                                    @php
                                        $statusClasses = [
                                            'Bahaya' => 'bg-red-100 text-red-700 border-red-200',
                                            'Waspada' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'Aman' => 'bg-green-100 text-green-700 border-green-200',
                                        ];
                                        $class = $statusClasses[$student->mental_status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="px-4 py-1 rounded-full text-xs font-black border {{ $class }}">
                                        {{ strtoupper($student->mental_status ?? 'N/A') }}
                                    </span>
                                </td>

                                {{-- Bagian Analisis AI yang diperbaiki --}}
                                <td class="px-4 py-4 border-y border-transparent max-w-md">
                                    <div id="markdown-{{ $student->id }}" class="text-sm text-gray-600 leading-relaxed prose prose-sm markdown-content">
                                        {{-- Teks mentah akan dirender oleh JS di bawah --}}
                                        {{ $student->ai_analysis }}
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-right text-xs font-medium text-gray-400 border-y border-transparent">
                                    {{ $student->created_at->translatedFormat('d M Y') }}
                                    <span class="block text-[10px] opacity-70">{{ $student->created_at->diffForHumans() }}</span>
                                </td>

                                <td class="px-4 py-4 rounded-r-2xl border-y border-r border-transparent text-center">
                                    <a href="{{ route('admin.student_data_detail', $student->id) }}" 
                                       class="inline-flex items-center justify-center p-2 bg-white text-cyan-600 hover:text-white hover:bg-cyan-600 rounded-xl shadow-sm border border-gray-100 transition-all transform hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-folder-open text-gray-200 text-5xl mb-4"></i>
                                        <p class="text-gray-400 font-medium">Belum ada data murid yang dianalisis oleh AI.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk merender Markdown otomatis --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.markdown-content').forEach(function(el) {
                const rawText = el.textContent.trim();
                el.innerHTML = marked.parse(rawText);
            });
        });
    </script>

    {{-- Style tambahan agar render Markdown tidak berantakan di tabel --}}
    <style>
        .markdown-content p { margin-bottom: 0px !important; display: inline; }
        .markdown-content strong { color: #111827; font-weight: 700; }
    </style>
</x-app-layout>