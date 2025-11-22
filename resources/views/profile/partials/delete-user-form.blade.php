<section class="space-y-6 bg-white p-8 shadow-2xl rounded-3xl border border-gray-100 ring-4 ring-cyan-50">
    <header class="border-b border-red-200 pb-4 mb-6">
        <h2 class="font-extrabold text-3xl text-red-800 flex items-center">
            <i class="fas fa-user-times mr-3 text-red-600"></i> {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    {{-- Tombol untuk membuka Modal --}}
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-6 py-2.5 bg-red-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-red-500/50 hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/50 transition duration-300 transform hover:scale-[1.02]"
    >
        <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete Account') }}
    </button>

    {{-- Modal Konfirmasi --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-extrabold text-red-700">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">{{ __('Password') }}</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full border-gray-300 rounded-xl shadow-md p-2.5 
                           focus:border-red-500 focus:ring-red-500 transition duration-150"
                    placeholder="{{ __('Password') }}"
                />

                @error('password', 'userDeletion') <p class="text-red-500 text-sm mt-2 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                {{-- Tombol Batal --}}
                <button type="button" x-on:click="$dispatch('close')" 
                        class="inline-flex items-center px-5 py-2 bg-white border border-gray-300 rounded-xl font-bold text-sm text-gray-700 uppercase tracking-wider hover:bg-gray-100 transition duration-150 shadow-md">
                    {{ __('Cancel') }}
                </button>

                {{-- Tombol Hapus Akun --}}
                <button type="submit" class="inline-flex items-center px-5 py-2 bg-red-600 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-wider shadow-lg shadow-red-500/50 hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/50 transition duration-150">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>