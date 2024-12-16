<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPembelianBarang;
use App\Models\DetailStockBarang;
use App\Models\DetailToko;
use App\Models\LevelHarga;
use App\Models\StockBarang;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockBarangController extends Controller
{
    public function getstockbarang(Request $request)
{
    $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
    $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

    // Periksa apakah toko ID tersedia di request
    $idToko = $request->input('id_toko');

    if ($idToko == 1) {
        // Ambil data stok barang dari tabel 'stock_barang'
        $query = StockBarang::with(['barang', 'toko'])
                            ->orderBy('id', $meta['orderBy']);
    } else {
        // Ambil stok barang dari tabel 'detail_toko' untuk toko selain ID = 1
        $query = DetailToko::with(['barang', 'toko'])
                           ->where('id_toko', '!=', 1)
                           ->orderBy('id', $meta['orderBy']);
    }

    // Tambahkan filter pencarian jika ada
    if (!empty($request['search'])) {
        $searchTerm = trim(strtolower($request['search']));

        $query->where(function ($query) use ($searchTerm) {
            $query->orWhereHas('barang', function ($subquery) use ($searchTerm) {
                $subquery->whereRaw("LOWER(nama_barang) LIKE ?", ["%$searchTerm%"]);
            });
        });
    }

    // Filter berdasarkan tanggal
    if ($request->has('startDate') && $request->has('endDate')) {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Ambil data dengan pagination
    $data = $query->paginate($meta['limit']);

    $paginationMeta = [
        'total'        => $data->total(),
        'per_page'     => $data->perPage(),
        'current_page' => $data->currentPage(),
        'total_pages'  => $data->lastPage()
    ];

    // Format data untuk respons
    $mappedData = collect($data->items())->map(function ($item) {
        return [
            'id' => $item->id,
            'nama_barang' => $item->barang->nama_barang ?? null,
            'hpp_baru' => $item->hpp_baru,
            'stock' => $item->stock,
        ];
    });

    // Jika tidak ada data, kembalikan respons error
    if ($mappedData->isEmpty()) {
        return response()->json([
            'status_code' => 400,
            'errors' => true,
            'message' => 'Tidak ada data'
        ], 400);
    }

    // Respons JSON
    return response()->json([
        'data' => $mappedData,
        'status_code' => 200,
        'errors' => false,
        'message' => 'Sukses',
        'pagination' => $paginationMeta
    ], 200);
}

    public function index()
    {
        $user = Auth::user(); // Mendapatkan user yang sedang login
        // Mengambil stock barang beserta relasi ke barang dan toko
        $stock = StockBarang::with(['barang', 'toko'])
                            ->orderBy('id', 'desc')
                            ->get();

                            // Ambil stok barang dari tabel 'detail_toko' untuk semua toko kecuali id = 1
        $stokTokoLain = DetailToko::with('barang', 'toko')
                            ->where('id_toko', '!=', 1)
                            ->get();

        // Ambil semua toko
        $toko = Toko::all();
        $levelharga = LevelHarga::all();
        $barang = Barang::all();

        return view('master.stockbarang.index', compact('stock', 'stokTokoLain', 'toko', 'levelharga', 'barang'));
    }

    public function getItem($id_barang)
    {
        $item = StockBarang::where('id_barang', $id_barang)->first();

        // $detail = DetailStockBarang::where('id_barang', $id_barang)->get();

        // Jika ditemukan, kembalikan respons JSON
        if ($item) {
            return response()->json([
                'nama_barang' => $item->nama_barang
            ]);
        } else {
            return response()->json(['error' => 'Item not found'], 404);
        }
    }

    public function create()
    {
        return view('master.stockbarang.create');
    }

    public function getStockDetails($id_barang)
    {
        // Ambil data stock barang yang sesuai
        $stockBarang = StockBarang::where('id_barang', $id_barang)->first();

        $barang = Barang::where('id', $id_barang)->first();

        // Ambil semua detail pembelian dengan status 'success' untuk barang tersebut
        $successfulDetails = DetailPembelianBarang::where('id_barang', $id_barang)->get();

        $hpp0 = $successfulDetails->sum('harga_barang');

        // Hitung total harga dan total qty dari pembelian yang sudah 'success'
        $totalHargaSuccess = $successfulDetails->sum('total_harga');
        $totalQtySuccess = $successfulDetails->sum('qty');

        // Hitung HPP baru
        if ($totalQtySuccess > 0) {
            $hppBaru = $totalHargaSuccess / $totalQtySuccess;
        } else {
            $hppBaru = 0;
        }

        $level_harga = [];
        if ($barang && $barang->level_harga) {
            $decoded_level_harga = json_decode($barang->level_harga, true);
            foreach ($decoded_level_harga as $item) {
                list($level_name, $level_value) = explode(' : ', $item);
                $level_harga[$level_name] = $level_value;
            }
        }

        if ($stockBarang) {
            return response()->json([
                'stock' => $stockBarang->stock,
                'hpp_awal' => $hppBaru,
                'hpp_baru' => 0,
                'total_harga_success' => $totalHargaSuccess,
                'total_qty_success' => $totalQtySuccess,
                'level_harga' => $level_harga,
            ]);
        } else {
            return response()->json([
                'stock' => 0,
                'hpp_awal' => 0,
                'hpp_baru' => $hpp0,
                'total_harga_success' => $totalHargaSuccess,
                'total_qty_success' => $totalQtySuccess,
                'level_harga' => [],
            ]);
        }
    }

    public function updateLevelHarga(Request $request)
{
    $id_barang = $request->input('id_barang'); // Mengambil ID barang dari request

    try {
        DB::beginTransaction();

        // Ambil data barang berdasarkan ID
        $barang = Barang::findOrFail($id_barang);

        // Ambil semua level harga yang dikirim dari form
        $levelNamas = $request->input('level_nama', []);
        $levelHargas = $request->input('level_harga', []);

        $levelHargaBarang = [];

        // Loop untuk memperbarui level harga berdasarkan input dari form
        foreach ($levelHargas as $index => $hargaLevel) {
            $levelNama = $levelNamas[$index] ?? 'Level ' . ($index + 1);

            // Jika harga level tidak kosong, hapus pemisah ribuan dan masukkan ke array level harga
            if (!is_null($hargaLevel)) {
                // Hapus pemisah ribuan dari hargaLevel
                $hargaLevel = str_replace(',', '', $hargaLevel);

                $levelHargaBarang[] = "{$levelNama} : {$hargaLevel}";
            }
        }

        // Simpan level harga yang baru dalam format JSON
        $barang->level_harga = json_encode($levelHargaBarang);
        $barang->save(); // Simpan perubahan ke database

        DB::commit(); // Commit transaksi jika semuanya berhasil

        return redirect()->back()->with('success', 'Level harga berhasil diperbarui');
    } catch (\Exception $e) {
        DB::rollback(); // Rollback jika ada error
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}
