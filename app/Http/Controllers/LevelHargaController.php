<?php

namespace App\Http\Controllers;

use App\Models\LevelHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelHargaController extends Controller
{

    public function index()
    {
        $levelharga = LevelHarga::orderBy('id', 'desc')->get();
        return view('master.levelharga.index', compact('levelharga'));
    }

    public function create()
    {
        return view('master.levelharga.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_level_harga' => 'required|max:255',
        ],[
            'nama_level_harga.required' => 'Nama level harga tidak boleh kosong.',
        ]);
        try {
            LevelHarga::create([
                'nama_level_harga' => $request->nama_level_harga,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.levelharga.index')->with('success', 'Berhasil menambahkan Level Baru');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $levelharga = LevelHarga::findOrFail($id);
        return view('master.levelharga.edit', compact('levelharga'));
    }

    public function update(Request $request, string $id)
    {
        $levelharga = LevelHarga::findOrFail($id);
        try {
           $levelharga->update([
            'nama_level_harga'=> $request->nama_level_harga,
           ]);
     } catch (\Throwable $th) {
        return redirect()->back()->with('error', $th->getMessage())->withInput();
    }
    return redirect()->route('master.levelharga.index')->with('success', 'Sukses Mengubah Data Level Harga');
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        $levelharga = LevelHarga::findOrFail($id);
        try {
            $levelharga->delete();
        DB::commit();

        return redirect()->route('master.levelharga.index')->with('success', 'Berhasil menghapus Data Level Harga');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Level Harga ' . $th->getMessage());
        }
    }
}
