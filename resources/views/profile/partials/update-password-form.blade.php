<section class="bg-white p-6 shadow-2xl rounded-3xl border border-gray-100 ring-4 ring-cyan-50">
    <header class="border-b border-cyan-200 pb-3 mb-4">
        <h2 class="font-extrabold text-2xl text-gray-800 flex items-center">
            <i class="fas fa-key mr-3 text-cyan-600"></i> {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-4">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="current_password" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                <i class="fas fa-user-lock mr-2 text-cyan-600"></i> {{ __('Current Password') }}
            </label>
            <input id="current_password" name="current_password" type="password" 
                   class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 
                          focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                   autocomplete="current-password" />
            @error('current_password', 'updatePassword') <p class="text-sm text-red-500 mt-2 font-semibold">{{ $message }}</p> @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="password" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                <i class="fas fa-lock mr-2 text-cyan-600"></i> {{ __('New Password') }}
            </label>
            <input id="password" name="password" type="password" 
                   class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 
                          focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                   autocomplete="new-password" />
            @error('password', 'updatePassword') <p class="text-sm text-red-500 mt-2 font-semibold">{{ $message }}</p> @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block font-bold text-sm text-gray-700 mb-2 flex items-center">
                <i class="fas fa-check-double mr-2 text-cyan-600"></i> {{ __('Confirm Password') }}
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password" 
                   class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 
                          focus:border-cyan-500 focus:ring-cyan-500 transition duration-150" 
                   autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword') <p class="text-sm text-red-500 mt-2 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100 mt-6">
            {{-- Tombol Simpan (Cyan) --}}
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-cyan-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-cyan-500/50 hover:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/50 transition duration-300 transform hover:scale-[1.02]">
                <i class="fas fa-save mr-2"></i> {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
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