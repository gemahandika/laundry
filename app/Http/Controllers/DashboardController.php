<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Ringkasan transaksi harian (Jumlah nota masuk hari ini)
        $todayTransactionsCount = Transaction::whereDate('created_at', $today)->count();

        // 2. Total pendapatan harian (Hanya uang masuk dari nota lunas hari ini)
        $todayRevenue = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'lunas')
            ->sum('total_pay');

        // 3. Jumlah laundry dalam proses (Status: diterima atau diproses)
        $laundryInProcess = Transaction::whereIn('status', ['diterima', 'diproses'])->count();

        // 4. Jumlah laundry selesai (Status: selesai, tetapi belum diambil pelanggan)
        $laundryCompleted = Transaction::where('status', 'selesai')->count();

        // Ambil 5 aktivitas nota terbaru hari ini untuk dipantau kasir
        $latestTodayTransactions = Transaction::with('customer')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'todayTransactionsCount',
            'todayRevenue',
            'laundryInProcess',
            'laundryCompleted',
            'latestTodayTransactions'
        ));
    }
}
