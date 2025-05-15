<?php

namespace App\Http\Controllers;

use App\Models\DetailKasbon;
use App\Models\Kasbon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasbonController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Kasbon Member',
            'Detail Kasbon Member',
        ];
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[1]];

        return view('master.kasbon.index', compact('menu'));
    }

    public function detail($id)
    {
        $menu = [$this->title[1], $this->label[1]];
        $kasbon = Kasbon::find($id);
        $dt_kasbon = DetailKasbon::where('id_kasbon', $id)->latest()->get();

        return view('master.kasbon.detail', compact('menu', 'kasbon', 'dt_kasbon'));
    }

    public function bayar(Request $request)
    {
        $request->validate([
            'id_kasbon' => 'required',
            'bayar' => 'required|numeric',
            'tipe_bayar' => 'required|in:Tunai,Non-Tunai',
            'id_member' => 'required',
        ]);

        $tgl_bayar = Carbon::now();

        $kasbon = Kasbon::where('id', $request->id_kasbon)
            ->where('id_member', $request->id_member)
            ->first();

        if (!$kasbon) {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Data kasbon tidak ditemukan'
            ], 400);
        }

        if ($request->bayar > $kasbon->utang_sisa) {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Jumlah pembayaran melebihi sisa utang'
            ], 400);
        }

        try {
            DB::beginTransaction();

            if ($request->tipe_bayar == 'Non-Tunai') {
                return response()->json([
                    'status_code' => 400,
                    'errors' => true,
                    'message' => 'Metode pembayaran Non-Tunai belum didukung'
                ], 400);
            }

            // Simpan ke detail_kasbon
            $kasbon->detailKasbon()->create([
                'tgl_bayar' => $tgl_bayar,
                'bayar' => $request->bayar,
                'tipe_bayar' => $request->tipe_bayar,
            ]);

            // Kurangi sisa utang
            $kasbon->utang_sisa -= $request->bayar;

            // Jika sisa utang 0, ubah status jadi Lunas
            if ($kasbon->utang_sisa == 0) {
                $kasbon->status = 'L';
            }

            $kasbon->save();

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'errors' => false,
                'message' => 'Pembayaran berhasil disimpan'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status_code' => 500,
                'errors' => true,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage(),
            ], 500);
        }
    }

}
