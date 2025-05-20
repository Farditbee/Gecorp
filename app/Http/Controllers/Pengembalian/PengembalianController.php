<?php

namespace App\Http\Controllers\Pengembalian;

use App\Http\Controllers\Controller;
use App\Models\DetailKasir;
use App\Models\DetailToko;
use App\Models\Kasir;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Pengembalian Barang',
        ];
    }

    public function delete(Request $request)
    {
        // 1. Validasi request
        $request->validate([
            'id' => 'required|integer',
            'id_toko' => 'required|integer',
        ]);

        $id = $request->id;
        $idToko = $request->id_toko;

        // 2. Cari data detail_kasir
        $detailKasir = DetailKasir::find($id);

        if (!$detailKasir) {
            return response()->json(['message' => 'Data detail kasir tidak ditemukan.'], 404);
        }

        $idKasir = $detailKasir->id_kasir;

        // 3. Update qty di tabel detail_toko
        $detailToko = DetailToko::where('qrcode', $detailKasir->detailPembelian->qrcode)
            ->where('id_toko', $idToko)
            ->first();

        if ($detailToko) {
            $detailToko->qty += $detailKasir->qty;
            $detailToko->save();
        }

        // 4. Cek apakah hanya ada satu data detail_kasir dengan id_kasir yang sama
        $jumlahDetailKasir = DetailKasir::where('id_kasir', $idKasir)->count();

        if ($jumlahDetailKasir == 1) {
            // Hapus detail_kasir dan kasir (karena hanya satu)
            $detailKasir->delete();

            $kasir = Kasir::find($idKasir);
            if ($kasir) {
                $kasir->delete();
            }

            return response()->json(['message' => 'Detail kasir dan kasir berhasil dihapus.']);
        } else {
            // Jika lebih dari 1, update kasir dan hapus hanya detail_kasir ini saja
            $kasir = Kasir::find($idKasir);
            if ($kasir) {
                $kasir->total_item -= $detailKasir->qty;
                $kasir->total_nilai -= ($detailKasir->qty * $detailKasir->harga);
                $kasir->kembalian = $kasir->jml_bayar - $kasir->total_nilai;
                $kasir->save();
            }

            $detailKasir->delete();

            return response()->json(['message' => 'Detail kasir berhasil dihapus dan kasir diperbarui.']);
        }
    }

}
