<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisBarangController extends Controller
{
    public function index()
    {
        $jenisbarang = JenisBarang::orderBy('id', 'desc')->get();
        return view('master.jenisbarang.index', compact('jenisbarang'));
    }

    public function create()
    {
        return view('master.jenisbarang.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jenis_barang' => 'required|max:255',
        ],[
            'nama_jenis_barang.required' => 'Jenis Barang tidak boleh kosong.',
        ]);
        try {

            JenisBarang::create([
                'nama_jenis_barang' => $request->nama_jenis_barang,
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.jenisbarang.index')->with('success', 'Sukses menambahkan Jenis Barang Baru');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id )
    {
        $jenisbarang = JenisBarang::findOrFail($id);
        return view('master.jenisbarang.edit', compact('jenisbarang'));
    }

    public function update(Request $request, string $id)
    {
        $jenisbarang = JenisBarang::findOrFail($id);
        try {
           $jenisbarang->update([
            'nama_jenis_barang'=> $request->nama_jenis_barang,
           ]);
           return redirect()->route('master.jenisbarang.index')->with('success', 'Sukses Mengubah Data Jenis Barang');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        $jenisbarang = JenisBarang::findOrFail($id);
        try {
            $jenisbarang->delete();
        DB::commit();

        return redirect()->route('master.jenisbarang.index')->with('success', 'Sukses menghapus Data Jenis Barang');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Jenis Barang ' . $th->getMessage());
        }
    }
}
