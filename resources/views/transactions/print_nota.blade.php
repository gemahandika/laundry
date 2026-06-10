<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Nota - {{ $transaction->invoice_number }}</title>
    <style>
        /* 1. Pengaturan khusus printer untuk memaksa 1 halaman */
        @page {
            size: 8.5in 5.5in;
            /* Ukuran umum kertas continuous form */
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* 2. LOGIKA PRINT: Sembunyikan semua elemen selain nota */
        @media print {

            /* Sembunyikan SEMUA elemen admin */
            body * {
                visibility: hidden;
            }

            /* Tampilkan HANYA area nota */
            #printableArea,
            #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }

        /* Styling Nota */
        .header {
            text-align: center;
            margin-bottom: 5px;
        }

        .store-name {
            font-weight: bold;
            font-size: 14px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .text-right {
            text-align: right;
        }

        .ttd {
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div id="printableArea">
        <div class="header">
            <div class="store-name">SO FRESH LAUNDRY</div>
            <div style="font-weight: bold; font-size: 12px">Jl. Gaperta No. 234 Medan Helvetia</div>
            <div style="font-weight: bold;">081263304948</div>
        </div>
        <div class="divider"></div>

        <table style="font-size: 9px;">
            <tr>
                <td style="font-weight: bold;">No. Invoice</td>
                <td style="font-weight: bold;">: {{ $transaction->invoice_number }}</td>
                <td style="font-weight: bold;">Aroma</td>
                <td style="font-weight: bold;">: {{ $transaction->aroma->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Name</td>
                <td style="font-weight: bold;">: {{ $transaction->customer->name }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Address</td>
                <td style="font-weight: bold;">: {{ $transaction->customer->address ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Receive</td>
                <td style="font-weight: bold;">: {{ $transaction->created_at->format('d-M-y H:i') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Finish</td>
                <td style="font-weight: bold;">:
                    {{ $transaction->finished_at ? \Carbon\Carbon::parse($transaction->finished_at)->format('d-M-y H:i') : '-' }}
                </td>
            </tr>
        </table>

        <div class="divider"></div>
        <table>
            <thead>
                <tr style="border-bottom: 1px dashed #000;">
                    <th align="left">Services</th>
                    <th align="center">Qty</th>
                    <th align="right">Price</th>
                    <th align="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $detail)
                    <tr>
                        <td style="font-size: 12px; font-weight: bold;">{{ $detail->service->name }}</td>
                        <td align="center" style="font-size: 12px; font-weight: bold;">{{ $detail->quantity }}</td>
                        <td align="right" style="font-size: 12px; font-weight: bold;">
                            {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td align="right" style="font-size: 12px; font-weight: bold;">
                            {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <table style="font-weight: bold;">
            <tr>
                <td>TOTAL QTY</td>
                <td class="text-right">: {{ $transaction->details->sum('quantity') }}</td>
            </tr>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">: Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td class="text-right">: Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="font-size: 11px;">TOTAL BAYAR</td>
                <td class="text-right" style="font-size: 11px;">: Rp
                    {{ number_format($transaction->total_pay, 0, ',', '.') }}</td>
            </tr>
        </table>

        <table class="ttd">
            <tr>
                <td align="center" style="width: 50%; font-weight: bold;">( Cashier )<br><br><br>Admin</td>
                <td align="center" style="width: 50%; font-weight: bold;">( Customer
                    )<br><br><br>{{ $transaction->customer->name }}</td>
            </tr>
        </table>

        <div class="divider" style="margin-top: 5px;"></div>
        <div style="font-size: 8px; font-weight: bold;">
            PERHATIAN :<br>
            1. Barang yang tidak diambil setelah 1 bln di luar tanggung jawab.
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
