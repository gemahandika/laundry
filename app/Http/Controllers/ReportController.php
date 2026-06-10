<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter tanggal dari request URL
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Set tahun default ke tahun berjalan (2026) sebagai fallback
        $year = $request->get('year', Carbon::now()->year);

        // 1. Inisialisasi Base Query untuk transaksi agar filter bisa dipakai massal
        $query = Transaction::query();

        // 2. Terapkan Kondisi Filter Tanggal Harian ATAU Tahunan
        if ($startDate && $endDate) {
            // Jika kasir mengisi filter tanggal harian (Contoh: rentang 1 minggu atau hari ini)
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } else {
            // Default bawaan Anda: Jika filter kosong, kunci berdasarkan tahun berjalan
            $query->whereYear('created_at', $year);
        }

        // 3. Hitung total statistik utama berdasarkan Query yang sudah difilter di atas
        // Gunakan (clone) agar filter rentang tanggal ikut memengaruhi nominal statistik
        $totalOmzet     = (clone $query)->where('payment_status', 'lunas')->sum('total_pay');
        $piutang        = (clone $query)->where('payment_status', 'belum_bayar')->sum('total_pay');
        $totalTransaksi = (clone $query)->count();

        // 4. Ambil data grafik bulanan (Januari - Desember) 
        // Bagian ini tetap menggunakan query berbasis TAHUNAN agar visual grafik tetap utuh 12 bulan
        $monthlyData = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(CASE WHEN payment_status = "lunas" THEN total_pay ELSE 0 END) as omzet'),
            DB::raw('COUNT(id) as count')
        )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->pluck('omzet', 'month')
            ->toArray();

        // Pastikan semua bulan (1-12) terisi nilainya, jika kosong set ke 0
        $reportData = [];
        for ($m = 1; $m <= 12; $m++) {
            $reportData[] = $monthlyData[$m] ?? 0;
        }

        // 5. Ambil list transaksi untuk tabel detail laporan (mengikuti filter harian/tahunan)
        $latestTransactions = $query->with('customer')
            ->latest()
            ->paginate(10);

        $totalSemuaQty = \App\Models\TransactionDetail::whereIn('transaction_id', $query->pluck('id'))
            ->sum('quantity');

        return view('reports.index', compact(
            'totalOmzet',
            'piutang',
            'totalTransaksi',
            'totalSemuaQty',
            'reportData',
            'year',
            'latestTransactions'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $year = $request->get('year', Carbon::now()->year);

        $query = Transaction::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        } else {
            $query->whereYear('created_at', $year);
        }

        // Memuat relasi 'details' agar bisa menghitung QTY dengan akurat
        $transactions = $query->with(['customer', 'details'])->latest()->get();

        $filename = "Laporan_Keuangan_" . ($startDate ?: $year) . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        // Urutan kolom yang rapi
        $columns = [
            'Tanggal Transaksi',
            'Invoice Number',
            'Nama Pelanggan',
            'Start Date',
            'Finished At',
            'Total QTY',       // Kolom QTY
            'Subtotal',
            'Discount',
            'Total Pay',
            'Payment Status'
        ];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $trx) {
                // Menghitung total QTY dari semua item di transaksi ini
                $totalQty = $trx->details->sum('quantity');

                fputcsv($file, [
                    $trx->created_at ? $trx->created_at->format('d/m/Y H:i') : '-',
                    $trx->invoice_number,
                    $trx->customer ? $trx->customer->name : '-',
                    $trx->start_date ? Carbon::parse($trx->start_date)->format('d/m/Y H:i') : '-',
                    $trx->finished_at ? Carbon::parse($trx->finished_at)->format('d/m/Y H:i') : '-',
                    $totalQty,             // Nilai QTY yang sudah dijumlahkan
                    $trx->subtotal,
                    $trx->discount,
                    $trx->total_pay,
                    strtoupper($trx->payment_status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
