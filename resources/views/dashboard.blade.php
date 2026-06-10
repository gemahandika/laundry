<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama Kasir') }}
        </h2>
    </x-slot>

    <div class="py-4 space-y-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div
                class="bg-slate-800 overflow-hidden shadow-xl shadow-blue-900/20 rounded-2xl p-6 border-l-4 border-blue-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-blue-300 uppercase tracking-widest">Transaksi Hari Ini</p>
                        <p class="text-2xl font-black text-white mt-1">{{ $todayTransactionsCount }} Nota</p>
                    </div>
                    <div class="text-2xl bg-blue-900/50 p-2.5 rounded-lg text-blue-300">📝</div>
                </div>
                <p class="text-[11px] text-slate-100 mt-3 font-medium">Total nota baru yang dibuat hari ini</p>
            </div>

            <div
                class="bg-slate-800 overflow-hidden shadow-xl shadow-green-900/20 rounded-2xl p-6 border-l-4 border-green-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-green-300 uppercase tracking-widest">Pendapatan Hari Ini
                        </p>
                        <p class="text-2xl font-black text-white mt-1">Rp
                            {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-2xl bg-green-900/50 p-2.5 rounded-lg text-green-300">💰</div>
                </div>
                <p class="text-[11px] text-slate-100 mt-3 font-medium">Total uang masuk nota lunas hari ini</p>
            </div>

            <div
                class="bg-slate-800 overflow-hidden shadow-xl shadow-yellow-900/20 rounded-2xl p-6 border-l-4 border-yellow-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-yellow-300 uppercase tracking-widest">Dalam Proses</p>
                        <p class="text-2xl font-black text-white mt-1">{{ $laundryInProcess }} Slot</p>
                    </div>
                    <div class="text-2xl bg-yellow-900/50 p-2.5 rounded-lg text-yellow-300">⏳</div>
                </div>
                <p class="text-[11px] text-slate-100 mt-3 font-medium">Pakaian yang sedang dicuci / disetrika</p>
            </div>

            <div
                class="bg-slate-800 overflow-hidden shadow-xl shadow-purple-900/20 rounded-2xl p-6 border-l-4 border-purple-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-purple-300 uppercase tracking-widest">Selesai (Siap Ambil)
                        </p>
                        <p class="text-2xl font-black text-white mt-1">{{ $laundryCompleted }} Nota</p>
                    </div>
                    <div class="text-2xl bg-purple-900/50 p-2.5 rounded-lg text-purple-300">✅</div>
                </div>
                <p class="text-[11px] text-slate-100 mt-3 font-medium">Sudah bersih & menunggu diambil</p>
            </div>

        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Aktivitas Nota Hari Ini</h3>
                <a href="{{ route('transactions.index') }}"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">Lihat Semua →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-800">No. Nota</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-800">Pelanggan</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-800">Total</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-800">Pembayaran</th>
                            <th class="py-2.5 px-4 text-sm font-bold uppercase text-gray-800">Status Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestTodayTransactions as $trx)
                            <tr class="border-b hover:bg-gray-50 transition text-sm">
                                <td class="py-3 px-4 font-bold font-mono text-blue-600">{{ $trx->invoice_number }}</td>
                                <td class="py-3 px-4 text-gray-900">{{ $trx->customer->name }}</td>
                                <td class="py-3 px-4 font-semibold">Rp {{ number_format($trx->total_pay, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full {{ $trx->payment_status == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full 
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
                                <td colspan="5" class="py-4 text-center text-gray-500 text-xs">Belum ada aktivitas
                                    transaksi masuk untuk hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
