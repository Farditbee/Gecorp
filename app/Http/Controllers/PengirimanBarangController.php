<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelianBarang;
use App\Models\DetailPengirimanBarang;
use App\Models\DetailToko;
use App\Models\LevelUser;
use App\Models\PembelianBarang;
use App\Models\PengirimanBarang;
use App\Models\StockBarang;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengirimanBarangController extends Controller
{
    public function index()
    {
        $toko = Toko::all();
        $barang =  Barang::all();
        $user = User::all();
        $pengiriman_barang = PengirimanBarang::orderBy('id', 'desc')->get();
        return view('transaksi.pengirimanbarang.index', compact('toko', 'barang', 'user', 'pengiriman_barang'));
    }

    public function detail(string $id)
    {
        $detail_pengiriman = DetailPengirimanBarang::where('id_pengiriman_barang', $id)->get();  // Ambil data pengiriman dari database
        // $selectedTokoId = $detail_pengiriman->toko_pengirim;  // Asumsikan kamu menyimpan id toko pengirim di dalam pengiriman
        $pengiriman_barang = PengirimanBarang::findOrFail($id);
        // $pengiriman_barang = PengirimanBarang::all();

        return view('transaksi.pengirimanbarang.detail', compact('detail_pengiriman', 'pengiriman_barang'));
    }

    public function create(Request $request)
    {
        $toko = Toko::all();
        // $user = User::all();
        $detail_toko = DetailToko::all();
        // $barang = Barang::all();
        $stock = StockBarang::all();
        // $barangs = collect();

        // $pengiriman = PengirimanBarang::where('id', $id)->first();

        return view('transaksi.pengirimanbarang.create', compact('toko', 'stock', 'detail_toko'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // dd($request);

            // Simpan data dasar pengiriman
            $pengiriman_barang = PengirimanBarang::create([
                'no_resi' => $request->no_resi,
                'toko_pengirim' => $request->toko_pengirim,
                'nama_pengirim' => $request->nama_pengirim,
                'ekspedisi' => $request->ekspedisi,
                'toko_penerima' => $request->toko_penerima,
                'tgl_kirim' => $request->tgl_kirim
            ]);

            DB::commit();
            // Redirect ke tab "detail pengiriman" dengan data pengiriman yang baru disimpan
            return redirect()->route('master.pengirimanbarang.create')
                ->with('tab', 'detail')
                ->with('pengiriman_barang', $pengiriman_barang);
                // ->with('stock', $stock);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function getUsersByToko($id_toko)
    {
        $users = User::where('id_toko', $id_toko)
                    ->where('id_level', 2) // Tambahkan kondisi ini untuk filter admin
                    ->get();
        if ($users->isEmpty()) {
            return response()->json(['error' => 'No users found'], 404);
        }
        return response()->json($users);
    }

    public function getBarangStock($id_barang, $id_toko)
    {
        // Mengambil barang yang tersedia berdasarkan id_toko dari tabel StockBarang
        if ($id_toko == 1) {
            $barangs = StockBarang::all();

            return response()->json($barangs);
        } else {
            $barangs = DetailToko::where('id_barang', $id_barang)
                                ->where('id_toko', $id_toko)
                                ->first();

            return response()->json($barangs);
        }
    }

    public function getHargaBarang($id_barang, $id_toko)
    {
        if ($id_toko == 1){
            $stock = StockBarang::where('id_barang', $id_barang)->first();

            if ($stock) {
                return response()->json(['harga' => $stock->hpp_baru]);
            } else {
                return response()->json(['error' => 'Barang tidak ditemukan'], 404);
            }
        } else {
            $detail = DetailToko::where('id_barang', $id_barang)
                                ->where('id_toko', $id_toko) // Menyesuaikan dengan toko yang bersangkutan
                                ->first();
            if ($detail) {
                // return response()->json(['harga' => $detail->harga]);
                return response()->json($detail);
            } else {
                return response()->json(['error' => 'Barang tidak ditemukan'], 404);
            }
        }
        // Ambil harga dari tabel stock_barang berdasarkan id_barang
    }

    // public function getHargaBarangs($id_barang)
    // {
    //     // Ambil harga dari tabel detail_toko berdasarkan id_barang
    //     $detail = DetailToko::where('id_barang', $id_barang)->first();

    //     if ($detail) {
    //         return response()->json(['harga' => $detail->harga]);
    //     } else {
    //         return response()->json(['error' => 'Barang tidak ditemukan'], 404);
    //     }
    // }

    public function update(Request $request, $id)
    {
        // dd($request);
        $idBarangs = $request->input('id_barang', []);
        $qtys = $request->input('qty', []);
        $hargaBarangs = $request->input('harga', []);

        foreach ($idBarangs as $index => $id_barang) {
            $qty = $qtys[$index] ?? null;
            $harga = $hargaBarangs[$index] ?? null;

            if (is_null($qty) || is_null($harga)) {
                continue;
            }

            if ($qty <= 0 || $harga <= 0) {
                return redirect()->back()->with('error', 'Failed: Data harap diisi dengan benar.');
            }
        }

        try {
            DB::beginTransaction();

            $pengiriman_barang = PengirimanBarang::findOrFail($id);

            $totalItem = 0;
            $totalNilai = 0;

            $count = count($idBarangs);
            for ($i = 0; $i < $count; $i++) {
                $id_barang = $idBarangs[$i];
                $qty = $qtys[$i] ?? null;
                $harga = $hargaBarangs[$i] ?? null;

                if (is_null($qty) || is_null($harga)) {
                    continue;
                }

                // if (!$barang) {
                //     return redirect()->back()->with('error', 'Barang dengan ID ' . $id_barang . ' tidak ditemukan.');
                // }

                if ($id_barang && $qty > 0 && $harga > 0) {
                    $barang = StockBarang::where('id_barang', $id_barang)->first();

                    $detail = DetailPengirimanBarang::updateOrCreate(
                        [
                            'id_pengiriman_barang' => $pengiriman_barang->id,
                            'id_barang' => $id_barang,
                        ],
                        [
                            'nama_barang' => $barang->nama_barang,
                            'qty' => $qty,
                            'harga' => $harga,
                            'total_harga' => $qty * $harga,
                        ]
                    );

                    $totalItem += $detail->qty;
                    $totalNilai += $detail->total_harga;
                }
            }

            $pengiriman_barang->total_item = $totalItem;
            $pengiriman_barang->total_nilai = $totalNilai;
            $pengiriman_barang->save();

            DB::commit();

            return redirect()->route('master.pengirimanbarang.index')->with('success', 'Data Pengiriman Barang berhasil Ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Failed to update pengeriman barang. ' . $e->getMessage());
        }
    }


// public function updateStatus(Request $request, $id)
// {
//     try {
//         DB::beginTransaction();

//         $detailPengiriman = DetailPengirimanBarang::findOrFail($id);
//         $status = $request->input('status');

//         // Jika status diubah menjadi "success"
//         if ($status === 'success' && $detailPengiriman->status !== 'success') {
//             // Mengurangi stock barang berdasarkan qty
//             $stockBarang = StockBarang::find($detailPengiriman->id_barang);
//             if ($stockBarang) {
//                 $stockBarang->stock -= $detailPengiriman->qty;
//                 $stockBarang->save();
//             }
//         }

//         // Update status pengiriman
//         $detailPengiriman->status = $status;
//         $detailPengiriman->save();

//         DB::commit();

//         return redirect()->back()->with('success', 'Status berhasil diperbarui.');
//     } catch (\Exception $e) {
//         DB::rollback();
//         return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
//     }
// }


// Method untuk menyimpan detail barang
// public function update(Request $request, PengirimanBarang $pengiriman_barang)
// {
//     try {
//         DB::beginTransaction();

//         // Simpan detail barang yang dikirim
//         foreach ($request->id_barang as $key => $id_barang) {
//             $pengiriman_barang->barang()->create([
//                 'id_barang' => $id_barang,
//                 'qty' => $request->qty[$key],
//                 'harga' => $request->harga[$key],
//                 'total_harga' => $request->qty[$key] * $request->harga[$key],
//             ]);
//         }

//         DB::commit();

//         return redirect()->route('master.pengirimanbarang.index')->with('success', 'Detail pengiriman berhasil ditambahkan.');

//     } catch (\Throwable $th) {
//         DB::rollBack();
//         return redirect()->back()->with('error', $th->getMessage());
//     }
// }

public function edit($id)
{
    $pengiriman_barang = PengirimanBarang::with('detail')->findOrFail($id);

    return view('transaksi.pengirimanbarang.edit', compact('pengiriman_barang', ));
}

    public function updateStatus(Request $request, $id)
    {
        // Ambil data pengiriman_barang
        $pengiriman_barang = PengirimanBarang::findOrFail($id);
        $toko_pengirim = $pengiriman_barang->toko_pengirim;
        $toko_penerima = $pengiriman_barang->toko_penerima;

        $detail_ids = $request->input('detail_ids', []);
        $statuses = $request->input('status_detail', []);

        try{
            DB::beginTransaction();

            foreach ($detail_ids as $key => $detail_id) {
                $detail = DetailPengirimanBarang::findOrFail($detail_id);

                if (isset($statuses[$key]) && $statuses[$key] == 'success' && $detail->status != 'success') {

                    // Update the status in detail pembelian
                    $detail->status = 'success';
                    $detail->save();

                if($toko_pengirim != 1){
                    $detailTokoPengirim = DetailToko::where('id_toko', $toko_pengirim)
                                                    ->where('id_barang', $detail->id_barang)
                                                    ->first();

                    if($detailTokoPengirim){
                        if($detailTokoPengirim->qty >= $detail->qty){
                            $detailTokoPengirim->qty -= $detail->qty;
                            $detailTokoPengirim->save();
                        } else {
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Stok tidak mencukupi di toko pengirim untuk barang dengan ID: ' . $detail->id_barang);
                        }
                    } else {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Barang dengan ID: ' . $detail->id_barang . ' tidak ditemukan di detail_toko pengirim.');
                    }
                } else {
                        $stockBarang = StockBarang::where('id_barang', $detail->id_barang)->first();
                    if ($stockBarang) {
                        if ($stockBarang->stock >= $detail->qty) {
                            $stockBarang->stock -= $detail->qty;
                            $stockBarang->save();
                        } else {
                            // Jika stok tidak mencukupi, rollback transaksi
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk barang: ' . $stockBarang->nama_barang);
                        }
                    }
                }

                $detailToko = DetailToko::where('id_toko', $toko_penerima)
                                        ->where('id_barang', $detail->id_barang)
                                        ->first();
                if($detailToko){
                    $detailToko->qty += $detail->qty;
                    $detailToko->save();
                }else {
                    DetailToko::create([
                        'id_toko' => $toko_penerima,
                        'id_barang' => $detail->id_barang,
                        'qty' => $detail->qty,
                        'harga' => $detail->harga
                    ]);
                }
            }
        }

        // Cek apakah semua barang dalam detail pembelian memiliki status 'success'
        $allSuccess = $pengiriman_barang->detail()->where('status', '!=', 'success')->count() === 0;

        if ($allSuccess) {
            // Jika semua barang sudah success, ubah status pembelian jadi success
            $pengiriman_barang->status = 'success';
            $pengiriman_barang->tgl_terima = now();
            $pengiriman_barang->save();
        }

        DB::commit();  // Commit transaction setelah semua operasi berhasil
        return redirect()->route('master.pengirimanbarang.index')->with('success', 'Status Berhasil Diubah');
    } catch (\Exception $e) {
        DB::rollBack();  // Rollback jika terjadi error
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function destroy(string $id)
    {
        //
    }
}

