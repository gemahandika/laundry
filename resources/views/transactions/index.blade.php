<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Transaksi & Status Laundry') }}
            </h2>
            <a href="{{ route('transactions.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow shadow-blue-200 text-sm transition">
                + Buka Nota Baru
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <form action="{{ route('transactions.index') }}" method="GET" class="flex gap-2 max-w-md">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari No. Nota, nama pelanggan, atau telp..."
                        class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 pl-9">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <button type="submit"
                    class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-md text-sm font-medium transition">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('transactions.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-700 border-b border-gray-200">
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">No. Nota
                            </th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">Pelanggan
                            </th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">Alamat
                            </th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">Aroma
                            </th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">Diskon
                            </th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">Total
                                Bayar</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">
                                Pembayaran</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">Status
                                Kerja</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">Info Waktu</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100">Waktu Diambil</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100 text-center">
                                Atur Status</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-100 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a class="text-blue-600 hover:text-blue-900 font-bold">
                                            {{ $trx->invoice_number }} </a>

                                        <a href="{{ route('transactions.print', $trx->id) }}" target="_blank"
                                            class="text-gray-500 hover:text-blue-600 p-1 rounded hover:bg-gray-100 transition shadow-sm border border-gray-200 bg-white"
                                            title="Cetak Ulang Nota">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-sm">
                                    <div class="font-medium text-gray-900">{{ $trx->customer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $trx->customer->phone }}</div>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-600 truncate max-w-[150px]"
                                    title="{{ $trx->customer->address }}">
                                    {{ $trx->customer->address ?? '-' }}
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-700 font-medium">
                                    {{ $trx->aroma ? $trx->aroma->name : '-' }}
                                </td>

                                <td class="py-4 px-4 text-sm font-semibold text-gray-950">Rp
                                    {{ number_format($trx->discount, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-sm font-semibold text-gray-950">Rp
                                    {{ number_format($trx->total_pay, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-sm">
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $trx->payment_status == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm">
                                    <span
                                        class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                        {{ $trx->status == 'diterima' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $trx->status == 'diproses' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $trx->status == 'selesai' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $trx->status == 'diambil' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm">
                                    <div class="space-y-1">
                                        <div class="text-xs text-gray-500">
                                            Masuk: <span
                                                class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($trx->start_date)->format('d M, H:i') }}</span>
                                        </div>

                                        <div class="text-xs text-blue-600">
                                            Estimasi: <span
                                                class="font-medium">{{ \Carbon\Carbon::parse($trx->end_date)->format('d M, H:i') }}</span>
                                        </div>

                                        @if ($trx->finished_at)
                                            <div class="text-xs text-purple-600 font-bold">
                                                Selesai:
                                                {{ \Carbon\Carbon::parse($trx->finished_at)->format('d M, H:i') }}
                                            </div>
                                        @else
                                            <div class="text-xs text-gray-400 italic">Belum selesai</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-sm">
                                    <div class="space-y-1">

                                        @if ($trx->taken_at)
                                            <div class="text-green-600 font-semibold">
                                                {{ $trx->taken_at->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $trx->taken_at->format('H:i') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Belum diambil</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-sm text-center">
                                    <form action="{{ route('transactions.updateStatus', $trx->id) }}" method="POST"
                                        class="inline-flex items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status"
                                            class="text-xs border-gray-300 rounded-md py-1 px-2 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="diterima"
                                                {{ $trx->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                            <option value="diproses"
                                                {{ $trx->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="selesai" {{ $trx->status == 'selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                            <option value="diambil" {{ $trx->status == 'diambil' ? 'selected' : '' }}>
                                                Diambil</option>
                                        </select>
                                        <select name="payment_status"
                                            class="text-xs border-gray-300 rounded-md py-1 px-2 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="belum_bayar"
                                                {{ $trx->payment_status == 'belum_bayar' ? 'selected' : '' }}>
                                                Belum Lunas</option>
                                            <option value="lunas"
                                                {{ $trx->payment_status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        </select>
                                        <button type="submit"
                                            class="bg-gray-700 hover:bg-black text-white px-2 py-1 rounded-md text-xs transition">Update</button>
                                    </form>
                                </td>
                                <td class="py-4 px-4 text-sm text-center">
                                    <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Data tidak dapat dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 transition p-1 rounded-md hover:bg-red-50"
                                            title="Hapus Transaksi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-500 text-sm">
                                    {{ request('search') ? 'Transaksi yang Anda cari tidak ditemukan.' : 'Belum ada riwayat transaksi masuk.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
