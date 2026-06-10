<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // 1. Tampilkan semua data pelanggan
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = Customer::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        })
            ->orderBy('name', 'asc') // Mengurutkan pelanggan abjad A-Z agar rapi
            ->paginate(10) // Tampilkan 10 data per halaman
            ->withQueryString(); // Mempertahankan teks pencarian saat pindah nomor halaman

        return view('customers.index', compact('customers'));
    }

    // 2. Tampilkan form tambah pelanggan
    public function create()
    {
        return view('customers.create');
    }

    // 3. Simpan data pelanggan baru ke database
    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
        ]);

        // Simpan ke database
        Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Kembali ke halaman utama dengan pesan sukses
        return redirect()->route('customers.index')->with('success', 'Pelanggan baru berhasil didaftarkan!');
    }

    // 4. Tampilkan form edit pelanggan
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    // 5. Update data pelanggan di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'is_member' => 'required|boolean',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_member' => $request->is_member,
        ]);

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    // 6. Hapus data pelanggan
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus!');
    }

    public function show($id)
    {
        // Ambil data pelanggan beserta seluruh riwayat transaksinya
        $customer = Customer::with('transactions')->findOrFail($id);

        return view('customers.show', compact('customer'));
    }
}
