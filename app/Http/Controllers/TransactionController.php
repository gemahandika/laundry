<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Promo;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // 1. TAMPILKAN DAFTAR TRANSAKSI (Poin 6: Status Laundry)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $transactions = Transaction::with(['customer', 'aroma'])->latest()->get();
        // Mengambil data dengan Eager Loading agar query database efisien
        $transactions = Transaction::with(['customer', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            })
            ->latest() // Mengurutkan dari transaksi terbaru
            ->paginate(10) // Menampilkan 10 data per halaman
            ->withQueryString(); // Mempertahankan parameter 'search' saat pindah halaman

        return view('transactions.index', compact('transactions'));
    }

    // 2. FORM TRANSAKSI BARU (Kasir tinggal input, data pelanggan & layanan ditarik otomatis)
    public function create()
    {
        $aromas = \App\Models\Aroma::where('is_active', true)->get();
        $customers = Customer::all();
        $services = Service::all();
        $promos = Promo::all(); // Untuk diskon poin 5
        $customers = Customer::withCount('transactions')->get();

        return view('transactions.create', compact('customers', 'services', 'promos', 'aromas'));
    }

    // 3. PROSES SIMPAN TRANSAKSI & HITUNG OTOMATIS
    public function store(Request $request)
    {
        // Penyesuaian Validasi: customer_id dibuat nullable karena bisa diganti input text pelanggan baru
        $request->validate([
            'aroma_id' => 'nullable|exists:aromas,id',
            'customer_id' => 'nullable|exists:customers,id',
            'aroma_id'    => 'nullable|exists:aromas,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:15',
            'customer_address' => 'nullable|string',
            'services' => 'required|array',
            'services.*.id' => 'required|exists:services,id',
            'services.*.qty' => 'required|numeric|min:0.1',
            'promo_id' => 'nullable|exists:promos,id',
            'payment_status' => 'required|in:belum_bayar,lunas',
            'notes' => 'nullable|string',

        ]);

        // Gunakan Database Transaction agar jika satu proses gagal, semua dibatalkan (aman dari bug)
        DB::beginTransaction();
        try {
            // F. FILTER / DAFTARKAN PELANGGAN OTOMATIS JIKA BARU
            $customerId = $request->customer_id;

            if (empty($customerId)) {
                // Antisipasi ganda jika nomor telepon ternyata sudah ada di database untuk menghindari duplikasi
                $existingCustomer = Customer::where('phone', $request->customer_phone)->first();

                if ($existingCustomer) {
                    $customerId = $existingCustomer->id;
                } else {
                    // Daftarkan sebagai member baru di Maju Jaya Laundry
                    $newCustomer = Customer::create([
                        'name' => $request->customer_name,
                        'phone' => $request->customer_phone,
                        'address' => $request->customer_address,
                    ]);
                    $customerId = $newCustomer->id;
                }
            }

            // A. GENERATE NOMOR NOTA OTOMATIS (Contoh: TRS-20260604-0001)
            $now = Carbon::now();
            $yearMonth = $now->format('Ym'); // Mengambil Tahun dan Bulan saja (e.g., 202606)
            $todayFormat = $now->format('Ymd'); // Untuk format nota (ada tanggalnya)

            // Mencari transaksi terakhir yang memiliki awalan bulan yang sama (SFL-202606...)
            $lastTransaction = Transaction::where('invoice_number', 'like', 'SFL-' . $yearMonth . '%')
                ->latest()
                ->first();

            // Mengambil 4 angka terakhir, jika tidak ada mulai dari 1
            $nextNumber = $lastTransaction ? intval(substr($lastTransaction->invoice_number, -4)) + 1 : 1;

            // Format akhir: SFL-20260605-0001
            $invoiceNumber = 'SFL-' . $todayFormat . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            $maxEstimatedHours = 0;
            $detailsData = [];

            // B. HITUNG MATEMATIKA LAYANAN
            foreach ($request->services as $serviceInput) {
                $service = Service::find($serviceInput['id']);
                $qty = $serviceInput['qty'];
                $totalItem = $service->price * $qty;

                $subtotal += $totalItem;

                // Cari estimasi selesai paling lama jika mengambil multi-layanan
                if ($service->estimated_hours > $maxEstimatedHours) {
                    $maxEstimatedHours = $service->estimated_hours;
                }

                $detailsData[] = [
                    'service_id' => $service->id,
                    'quantity' => $qty,
                    'price' => $service->price,
                    'total' => $totalItem,
                ];
            }

            // C. HITUNG POTONGAN DISKON (Poin 5)
            $discount = 0;
            if ($request->promo_id) {
                $promo = Promo::find($request->promo_id);
                if ($promo->type == 'percentage') {
                    $discount = ($subtotal * $promo->value) / 100;
                } else {
                    $discount = $promo->value;
                }
            }

            $totalPay = max(0, $subtotal - $discount);
            $startDate = Carbon::now();
            $endDate = Carbon::now()->addHours($maxEstimatedHours); // Estimasi tanggal selesai otomatis


            // 1. Ambil data pelanggan
            $customer = \App\Models\Customer::find($request->customer_id);

            // 2. Hitung diskon
            $subtotal = $request->subtotal; // Pastikan ambil dari request
            $discount = 0;

            // Jika pelanggan adalah member, hitung diskon 10%
            if ($customer && $customer->is_member) {
                $discount = $subtotal * 0.10;
            } else {
                $discount = $request->discount ?? 0; // Jika bukan member, ambil input manual
            }


            // 1. Ambil nilai dari input yang sudah diupdate oleh JS
            $subtotal = $request->input('subtotal');
            $discount = $request->input('discount');
            $totalPay = $request->input('total_pay');

            // 2. Lakukan validasi agar tidak 0 (Opsional, untuk memastikan)
            if ($totalPay <= 0) {
                return back()->with('error', 'Total pembayaran tidak valid.');
            }
            // D. SIMPAN DATA UTAMA TRANSAKSI
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customerId, // <-- Menggunakan ID Pelanggan hasil filter di atas
                'user_id' => Auth::id(), // ID Kasir yang sedang login
                'promo_id' => $request->promo_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_pay' => $totalPay,
                'status' => 'diterima',
                'payment_status' => $request->payment_status,
                'notes' => $request->notes,
                'aroma_id' => $request->aroma_id,

            ]);

            // E. SIMPAN DETAIL LAYANAN YANG DIAMBIL
            foreach ($detailsData as $detail) {
                $detail['transaction_id'] = $transaction->id;
                TransactionDetail::create($detail);
            }

            DB::commit();
            return redirect()->route('transactions.print_nota', $transaction->id)->with('success', 'Transaksi ' . $invoiceNumber . ' berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 4. UPDATE STATUS LAUNDRY (Poin 6: Diterima -> Diproses -> Selesai -> Diambil)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,diproses,selesai,diambil',
            'payment_status' => 'required|in:belum_bayar,lunas',
        ]);

        $transaction = Transaction::find($id);

        // Logic: Jika status diubah ke 'selesai', isi finished_at dengan waktu saat ini
        $dataUpdate = [
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ];

        if ($request->status == 'selesai') {
            $dataUpdate['finished_at'] = Carbon::now();
        } else {
            // Jika status diubah kembali dari 'selesai' ke status lain, kosongkan jam selesainya
            $dataUpdate['finished_at'] = null;
        }

        $transaction->update($dataUpdate);

        return redirect()->back()->with('success', 'Status Nota ' . $transaction->invoice_number . ' berhasil diperbarui!');
    }

    // 5. TAMPILKAN DETAIL NOTA / INVOICE UNTUK DI-PRINT
    public function show($id)
    {
        // Ambil data transaksi beserta relasi detail, layanan, pelanggan, dan kasir
        $transaction = Transaction::with(['customer', 'user', 'promo', 'details.service'])->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    public function print(string $id)
    {
        // Memuat relasi 'customer' dan 'details.service' (sesuaikan 'service' dengan nama relasi di TransactionDetail Anda)
        $transaction = Transaction::with(['customer', 'details.service'])->findOrFail($id);

        return view('transactions.print_nota', compact('transaction'));
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Opsional: Hapus detail jika tidak menggunakan onDelete('cascade') di migration
        $transaction->details()->delete();

        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
