<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('customers.index') }}"
                class="text-gray-500 hover:text-gray-700 transition p-1 rounded-lg hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ubah Data Pelanggan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">

            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-5">
                <h3 class="text-white font-semibold text-base uppercase tracking-wider">Perbarui Informasi Pelanggan
                </h3>
                <p class="text-amber-100 text-sm mt-0.5">Mengubah data di sini akan otomatis memperbarui informasi pada
                    nota transaksi selanjutnya.</p>
            </div>

            <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Nama
                        Lengkap</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}"
                            required placeholder="Contoh: Budi Santoso"
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
                    <label for="phone"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Nomor Telepon /
                        WA</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                            required placeholder="Contoh: 081234567xxx"
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200
                            {{ $errors->has('phone') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-200' }} 
                            focus:ring-2 focus:ring-opacity-50">
                    </div>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <span class="inline-block animate-pulse">⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="address"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Alamat
                        Rumah</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <textarea name="address" id="address" rows="4"
                            placeholder="Masukkan nama jalan, nomor rumah, RT/RW, kelurahan dan kecamatan..."
                            class="block w-full rounded-md text-sm pl-10 py-2.5 transition duration-200
                            {{ $errors->has('address') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-200' }} 
                            focus:ring-2 focus:ring-opacity-50">{{ old('address', $customer->address) }}</textarea>
                    </div>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <span class="inline-block animate-pulse">⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="is_member"
                        class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                        Status Keanggotaan
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <select name="is_member" id="is_member"
                            class="block w-full rounded-md text-sm py-2.5 transition duration-200 focus:ring-2 focus:ring-opacity-50 
            {{ $errors->has('is_member') ? 'border-red-300 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-amber-500 focus:ring-amber-200' }}">
                            <option value="1" {{ old('is_member', $customer->is_member) == 1 ? 'selected' : '' }}>
                                Member</option>
                            <option value="0" {{ old('is_member', $customer->is_member) == 0 ? 'selected' : '' }}>
                                Biasa</option>
                        </select>
                    </div>
                    @error('is_member')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <span class="inline-block animate-pulse">⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('customers.index') }}"
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
