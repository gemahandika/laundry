<?php

namespace App\Http\Controllers;

use App\Models\Aroma;
use Illuminate\Http\Request;

class AromaController extends Controller
{
    public function index()
    {
        $aromas = Aroma::all();
        return view('aromas.index', compact('aromas'));
    }

    public function store(Request $request)
    {
        // 1. Validasi: Pastikan nama aroma wajib diisi
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 2. Simpan ke database
        \App\Models\Aroma::create([
            'name' => $request->name,
        ]);

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('aromas.index')->with('success', 'Aroma berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Aroma::findOrFail($id)->delete();
        return back()->with('success', 'Aroma berhasil dihapus!');
    }
}
