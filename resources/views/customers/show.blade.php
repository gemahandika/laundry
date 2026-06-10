<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profil Pelanggan: {{ $customer->name }}
            </h2>
            <a href="{{ route('customers.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-1.5 px-3 rounded text-xs transition">
                ← Kembali ke List
            </a>
        </div>
    </x-slot>

    <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4 h-fit">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-2">Biodata Member</h3>

            <div>
                <label class="block text-xs text-gray-500">Nama Lengkap</label>
                <p class="text-base font-bold text-gray-900">{{ $customer->name }}</p>
            </div>

            <div>
                <label class="block text-xs text-gray-500">Nomor Telepon</label>
                <p class="text-sm font-semibold text-blue-600">{{ $customer->phone }}</p>
            </div>

            <div>
                <label class="block text-xs text-gray-500">Alamat Rumah</label>
                <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded border border-dashed mt-1">
                    {{ $customer->address ?? 'Alamat belum diisi.' }}
                </p>
            </div>

            <div class="pt-2">
                <div class="bg-blue-50 text-blue-800 rounded p-3 text-center">
                    <p class="text-xs font-medium">Total Loyalitas</p>
                    <p class="text-xl font-black mt-1">{{ $customer->transactions->count() }} Kali Cuci</p>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-2 mb-4">Riwayat Aktivitas
                Nota</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b text-xs font-bold text-gray-600 uppercase">
                            <th class="py-2.5 px-4">Tanggal</th>
                            <th class="py-2.5 px-4">No. Nota</th>
                            <th class="py-2.5 px-4">Total Bayar</th>
                            <th class="py-2.5 px-4">Pembayaran</th>
                            <th class="py-2.5 px-4">Status Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->transactions as $trx)
                            <tr class="border-b hover:bg-gray-50 text-sm transition">
                                <td class="py-3 px-4 text-gray-600">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-blue-600">
                                    <a href="{{ route('transactions.show', $trx->id) }}"
                                        class="hover:underline">{{ $trx->invoice_number }}</a>
                                </td>
                                <td class="py-3 px-4 font-semibold text-gray-900">Rp
                                    {{ number_format($trx->total_pay, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $trx->payment_status == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold rounded-full
                                        {{ $trx->status == 'diterima' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $trx->status == 'diproses' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $trx->status == 'selesai' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $trx->status == 'diambil' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400 italic text-sm">Pelanggan ini
                                    belum memiliki riwayat transaksi laundry.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
