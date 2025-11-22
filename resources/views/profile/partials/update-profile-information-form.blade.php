<section class="bg-white p-6 shadow-2xl rounded-3xl border border-gray-100 ring-4 ring-cyan-50">
    <header class="border-b border-cyan-200 pb-4 mb-6">
        <h2 class="font-extrabold text-3xl text-gray-800 flex items-center">
            <i class="fas fa-id-card mr-3 text-cyan-600"></i> {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6">
        @csrf
        @method('patch')

        {{-- PEMBAGIAN FIELD MENJADI 2 KOLOM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
        
            {{-- 1. Nama Lengkap (Kolom Kiri) --}}
            <div>
                <label for="name" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-user mr-2 text-cyan-600"></i> {{ __('Name') }}
                </label>
                <input id="name" name="name" type="text" 
                        class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 
                               focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                        value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name') <p class="text-red-500 text-sm mt-2 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- 2. Email Address (Kolom Kanan) --}}
            <div>
                <label for="email" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-at mr-2 text-cyan-600"></i> {{ __('Email') }}
                </label>
                <input id="email" name="email" type="email" 
                        class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 
                               focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                        value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email') <p class="text-red-500 text-sm mt-2 font-semibold">{{ $message }}</p> @enderror

                {{-- Notifikasi Email Belum Terverifikasi --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-red-50 border border-red-300 rounded-lg">
                        <p class="text-sm text-red-800 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline ml-2 text-sm text-red-600 hover:text-red-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

        </div>
        {{-- AKHIR PEMBAGIAN KOLOM --}}

        <div class="flex items-center gap-4 pt-4 mt-4 border-t border-gray-100">
            {{-- Tombol Simpan (Gaya Primary Button Cyan) --}}
            <button type="submit" class="inline-flex items-center px-5 py-2 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/50 transition duration-300 transform hover:scale-[1.02]">
                <i class="fas fa-save mr-2"></i> {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                {{-- Notifikasi 'Saved' diperbagus --}}
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-semibold text-green-600 flex items-center"
                ><i class="fas fa-check-circle mr-1"></i> {{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>