<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nota Transaksi: {{ $transaction->invoice_number }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('transactions.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
                    ← Kembali
                </a>
                <button onclick="printPortrait()"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition">
                    🖨️ Cetak Thermal (Portrait)
                </button>
                <button onclick="printLandscape()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm transition">
                    🖨️ Cetak LX-300 (Landscape)
                </button>
            </div>
        </div>
    </x-slot>

    <style>
        /* Gaya Khusus Ramping & Padat untuk Monitor / Screenshot WhatsApp */
        .font-nota {
            font-family: 'Courier New', Courier, monospace;
        }

        /* DEFAULT PRINT CONFIGURATION (Sembunyikan elemen web) */
        @media print {

            .no-print,
            nav,
            header,
            button,
            a {
                display: none !important;
            }

            body,
            .bg-gray-100 {
                background-color: white !important;
                color: black !important;
                padding: 10px !important;
            }

            .nota-box {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        }

        /* DINAMIS STYLE BERDASARKAN TOMBOL YANG DIKLIK */
        /* 1. Jika memilih cetak portrait (Thermal) */
        body.print-mode-portrait {
            @page {
                size: portrait;
                margin: 0;
            }
        }

        body.print-mode-portrait .nota-box {
            width: 100% !important;
            max-width: 80mm !important;
            margin: 0 auto !important;
        }

        /* 2. Jika memilih cetak landscape (Epson LX-300) */
        body.print-mode-landscape {
            @page {
                size: 8.5in 5.5in;
                margin: 0;
            }
        }

        body.print-mode-landscape .nota-box {
            width: 100% !important;
            max-width: 100% !important;
            padding: 10px 30px !important;
        }
    </style>

    <div class="py-4">
        <div id="notaContainer"
            class="max-w-[105mm] mx-auto bg-white shadow-md p-4 nota-box font-nota text-[10px] leading-tight">

            <div class="text-center mb-2 border-b border-dashed border-black pb-1">
                <h1 class="font-bold text-sm uppercase">SO FRESH LAUNDRY</h1>
                <p>Jl. Gaperta No. 234, Medan Helvetia</p>
                <p>Telp: 0812-6330-4948</p>
            </div>

            <table class="w-full mb-2">
                <tr>
                    <td class="w-1/2">No: {{ $transaction->invoice_number }}</td>
                    <td class="w-1/2 text-right">Aroma: {{ $transaction->aroma->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td colspan="2">Nama: {{ $transaction->customer->name }}</td>
                </tr>
                <tr>
                    <td colspan="2">Alamat: {{ $transaction->customer->address ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Telp: {{ $transaction->customer->phone }}</td>
                    <td class="text-right">Masuk: {{ $transaction->created_at->format('d-M-y H:i') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-right">
                        Selesai:
                        {{ $transaction->finished_at ? \Carbon\Carbon::parse($transaction->finished_at)->format('d-M-y H:i') : '-' }}
                    </td>
                </tr>
            </table>

            <div class="border-t border-b border-dashed border-black py-1 mb-2">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-dashed border-black">
                            <th class="text-left">Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->details as $detail)
                            <tr>
                                <td>{{ $detail->service->name }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-right">{{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-right font-bold text-xs mb-4">
                <div>Total Qty: {{ $transaction->details->sum('quantity') }}</div>
            </div>

            <div class="text-right font-bold text-xs space-y-1 mb-4">
                <div>Subtotal: Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</div>
                <div class="text-red-600">Diskon: - Rp {{ number_format($transaction->discount, 0, ',', '.') }}</div>
                <div class="border-t border-dashed border-black pt-1 text-sm">
                    TOTAL BAYAR: Rp {{ number_format($transaction->total_pay, 0, ',', '.') }}
                </div>
            </div>

            <table class="w-full mt-4 text-center">
                <tr>
                    <td class="w-1/2">( Cashier )</td>
                    <td class="w-1/2">( Customer )</td>
                </tr>
                <tr>
                    <td class="pt-8">Admin</td>
                    <td class="pt-8">{{ $transaction->customer->name }}</td>
                </tr>
            </table>

            <div class="mt-4 text-[8px] text-center">
                Barang yang tidak diambil dalam 30 hari di luar tanggung jawab kami.
            </div>
        </div>
    </div>

    <script>
        function printPortrait() {
            // Pasang class portrait ke body, hapus class landscape
            document.body.classList.add('print-mode-portrait');
            document.body.classList.remove('print-mode-landscape');

            window.print();
        }

        function printLandscape() {
            // Pasang class landscape ke body (untuk LX-300), hapus class portrait
            document.body.classList.add('print-mode-landscape');
            document.body.classList.remove('print-mode-portrait');

            window.print();
        }
    </script>
</x-app-layout>
