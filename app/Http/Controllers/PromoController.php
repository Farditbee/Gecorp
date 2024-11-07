<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        // Ambil ID barang yang memiliki promo dengan status "ongoing"
        $ongoingPromoBarangIds = Promo::where('status', 'ongoing')
                                        ->pluck('id_barang')
                                        ->toArray();

        // Ambil barang yang tidak memiliki promo "ongoing" atau memiliki promo dengan status selain "ongoing"
        $barang = Barang::whereNotIn('id', $ongoingPromoBarangIds)
                            ->orWhereHas('promo', function ($query) {
                                $query->where('status', '!=', 'ongoing');
                            })
                            ->get();

        return view('master.promo.index', compact('barang'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'barang' => 'required|exists:barang,id', // Validasi barang harus ada di tabel barang
            'minimal' => 'required|integer|min:0',
            'jumlah' => 'required|integer|min:0',
            'diskon' => 'required|integer|between:0,100',
            'dari' => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ]);

        try {
            $barang = Barang::findOrFail($validatedData['barang']);

            Promo::create([
                'id_barang' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'minimal' => $validatedData['minimal'],
                'jumlah' => $validatedData['jumlah'],
                'diskon' => $validatedData['diskon'],
                'dari' => $validatedData['dari'],
                'sampai' => $validatedData['sampai'],
            ]);
    
            return redirect()->back()->with('success', 'Promo berhasil disimpan!');
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', 'Something gone wrong. ' . $th->getMessage());
        }

    }
}
