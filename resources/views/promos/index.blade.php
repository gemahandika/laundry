<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Diskon & Promo') }}
            </h2>
            <a href="{{ route('promos.create') }}"
                class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-md shadow-emerald-100 hover:shadow-lg transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Promo
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        @if (session('success'))
            <div
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative shadow-sm text-sm">
                <span class="font-semibold">Berhasil!</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Kode Kupon / Promo
                            </th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Jenis Potongan</th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase">Besar Nilai Diskon
                            </th>
                            <th class="py-3.5 px-4 font-semibold text-sm text-gray-800 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promos as $promo)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-150">
                                <td class="py-3.5 px-4 text-sm">
                                    <span
                                        class="px-3 py-1.5 text-xs font-mono font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-dashed border-emerald-300 rounded-md shadow-sm inline-block">
                                        🎟️ {{ $promo->code }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-sm">
                                    <span
                                        class="px-3 py-1 text-xs font-bold rounded-full tracking-wide inline-block border
                                        {{ $promo->type == 'percentage' ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-blue-100 text-blue-800 border-blue-200' }}">
                                        {{ $promo->type == 'percentage' ? 'PERSENTASE (%)' : 'NOMINAL (RUPIAH)' }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-sm font-bold text-gray-900">
                                    @if ($promo->type == 'percentage')
                                        <span class="text-emerald-600">{{ $promo->value }}%</span>
                                        @identifikasikan nominal rupiah
                                    @else
                                        <span class="text-gray-400 font-normal text-xs">Rp</span> <span
                                            class="text-emerald-600">{{ number_format($promo->value, 0, ',', '.') }}</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-sm text-center flex justify-center items-center gap-2">
                                    <a href="{{ route('promos.edit', $promo->id) }}"
                                        class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-bold py-1.5 px-3 rounded-md transition duration-150 shadow-sm"
                                        title="Edit Promo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('promos.destroy', $promo->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus promo ini? Nota berjalan yang sudah menggunakan kode ini tidak akan berubah, namun pelanggan baru tidak bisa memakainya lagi.')"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 bg-red-100 text-red-700 hover:bg-red-200 text-xs font-bold py-1.5 px-3 rounded-md transition duration-150 shadow-sm"
                                            title="Hapus Promo">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500 text-sm">
                                    Belum ada data diskon atau kode kupon promo terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
