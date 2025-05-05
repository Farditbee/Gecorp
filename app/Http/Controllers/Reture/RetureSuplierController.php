<?php

namespace App\Http\Controllers\Reture;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DataReture;
use App\Models\DetailKasir;
use App\Models\DetailPembelianBarang;
use App\Models\DetailRetur;
use App\Models\DetailStockBarang;
use App\Models\StockBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetureSuplierController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Retur ke Supplier',
        ];
    }

    public function index()
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }

        $menu = [$this->title[0], $this->label[3]];

        return view('reture.suplier.index', compact('menu'));
    }

    public function get(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = DataReture::where('id_supplier', '!=', null);

        $query->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(id_supplier) LIKE ?", ["%$searchTerm%"]);
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $query->join('supplier', 'data_retur.id_supplier', '=', 'supplier.id')
                ->select('data_retur.*', 'supplier.nama_supplier');

        $data = $query->paginate($meta['limit']);

        $paginationMeta = [
            'total'        => $data->total(),
            'per_page'     => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages'  => $data->lastPage()
        ];

        $data = [
            'data' => $data->items(),
            'meta' => $paginationMeta
        ];

        if (empty($data['data'])) {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Tidak ada data'
            ], 400);
        }

        $mappedData = collect($data['data'])->map(function ($item) {
            return [
                'id' => $item['id'],
                'id_supplier' => $item['id_supplier'],
                'nama_supplier' => $item['nama_supplier'],
                'no_nota' => $item['no_nota'],
                'tgl_retur' => $item['tgl_retur'],
                'status' => $item['status'],
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Sukses',
            'pagination' => $data['meta']
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_nota' => 'required|string',
            'id_retur' => 'required|array',
            'id_transaksi' => 'required|array',
            'id_barang' => 'required|array',
            'metode_reture' => 'required|array',
            'qty_acc' => 'required|array',
            'qrcode' => 'required|array',
        ]);

        ActivityLogger::log('Tambah Reture Supplier', $request->all());

        try {
            DB::beginTransaction();

            foreach ($request->id_retur as $index => $idRetur) {
                $idTransaksi = $request->id_transaksi[$index];
                $idBarang = $request->id_barang[$index];
                $metodeReture = $request->metode_reture[$index];
                $qtyAcc = $request->qty_acc[$index];
                $qrcode = $request->qrcode[$index];

                $detailBeli = DetailPembelianBarang::where('qrcode', $qrcode)->first();

                DetailRetur::where('id_retur', $idRetur)
                    ->where('id_transaksi', $idTransaksi)
                    ->where('id_barang', $idBarang)
                    ->update([
                        'metode_reture' => $metodeReture,
                        'status_reture' => 'success',
                    ]);

                if ($metodeReture === 'Barang') {
                    StockBarang::where('id_barang', $idBarang)
                                ->increment('stock', $qtyAcc);

                    DetailStockBarang::where('id_detail_pembelian', $detailBeli->id)
                                    ->increment('qty_now', $qtyAcc);
                }
            }

            DataReture::where('no_nota', $request->no_nota)
                ->update(['status' => 'done']);

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'errors' => false,
                'message' => 'Data berhasil diupdate',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating data: ' . $e->getMessage());

            return response()->json([
                'status_code' => 500,
                'errors' => true,
                'message' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function detailReture(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|string',
        ]);

        try {

            $detailKasir = DetailKasir::where('id_supplier', $request->id_supplier)->get();

            if ($detailKasir->isEmpty()) {
                return response()->json([
                        'error' => true,
                        'message' => 'Data tidak ditemukan',
                        'status_code' => 404,
                    ], 404);
            } else {
                $detailTransaksi = DetailRetur::join('detail_pembelian_barang', 'detail_retur.qrcode', '=', 'detail_pembelian_barang.qrcode')
                                        ->whereIn('detail_retur.id_transaksi', $detailKasir->pluck('id_kasir'))
                                        ->where('detail_retur.status', 'success')
                                        ->where('detail_retur.status_reture', 'pending')
                                        ->where('detail_retur.status_kirim', 'success')
                                        ->select('detail_retur.*', 'detail_pembelian_barang.harga_barang as hpp_jual', 'detail_pembelian_barang.qrcode')
                                        ->get();

                if ($detailTransaksi->isEmpty()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Data tidak ditemukan',
                        'status_code' => 404,
                    ], 404);
                }
            }

            $barang = Barang::whereIn('id', $detailTransaksi->pluck('id_barang'))->get();

            $namaBarang = $barang->mapWithKeys(function ($item) {
                return [$item->id => $item->nama_barang];
            });

            $detailTransaksi = $detailTransaksi->map(function ($item) use ($namaBarang) {
                return [
                    'id_transaksi' => $item->id_transaksi,
                    'id_retur' => $item->id_retur,
                    'id_barang' => $item->id_barang,
                    'nama_barang' => $namaBarang[$item->id_barang] ?? null,
                    'qty_acc' => $item->qty_acc,
                    'metode' => $item->metode,
                    'hpp_jual' => $item->hpp_jual,
                    'tgl_retur' => $item->tgl_retur,
                    'qrcode' => $item->qrcode,
                    'no_nota' => $item->no_nota,
                ];
            });

            return response()->json([
                'error' => false,
                'message' => 'Successfully',
                'status_code' => 200,
                'data' => $detailTransaksi,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                "error" => true,
                "message" => "Terjadi kesalahan pada server: " . $th->getMessage(),
                "status_code" => 500,
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id_retur' => 'required|integer',
            'id_supplier' => 'required|integer',
            'no_nota' => 'required|string',
        ]);

        ActivityLogger::log('Hapus Reture Supplier', $request->all());

        $user = Auth::user();

        try {
            $deleted = DataReture::where('id', $request->id_retur)
                ->where('id_supplier', $request->id_supplier)
                ->where('no_nota', $request->no_nota)
                ->where('id_users', $user->id)
                ->delete();

            if ($deleted) {

                return response()->json([
                    'status_code' => 200,
                    'errors' => false,
                    'message' => 'Data berhasil dihapus',
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 404,
                    'errors' => true,
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }

        } catch (\Exception $e) {

            Log::error('Error deleting data: ' . $e->getMessage());

            return response()->json([
                'status_code' => 500,
                'errors' => true,
                'message' => 'Terjadi kesalahan saat menghapus data' . $e->getMessage(),
            ], 500);
        }
    }
}
