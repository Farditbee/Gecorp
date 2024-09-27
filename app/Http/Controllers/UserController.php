<?php

namespace App\Http\Controllers;

use App\Models\LevelUser;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class UserController extends Controller
{
    public function index()
    {

        $user = User::with('leveluser', 'toko')
                    ->orderBy('id', 'desc')->get();
        return view('master.user.index', compact('user'));
    }

    public function create()
    {
        $toko = Toko::all();
        $leveluser = LevelUser::all();
        return view('master.user.create', compact('toko', 'leveluser'), [
            'leveluser' => LevelUser::all()->pluck('nama_level','id'),
            'toko' => Toko::all()->pluck('nama_toko', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_toko' => 'required',
            'id_level' => 'required',
            'nama' => 'required|max:255',
            'username' => 'required|max:255',
            'password' => 'required|min:8|regex:/([0-9])/',
            'email' => 'required|max:255',
            'alamat' => 'required|max:255',
            'no_hp' => 'required|max:255',
        ],[
            'id_toko.required' => 'Nama Toko tidak boleh kosong.',
            'id_level.required' => 'Nama Level tidak boleh kosong.',
            'nama.required' => 'Nama tidak boleh kosong.',
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung minimal satu angka.',
            'email.required' => 'Email tidak boleh kosong.',
            'alamat.required' => 'Alamat tidak boleh kosong.',
            'no_hp.required' => 'No Hp tidak boleh kosong.',
        ]
    );
        try {
            User::create([
                'id_toko' => $request->id_toko,
                'id_level' => $request->id_level,
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'email' => $request->email,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);
        }catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.user.index')->with('success', 'Berhasil menambahkan User Baru');
    }

    public function edit(String $id)
    {
        $user = User::with(['leveluser', 'toko'])->findOrFail($id);

        // dd($user);
        $toko = Toko::all();
        $leveluser = LevelUser::all();
        return view('master.user.edit', compact('user', 'toko', 'leveluser'));
    }

    public function update(Request $request, String $id)
    {
        $user = User::findOrFail($id);
        try {
            $user->update([
                'id_toko' => $request->id_toko,
                'id_level' => $request->id_level,
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'email' => $request->email,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.user.index')->with('success', 'Sukses Mengubah Data User');
    }

    public function delete(String $id)
    {
        DB::beginTransaction();
        $user = User::findOrFail($id);
        try {
            $user->delete();
        DB::commit();
        return redirect()->route('master.user.index')->with('success', 'Sukses menghapus Data User');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Toko' . $th->getMessage());
        }
    }
}
