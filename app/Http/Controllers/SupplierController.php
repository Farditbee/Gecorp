<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{

    public function index()
    {
        $supplier = Supplier::orderBy('id', 'desc')->get();
        return view('master.supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('master.supplier.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validatedData = $request->validate([
            'nama_supplier' => 'required|max:255',
            'email' => 'required|max:255',
            'alamat' => 'required|max:255',
            'contact' => 'required|max:255',
        ],[
            'nama_supplier.required' => 'Nama Supplier tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'alamat.required' => 'Alamat tidak boleh kosong.',
            'contact.required' => 'Contact tidak boleh kosong.',
        ]);

        try {
            Supplier::create([
                'nama_supplier' => $request->nama_supplier,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'contact' => $request->contact,
            ]);

            ActivityLogger::log('Tambah Supplier', $data);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }

        return redirect()->route('master.supplier.index')->with('success', 'Berhasil menambahkan Supplier Baru');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id )
    {
        $supplier = Supplier::findOrFail($id);
        return view('master.supplier.edit', compact('supplier'));
    }


    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        try {
           $supplier->update([
            'nama_supplier'=> $request->nama_supplier,
            'email'=> $request->email,
            'alamat'=> $request->alamat,
            'contact'=> $request->contact,
           ]);
     } catch (\Throwable $th) {
        return redirect()->back()->with('error', $th->getMessage())->withInput();
    }
    return redirect()->route('master.supplier.index')->with('success', 'Sukses Mengubah Data Supplier');
    }

    public function delete(String $id)
    {
        DB::beginTransaction();
        $supplier = Supplier::findOrFail($id);
        try {
            $supplier->delete();
        DB::commit();

        return redirect()->route('master.supplier.index')->with('success', 'Berhasil menghapus Data Supplier');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Supplier ' . $th->getMessage());
        }

    }
}
