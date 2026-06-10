<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('services.index') }}"
                class="text-gray-500 hover:text-gray-700 transition p-1 rounded-lg hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Layanan Laundry') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">

            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-5">
                <h3 class="text-white font-semibold text-base uppercase tracking-wider">Ubah Konfigurasi Layanan</h3>
                <p class="text-amber-100 text-sm mt-0.5">Perubahan tarif atau estimasi waktu akan langsung diterapkan
                    pada pembuatan nota baru berikutnya.</p>
            </div>

            <form action="{{ route('services.update', $service->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Nama
                        Layanan</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}"
                            required placeholder="Contoh: Cuci Kering Setrika"
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200
                            {{ $errors->has('name') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-200' }} 
                            focus:ring-2 focus:ring-opacity-50">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <span class="inline-block animate-pulse">⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="type"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Jenis Satuan
                        Hitung</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <select name="type" id="type" required
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200 border-gray-300 focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                            <option value="kiloan" {{ old('type', $service->type) == 'kiloan' ? 'selected' : '' }}>Berat
                                (Kiloan)</option>
                            <option value="satuan" {{ old('type', $service->type) == 'satuan' ? 'selected' : '' }}>Per
                                Biji (Satuan)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="price"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Harga /
                        Tarif</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-sm font-semibold text-gray-400">Rp</span>
                        </div>
                        <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}"
                            required placeholder="Contoh: 7000"
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200
                            {{ $errors->has('price') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-200' }} 
                            focus:ring-2 focus:ring-opacity-50">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <span class="inline-block animate-pulse">⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="estimated_hours"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Estimasi Selesai
                        (Durasi Jam)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="number" name="estimated_hours" id="estimated_hours"
                            value="{{ old('estimated_hours', $service->estimated_hours) }}" required
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200
                            {{ $errors->has('estimated_hours') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-200' }} 
                            focus:ring-2 focus:ring-opacity-50">
                    </div>
                    <div class="mt-2 bg-gray-50 rounded-lg p-3 border border-gray-100 flex items-start gap-2">
                        <span class="text-xs">💡</span>
                        <p class="text-xs text-gray-500 leading-normal">
                            Panduan konversi cepat ke hari: <br>
                            <span class="font-semibold text-gray-700">24</span> = 1 Hari |
                            <span class="font-semibold text-gray-700">48</span> = 2 Hari |
                            <span class="font-semibold text-gray-700">72</span> = 3 Hari
                        </p>
                    </div>
                    @error('estimated_hours')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <span class="inline-block animate-pulse">⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('services.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-150 text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-lg shadow-md shadow-amber-100 hover:shadow-lg transition duration-150 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
