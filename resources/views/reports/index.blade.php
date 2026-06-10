<x-app-layout>
    <!-- CSS Khusus: Dipastikan HANYA berjalan saat kertas dicetak (@media print) -->
    <style>
        @media print {

            /* Sembunyikan elemen navigasi web, filter, tombol, dan paginasi */
            nav,
            header,
            .sidebar,
            [role="navigation"],
            .print\:hidden,
            form {
                display: none !important;
            }

            /* Paksa background body web menjadi putih bersih tanpa abu-abu */
            body,
            main,
            #app {
                background-color: #ffffff !important;
                background: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Ubah layout py-6 dashboard menjadi rata kertas saat diprint */
            .main-content-wrapper {
                padding: 0 !important;
                max-width: 100% !important;
            }

            /* Hilangkan shadow kotak website agar tidak berbayang kotor di kertas */
            .shadow-sm,
            .rounded-xl {
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            /* Modifikasi kartu statistik agar minimalis saat di kertas */
            .stat-card {
                border: 1px solid #d1d5db !important;
                border-left-width: 1px !important;
                /* Hilangkan garis tebal kiri khusus cetak */
                padding: 12px !important;
            }

            /* Set margin batas potong kertas printer */
            @page {
                margin: 1.5cm;
            }
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 print:hidden">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Laporan Omzet & Keuangan Toko') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Periode Analisis:
                    <span
                        class="font-bold text-gray-700 font-mono">{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }}</span>
                    s/d
                    <span
                        class="font-bold text-gray-700 font-mono">{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Hari Ini' }}</span>
                </p>
            </div>

            <!-- GROUPING TOMBOL AKSI (PRINT & EXPORT) -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Tombol Print / Simpan PDF (Bawaan Browser) -->
                <button onclick="window.print()"
                    class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-3.5 rounded-lg text-sm border border-gray-300 shadow-sm transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak / PDF
                </button>

                <!-- Tombol Export Excel -->
                <a href="{{ route('reports.export', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-3.5 rounded-lg text-sm shadow-sm transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Unduh Excel
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Pembungkus Utama Konten -->
    <div class="main-content-wrapper py-6 space-y-6 print:py-0 print:space-y-4">

        <!-- ================= KOP SURAT FORMAL (HANYA MUNCUL SAAT PRINT) ================= -->
        <div class="hidden print:block border-b-2 border-gray-800 pb-3 mb-4">
            <div class="text-center">
                <h1 class="text-2xl font-black tracking-wide text-gray-900 uppercase">SO FRESH LAUNDRY</h1>
                <p class="text-xs text-gray-600 mt-0.5">Jl. Gaperta No. 234, Medan Helvetia | Hp: 0812-6330-4948</p>
                <div
                    class="inline-block bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-0.5 rounded mt-2 border border-gray-300 uppercase tracking-wider">
                    Dokumen Laporan Keuangan Internal
                </div>
            </div>
            <div class="mt-4 flex justify-between items-end text-xs text-gray-700">
                <div>
                    <p><strong>Periode Laporan:</strong>
                        {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d F Y') : 'Awal Rekam' }}
                        s/d
                        {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d F Y') : \Carbon\Carbon::now()->format('d F Y') }}
                    </p>
                    <p class="mt-0.5"><strong>Waktu Unduh:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB
                    </p>
                </div>
                <div class="text-right">
                    <p><strong>Status Data:</strong> Terkonsolidasi (Final)</p>
                </div>
            </div>
        </div>

        <!-- ================= PANEL FILTER TANGGAL (Kembali Berwarna & Shadow Normal) ================= -->
        <div class="bg-white shadow-sm sm:rounded-xl p-5 border border-gray-200 print:hidden">
            <form action="" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                    <div>
                        <label for="start_date"
                            class="block text-xs font-bold uppercase tracking-wider text-gray-800 mb-1.5">Tanggal
                            Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="end_date"
                            class="block text-xs font-bold uppercase tracking-wider text-gray-800 mb-1.5">Tanggal
                            Selesai</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                    <a href="{{ url()->current() }}"
                        class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-150 text-center w-1/2 md:w-auto">
                        Reset
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md text-sm transition duration-150 w-1/2 md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Data
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= RINGKASAN KARTU KEUANGAN (KEMBALI BERWARNA WARNI) ================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 print:grid-cols-3 print:gap-2">
            <!-- Omzet -->
            <div
                class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-emerald-500 relative overflow-hidden">
                <p class="text-xs font-bold text-gray-800 uppercase tracking-wider print:text-gray-700">Total Omzet
                    Bersih</p>
                <p class="text-2xl font-black text-emerald-600 mt-2 font-mono print:text-lg print:text-black">Rp
                    {{ number_format($totalOmzet, 0, ',', '.') }}</p>
            </div>

            <!-- Piutang -->
            <div
                class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-rose-500 relative overflow-hidden">
                <p class="text-xs font-bold text-gray-800 uppercase tracking-wider print:text-gray-700">Total Piutang
                    (Belum Lunas)</p>
                <p class="text-2xl font-black text-rose-600 mt-2 font-mono print:text-lg print:text-black">Rp
                    {{ number_format($piutang, 0, ',', '.') }}</p>
            </div>



            <!-- Volume -->
            <div
                class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-blue-500 relative overflow-hidden">
                <p class="text-xs font-bold text-gray-800 uppercase tracking-wider print:text-gray-700">Jumlah Transaksi
                </p>
                <p class="text-2xl font-black text-blue-600 mt-2 font-mono print:text-lg print:text-black">
                    {{ $totalTransaksi }} Struk</p>
            </div>
        </div>

        <!-- ================= TABEL RINCIAN TRANSAKSI ================= -->
        <div class="bg-white shadow-sm sm:rounded-xl p-6 border border-gray-100 print:p-0 print:border-none">
            <div class="flex items-center justify-between mb-4 print:mb-2">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider print:text-black print:text-xs">
                    Daftar Rincian Transaksi Masuk</h3>
                <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-md print:hidden">
                    Total: {{ $latestTransactions->total() }} Nota
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse print:text-xs">
                    <thead>
                        <tr
                            class="bg-gray-50 border-b border-gray-200 print:bg-gray-100 print:border-b-2 print:border-gray-400">
                            <th
                                class="py-3 px-4 text-sm font-bold uppercase text-gray-800 tracking-wider print:text-black print:py-2 print:px-2">
                                Tanggal</th>
                            <th
                                class="py-3 px-4 text-sm font-bold uppercase text-gray-800 tracking-wider print:text-black print:py-2 print:px-2">
                                No. Nota</th>
                            <th
                                class="py-3 px-4 text-sm font-bold uppercase text-gray-800 tracking-wider print:text-black print:py-2 print:px-2">
                                Pelanggan</th>
                            <th
                                class="py-3 px-4 text-sm font-bold uppercase text-gray-800 tracking-wider print:text-black print:py-2 print:px-2">
                                Nilai Nota</th>
                            <th
                                class="py-3 px-4 text-sm font-bold uppercase text-gray-800 tracking-wider print:text-black print:py-2 print:px-2">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 print:divide-gray-300">
                        @forelse($latestTransactions as $trx)
                            <tr class="hover:bg-gray-50/70">
                                <td
                                    class="py-3.5 px-4 text-sm font-mono text-gray-600 print:py-2 print:px-2 print:text-black">
                                    {{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td
                                    class="py-3.5 px-4 text-sm font-bold font-mono text-blue-600 print:py-2 print:px-2 print:text-black">
                                    {{ $trx->invoice_number }}</td>
                                <td
                                    class="py-3.5 px-4 text-sm text-gray-900 font-medium print:py-2 print:px-2 print:text-black">
                                    {{ $trx->customer->name }}</td>
                                <td
                                    class="py-3.5 px-4 text-sm font-bold text-gray-900 font-mono print:py-2 print:px-2 print:text-black">
                                    Rp {{ number_format($trx->total_pay, 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4 text-sm print:py-2 print:px-2">
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-semibold rounded-full print:border-none print:p-0 print:text-black
                                        {{ $trx->payment_status == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $trx->payment_status == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 text-sm print:text-black">
                                    Tidak
                                    ditemukan data transaksi keuangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5 print:hidden">
                {{ $latestTransactions->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- ================= SLOT TANDA TANGAN ================= -->
        <div class="hidden print:grid grid-cols-2 gap-4 pt-10 text-center text-xs text-gray-900 mt-8">
            <div>
                <p>Diperiksa Oleh,</p>
                <div class="h-14"></div>
                <p class="font-bold border-t border-gray-400 inline-block px-6 pt-1">Supervisor Kasir</p>
            </div>
            <div>
                <p>Medan, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
                <p class="mt-0.5">Dibuat Oleh,</p>
                <div class="h-14"></div>
                <p class="font-bold border-t border-gray-400 inline-block px-6 pt-1">Admin / Kasir Toko</p>
            </div>
        </div>

    </div>
</x-app-layout>
