<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Pilihan Aroma') }}
            </h2>
            <button onclick="document.getElementById('addAromaForm').classList.toggle('hidden')"
                class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-md shadow-blue-100 hover:shadow-lg transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Aroma
            </button>
        </div>
    </x-slot>

    <div class="py-4">
        @if (session('success'))
            <div
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative shadow-sm text-sm">
                <span class="font-semibold">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        <div id="addAromaForm" class="hidden mb-6 bg-white p-6 rounded-xl border border-blue-100 shadow-sm">
            <form action="{{ route('aromas.store') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                @csrf
                <div class="flex-1 w-full">
                    <label class="block text-sm font-semibold text-gray-500 mb-1">Nama Aroma :</label>
                    <input type="text" name="name" placeholder="Contoh: Lavender"
                        class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="w-full md:w-auto">
                    <button type="submit"
                        class="w-full md:w-auto bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-6 rounded-lg text-sm transition duration-150">
                        Simpan Aroma
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Nama Aroma</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aromas as $aroma)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-150">
                                <td class="py-3.5 px-4 text-sm text-gray-900 font-medium">{{ $aroma->name }}</td>
                                <td class="py-3.5 px-4 text-sm text-center">
                                    <form action="{{ route('aromas.destroy', $aroma->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus aroma ini?')"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1.5 px-3 rounded-md transition duration-150 shadow-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-6 text-center text-gray-500 text-sm">
                                    Belum ada data aroma terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
