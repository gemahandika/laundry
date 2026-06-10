<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->get();
        return view('promos.index', compact('promos'));
    }

    public function create()
    {
        return view('promos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promos,code|max:50',
            'type' => 'required|in:percentage,nominal',
            'value' => 'required|integer|min:1',
        ]);

        Promo::create($request->all());

        return redirect()->route('promos.index')->with('success', 'Promo baru berhasil dibuat!');
    }

    public function edit(Promo $promo)
    {
        return view('promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promos,code,' . $promo->id,
            'type' => 'required|in:percentage,nominal',
            'value' => 'required|integer|min:1',
        ]);

        $promo->update($request->all());

        return redirect()->route('promos.index')->with('success', 'Promo berhasil diperbarui!');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('promos.index')->with('success', 'Promo berhasil dihapus!');
    }
}
