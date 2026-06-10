<x-guest-layout>
    <!-- LATAR BELAKANG: Menggunakan gradasi super lembut dari biru muda ke hijau mint -->
    <div
        class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-gradient-to-tr from-sky-100 via-white to-emerald-50">

        <!-- CARD LOGIN UTAMA: Ditambahkan bayangan (shadow) kustom yang tebal dan jelas di pinggirannya -->
        <div
            class="w-full max-w-md bg-white/90 backdrop-blur-md rounded-3xl shadow-[0_20px_50px_rgba(14,165,233,0.15)] border border-slate-100 p-8 md:p-10 transform transition duration-150">

            <!-- HEADER: IDENTITAS LAUNDRY -->
            <div class="text-center mb-8">
                <!-- Ikon Gelembung Sabun dengan gradasi Aqua -->
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-400 to-emerald-400 text-white mb-4 text-3xl shadow-lg shadow-sky-200 animate-bounce duration-1000">
                    🫧
                </div>
                <!-- Nama Laundry dengan warna Navy Gelap agar kontras -->
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">
                    So Fresh Laundry
                </h1>
                <!-- Alamat dengan warna abu-abu kebiruan -->
                <p class="text-[11px] font-bold text-sky-600/80 mt-1 uppercase tracking-widest">
                    Jl. Gaperta No. 234
                </p>
            </div>

            <!-- Teks Penyambut Menuju Form -->
            <div class="mb-6 bg-sky-50/50 rounded-2xl p-4 border border-sky-100/60">
                <h3 class="text-sm font-bold text-slate-700">Akses Masuk Kasir</h3>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan email dan kata sandi Anda untuk mulai mengelola
                    transaksi laundry.</p>
            </div>

            <!-- Session Status Penanda Error/Berhasil -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- FORM INPUT -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Input Alamat Email -->
                <div>
                    <label for="email"
                        class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sky-400 text-base">
                            ✉️
                        </div>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                            autocomplete="username" placeholder="kasir@sofresh.com"
                            class="block w-full pl-10 pr-4 py-3 text-sm rounded-xl border-slate-200/80 focus:border-sky-400 focus:ring-4 focus:ring-sky-100 transition bg-slate-50/50 text-slate-800 placeholder-slate-400/80 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-500 font-medium" />
                </div>

                <!-- Input Kata Sandi / Password -->
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Kata Sandi
                        </label>

                    </div>
                    <div class="relative rounded-xl shadow-sm">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-sky-400 text-base">
                            🔒
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full pl-10 pr-4 py-3 text-sm rounded-xl border-slate-200/80 focus:border-sky-400 focus:ring-4 focus:ring-sky-100 transition bg-slate-50/50 text-slate-800 placeholder-slate-400/80">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-500 font-medium" />
                </div>

                <!-- Opsi Ingat Perangkat -->
                <div class="flex items-center pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-slate-300 text-sky-400 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 w-4 h-4 cursor-pointer">
                        <span class="ms-2 text-xs font-semibold text-slate-500 hover:text-slate-700 transition">Ingat
                            perangkat ini</span>
                    </label>
                </div>

                <!-- Tombol Submit Masuk dengan Gradasi Biru-Hijau Segar -->
                <div class="pt-3">
                    <button type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-sky-100 text-sm font-bold text-white bg-gradient-to-r from-sky-500 to-emerald-500 hover:from-sky-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400 transition duration-150 active:scale-[0.98]">
                        Masuk Dashboard Kasir
                    </button>
                </div>
            </form>

            <!-- KETERANGAN BAWAH / FOOTER CARD -->
            <div class="mt-8 pt-4 border-t border-slate-100 text-center">
                <p
                    class="text-[10px] font-extrabold tracking-widest bg-gradient-to-r from-sky-500 to-emerald-500 bg-clip-text text-transparent uppercase">
                    ✨ BERSIH • WANGI • CEPAT • RAPI ✨
                </p>
            </div>

        </div>
    </div>
</x-guest-layout>
