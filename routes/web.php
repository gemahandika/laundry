<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;     // Controller Pelanggan
use App\Http\Controllers\ServiceController;      // Controller Layanan
use App\Http\Controllers\PromoController;        // Controller Promo
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;          // Controller Manajemen User Baru
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackupController;

Route::get('/', function () {
    return view('welcome');
});

// Route Dashboard Utama (Bisa diakses Admin & Kasir setelah login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('aromas', App\Http\Controllers\AromaController::class);
Route::post('/transactions/{id}/mark-as-taken', [TransactionController::class, 'markAsTaken'])->name('transactions.mark-as-taken');

// =========================================================================
// GOLONGAN 1: SEMUA USER YANG SUDAH LOGIN (ADMIN & KASIR)
// =========================================================================
Route::middleware('auth')->group(function () {

    // Profil User (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Data Pelanggan (Kasir & Admin sama-sama butuh input data pelanggan baru)
    Route::resource('customers', CustomerController::class);

    // Fitur Transaksi Laundry Utama (Akses Bersama)
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::patch('transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');

    // =====================================================================
    // GOLONGAN 2: PROTEKSI KHUSUS HAK AKSES ADMIN (`role:admin`)
    // =====================================================================
    Route::middleware(['role:admin'])->group(function () {

        // 1. Manajemen User / Karyawan (Fitur Baru)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // 2. Fitur Laporan Keuangan & Omzet (Dipindahkan ke sini agar Kasir tidak bisa intip)
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        // 3. Master Data Aturan Toko (Hanya Admin yang boleh ubah harga layanan & voucher promo)
        Route::resource('services', ServiceController::class)->except(['index', 'show']); // Create, Edit, Delete dikunci
        Route::resource('promos', PromoController::class)->except(['index', 'show']);

        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.reset-password')
            ->middleware('auth'); // Pastikan hanya admin yang bisa akses
    });

    // Jalur aman: Kasir tetap boleh melihat daftar harga layanan & promo aktif, tapi tidak bisa mengedit/menghapusnya
    Route::resource('services', ServiceController::class)->only(['index', 'show']);
    Route::resource('promos', PromoController::class)->only(['index', 'show']);
});



Route::middleware(['auth'])->group(function () {
    // Jalur Route Backup & Restore Database
    Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/run', [BackupController::class, 'backup'])->name('backups.run');
    Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('backups.download');
    Route::post('/backups/restore', [BackupController::class, 'restore'])->name('backups.restore');
    Route::delete('/backups/delete/{filename}', [BackupController::class, 'destroy'])->name('backups.delete');
});

Route::resource('transactions', TransactionController::class);
// Jika menggunakan Controller
Route::get('/transactions/{id}/print', [TransactionController::class, 'printNota'])->name('transactions.print_nota');

require __DIR__ . '/auth.php';
