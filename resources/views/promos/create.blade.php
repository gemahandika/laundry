<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('promos.index') }}"
                class="text-gray-500 hover:text-gray-700 transition p-1 rounded-lg hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Promo Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">

            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-8 py-5">
                <h3 class="text-white font-semibold text-base uppercase tracking-wider">Formulir Diskon Belanja</h3>
                <p class="text-emerald-100 text-sm mt-0.5">Buat kode kupon unik khusus untuk menarik perhatian pelanggan
                    setia laundry Anda.</p>
            </div>

            <form action="{{ route('promos.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div>
                    <label for="code"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Kode Promo</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" required
                            placeholder="Contoh: LAUNDRYBERKAH, DISKON10"
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200 uppercase font-mono tracking-wider
                            {{ $errors->has('code') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-emerald-500 focus:ring-emerald-200' }} 
                            focus:ring-2 focus:ring-opacity-50">
                    </div>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <span class="inline-block animate-pulse">⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="type"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Jenis
                        Potongan</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h.01M15 17h.01M9 17l6-10M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <select name="type" id="type" required
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200 border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase
                                (%)</option>
                            <option value="nominal" {{ old('type') == 'nominal' ? 'selected' : '' }}>Nominal Rupiah (Rp)
                            </option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="value"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Nilai
                        Potongan</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="number" name="value" id="value" value="{{ old('value') }}" required
                            placeholder="Misal: masukkan 10 untuk diskon 10%, atau 5000 untuk potongan Rp 5.000"
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200
                            {{ $errors->has('value') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-emerald-500 focus:ring-emerald-200' }} 
                            focus:ring-2 focus:ring-opacity-50">
                    </div>
                    @error('value')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <span class="inline-block animate-pulse">⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('promos.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-150 text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-md shadow-emerald-100 hover:shadow-lg transition duration-150 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Simpan Promo
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
