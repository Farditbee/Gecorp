<?php

namespace App\Http\Controllers;

use App\Models\LevelUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\String_;

class LevelUserController extends Controller
{
    public function index()
    {
        $leveluser = LevelUser::orderBy('id', 'desc')->get();
        return view ('master.leveluser.index', compact('leveluser'));
    }

    public function create()
    {
        return view('master.leveluser.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_level' => 'required|max:255',
            'informasi' => 'required|max:255',
        ],[
            'nama_level.required' => 'Nama Level User tidak boleh kosong.',
            'informasi.required' => 'Informasi tidak boleh kosong.',
        ]);
        try {

            LevelUser::create([
                'nama_level' => $request->nama_level,
                'informasi' => $request->informasi,
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.leveluser.index')->with('success', 'Sukses menambahkan Karyawan Baru');
    }

    public function edit(String $id)
    {
        $leveluser = LevelUser::findOrFail($id);
        return view('master.leveluser.edit', compact('leveluser'));
    }

    public function update(Request $request, string $id)
    {
        $leveluser = LevelUser::findOrFail($id);
        try {
           $leveluser->update([
            'nama_level'=> $request->nama_level,
            'informasi'=> $request->informasi,
           ]);
     } catch (\Throwable $th) {
        return redirect()->back()->with('error', $th->getMessage())->withInput();
    }
    return redirect()->route('master.leveluser.index')->with('success', 'Sukses Mengubah Data Level User');
    }

    public function delete(String $id)
    {
        DB::beginTransaction();
        $leveluser = LevelUser::findOrFail($id);
        try {
        $leveluser->delete();
        DB::commit();

        return redirect()->route('master.leveluser.index')->with('success', 'Sukses menghapus Data Level User');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Level User ' . $th->getMessage());
        }
    }
}
