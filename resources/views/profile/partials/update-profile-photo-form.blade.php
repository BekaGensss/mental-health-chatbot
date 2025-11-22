<section class="bg-white p-8 shadow-2xl rounded-3xl border border-gray-100 ring-4 ring-cyan-50">
    <header class="border-b border-cyan-200 pb-4 mb-6">
        <h2 class="font-extrabold text-3xl text-gray-800 flex items-center">
            <i class="fas fa-camera mr-3 text-cyan-600"></i> {{ __('Foto Profil Admin') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Upload foto profil baru Anda.") }}
        </p>
    </header>

    {{-- Form ini menangani UNGGAH FOTO --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center space-x-6">
            {{-- Foto Profil Saat Ini (Cyan Border) --}}
            <img class="h-24 w-24 rounded-full object-cover shadow-lg border-4 border-cyan-300" 
                 src="{{ Auth::user()->profile_photo_url }}" 
                 alt="{{ Auth::user()->name }}">
            
            <div>
                <label for="photo" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-upload mr-2 text-cyan-600"></i> {{ __('Unggah Foto Baru') }}
                </label>
                {{-- Input file dengan styling fokus Cyan --}}
                <input type="file" name="photo" id="photo" 
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 
                               focus:border-cyan-500 focus:ring-cyan-500 transition duration-150 p-2.5">
                
                @error('photo') <p class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB.</p>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            {{-- Tombol Unggah Foto (Cyan Primary Button) --}}
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/50 transition duration-300 transform hover:scale-[1.02]">
                <i class="fas fa-cloud-upload-alt mr-2"></i> {{ __('Unggah Foto') }}
            </button>

            @if (session('status') === 'profile-updated')
                {{-- Notifikasi 'Saved' diperbagus (Green/Success) --}}
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-semibold text-green-600 flex items-center"
                ><i class="fas fa-check-circle mr-1"></i> {{ __('Foto berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>
</section>