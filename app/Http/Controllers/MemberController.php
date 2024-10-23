<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use App\Models\LevelHarga;
use App\Models\LevelUser;
use App\Models\Member;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Throwable;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $member = Member::orderBy('id', 'desc')->
                            with(['levelharga', 'toko', 'jenis_barang'])->get();
        $jenis_barang = JenisBarang::all();
        $toko = Toko::all();
        $levelharga = LevelHarga::all();
        $jenis_barang = JenisBarang::all();

        $selected_levels = [];
        foreach ($member as $mbr) {
            if (!empty($mbr->level_info)) {
                foreach (json_decode($mbr->level_info, true) as $info) {
                    preg_match('/(\d+) : (\d+)/', $info, $matches);
                    $selected_levels[$mbr->id][$matches[1]] = $matches[2]; // $matches[1] adalah id_jenis_barang, $matches[2] adalah id_level_harga
                }
            }
        }

        return view('master.member.index', compact('member', 'toko', 'jenis_barang', 'levelharga', 'jenis_barang', 'selected_levels'));
    }

    public function getLevelHarga($id_toko)
    {
        // Ambil data toko berdasarkan id_toko
        $toko = Toko::where('id', $id_toko)->first();

        // Jika toko ditemukan
        if ($toko) {
            // Decode kolom id_level_harga yang disimpan sebagai JSON
            $levelHargaIds = json_decode($toko->id_level_harga, true);

            // Ambil data level_harga berdasarkan id yang terdecode
            $levelHarga = LevelHarga::whereIn('id', $levelHargaIds)->get();

            // Return sebagai JSON untuk digunakan di AJAX
            return response()->json($levelHarga);
        }

        // Jika toko tidak ditemukan
        return response()->json(['error' => 'Toko tidak ditemukan'], 404);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $toko = Toko::all();
        $leveluser = LevelUser::all();
        $levelharga = LevelHarga::all();
        $jenis_barang = JenisBarang::all();
        return view('master.member.create', compact('toko', 'leveluser', 'levelharga', 'jenis_barang'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $validatedData = $request->validate(
            [
                'id_toko' => 'required',
                'nama_member' => 'required',
                'no_hp' => 'required',
                'alamat' => 'required'
            ],
            [
                'id_toko.required' => 'Toko Wajib diisi.',
                'nama_member.required' => 'Nama Member tidak boleh kosong',
                'no_hp.required' => 'No Hp Wajib diisi',
                'alamat.required' => 'Alamat Wajib diisi',
            ]
        );
            $level_harga = $request->input('level_harga');

            foreach ($level_harga as $jenis_barang_id => $level_harga_id) {
                if (!empty($level_harga_id)) {
                    $levelInfo[] = "{$jenis_barang_id} : {$level_harga_id}";
                }
            }

        try {
            Member::create([
            'id_toko' => $request->id_toko,
            'nama_member' => $request->nama_member,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'level_info' => json_encode($levelInfo),
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('master.member.index')->with('error', $th->getMessage())->withInput();
        }
        return redirect()->route('master.member.index')->with('success', 'Sukses menambahkan Member Baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $member = Member::with('levelharga')->findOrFail($id);
        $jenis_barang = JenisBarang::all();
        $levelharga = LevelHarga::all();

        // Data yang sudah ada diambil dari level_data
        $selected_levels = $member->level_data; // Asumsikan level_data menyimpan data yang terkait

        return view('member.edit', compact('member', 'jenis_barang', 'levelharga', 'selected_levels'));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Validasi input
    $validatedData = $request->validate(
        [
            'id_toko' => 'required',
            'nama_member' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required'
        ],
        [
            'id_toko.required' => 'Toko Wajib diisi.',
            'nama_member.required' => 'Nama Member tidak boleh kosong',
            'no_hp.required' => 'No Hp Wajib diisi',
            'alamat.required' => 'Alamat Wajib diisi',
        ]
    );

    $level_harga = $request->input('level_harga');

    // Persiapkan level_info untuk disimpan
    $levelInfo = []; // Pastikan array ini diinisialisasi
    foreach ($level_harga as $jenis_barang_id => $level_harga_id) {
        if (!empty($level_harga_id)) {
            $levelInfo[] = "{$jenis_barang_id} : {$level_harga_id}";
        }
    }

    try {
        // Temukan Member yang akan diupdate
        $member = Member::findOrFail($id);

        // Update data member
        $member->update([
            'id_toko' => $request->id_toko,
            'nama_member' => $request->nama_member,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'level_info' => json_encode($levelInfo),
        ]);
    } catch (\Throwable $th) {
        return redirect()->route('master.member.index')->with('error', $th->getMessage())->withInput();
    }

    return redirect()->route('master.member.index')->with('success', 'Sukses memperbarui Member');
}


    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        DB::beginTransaction();
        $member = Member::findOrFail($id);
        try {
            $member->delete();
        DB::commit();

        return redirect()->route('master.member.index')->with('success', 'Berhasil menghapus Data Member');
        } catch (\Throwable $th) {
        DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Data Member ' . $th->getMessage());
        }
    }
}
