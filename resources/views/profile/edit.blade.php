<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Pengaturan Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card Profil -->
            <div class="p-6 bg-white shadow-lg shadow-slate-200/50 rounded-2xl border border-slate-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Card Password -->
            <div class="p-6 bg-white shadow-lg shadow-slate-200/50 rounded-2xl border border-slate-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Card Hapus Akun -->
            <div class="p-6 bg-white shadow-lg shadow-slate-200/50 rounded-2xl border border-slate-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
