<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">

        <!-- Logo atau Judul -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Lupa Password</h1>
        </div>

        <!-- Kartu Utama -->
        <div
            class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-lg shadow-slate-200/50 rounded-2xl border border-slate-100 overflow-hidden">

            <div class="mb-4 text-sm text-slate-600">
                {{ __('Lupa password? Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan tautan reset password yang memungkinkan Anda memilih yang baru.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email"
                        class="block mt-1 w-full rounded-lg border-slate-200 focus:border-slate-500 focus:ring-slate-500"
                        type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button class="bg-slate-800 hover:bg-slate-900 rounded-lg shadow-md transition-all">
                        {{ __('Kirim Link Reset') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
