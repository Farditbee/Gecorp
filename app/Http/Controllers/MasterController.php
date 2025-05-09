<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DataReture;
use App\Models\DetailToko;
use App\Models\Hutang;
use App\Models\JenisHutang;
use App\Models\JenisPemasukan;
use App\Models\JenisPengeluaran;
use App\Models\JenisPiutang;
use App\Models\Kasbon;
use App\Models\LevelUser;
use App\Models\Member;
use App\Models\Pemasukan;
use App\Models\Piutang;
use App\Models\StockBarang;
use App\Models\Supplier;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MasterController extends Controller
{
    public function getToko(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Toko::query();

        if (!empty($request['is_admin'])) {
            $query->where('id', '!=', 1);
        }

        if (!empty($request['super_admin'])) {
            $query->where('id', '=', 1);
        }

        if (!empty($request['is_delete'])) {
            $query->where('id', '!=', $request['is_delete']);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(singkatan) LIKE ?", ["%$searchTerm%"]);
            });
        }

        $query->orderBy('id', $meta['orderBy']);

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_toko'],
                'optional' => $item['singkatan'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getJenis(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = JenisPengeluaran::query();

        if (!empty($request['is_admin'])) {
            $query->where('id', '!=', 1);
        }

        if (!empty($request['super_admin'])) {
            $query->where('id', '=', 1);
        }

        if (!empty($request['is_delete'])) {
            $query->where('id', '!=', $request['is_delete']);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_jenis) LIKE ?", ["%$searchTerm%"]);
            });
        }

        $query->orderBy('id', $meta['orderBy']);

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_jenis'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getJenismasuk(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = JenisPemasukan::query();

        $cek_id_jenis = Pemasukan::where('id_jenis_pemasukan', 1)->exists();
        if ($cek_id_jenis) {
            $query->where('id', '!=', 1);
        }

        if (!empty($request['is_admin'])) {
            $query->where('id', '!=', 1);
        }

        if (!empty($request['super_admin'])) {
            $query->where('id', '=', 1);
        }

        if (!empty($request['is_delete'])) {
            $query->where('id', '!=', $request['is_delete']);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_jenis) LIKE ?", ["%$searchTerm%"]);
            });
        }

        $query->orderBy('id', $meta['orderBy']);

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_jenis'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getJenishutang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = JenisHutang::query();

        if (empty($request['is_not'])) {
            $cek_id_jenis = Hutang::where('id_jenis', 1)->exists();
            if ($cek_id_jenis) {
                $query->where('id', '!=', 1);
            }
        }

        if (!empty($request['is_admin'])) {
            $query->where('id', '!=', 1);
        }

        if (!empty($request['super_admin'])) {
            $query->where('id', '=', 1);
        }

        if (!empty($request['is_delete'])) {
            $query->where('id', '!=', $request['is_delete']);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_jenis) LIKE ?", ["%$searchTerm%"]);
            });
        }

        $query->orderBy('id', $meta['orderBy']);

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_jenis'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getJenispiutang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = JenisPiutang::query();

        if (!empty($request['is_admin'])) {
            $query->where('id', '!=', 1);
        }

        if (!empty($request['super_admin'])) {
            $query->where('id', '=', 1);
        }

        if (!empty($request['is_delete'])) {
            $query->where('id', '!=', $request['is_delete']);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_jenis) LIKE ?", ["%$searchTerm%"]);
            });
        }

        $query->orderBy('id', $meta['orderBy']);

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_jenis'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getMember(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $id_toko = $request->id_toko;

        if ($id_toko == 1) {
            $query = Member::query();
        } else {
            $query = Member::where('id_toko', $id_toko);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_member) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(no_hp) LIKE ?", ["%$searchTerm%"]);
            });
        }

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_member'] . ' / ' . $item['no_hp'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => true,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getBarang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Barang::query();

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_barang) LIKE ?", ["%$searchTerm%"]);
            });
        }

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_barang'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getSuplier(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Supplier::query();

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_supplier) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(contact) LIKE ?", ["%$searchTerm%"]);
            });
        }

        // $query->join('detail_kasir', 'supplier.id', '=', 'detail_kasir.id_supplier')
        //   ->join('detail_retur', function($join) {
        //       $join->on('detail_kasir.id_kasir', '=', 'detail_retur.id_transaksi')
        //            ->where('detail_retur.status', '=', 'success')
        //            ->where('detail_retur.status_reture', '=', 'pending')
        //            ->where('detail_retur.status_kirim', '=', 'success');
        //   })
        //   ->select('supplier.*')
        //   ->distinct();

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_supplier'] . ' / ' . $item['contact'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getBarangPengiriman(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $id_toko = $request->id_toko;

        if ($id_toko == 1) {
            $query = StockBarang::join('barang', 'stock_barang.id_barang', '=', 'barang.id')
                ->join('detail_pembelian_barang as dt_barang', 'stock_barang.id_barang', '=', 'dt_barang.id_barang')
                ->join('supplier', 'dt_barang.id_supplier', '=', 'supplier.id')
                ->join('detail_stock', 'dt_barang.id', '=', 'detail_stock.id_detail_pembelian')
                ->select('supplier.nama_supplier', 'barang.nama_barang', 'detail_stock.qty_now as qty', 'dt_barang.qrcode', 'dt_barang.id as id_detail');
        } else {
            $query = DetailToko::join('barang', 'detail_toko.id_barang', '=', 'barang.id')
                ->join('detail_pembelian_barang as dt_barang', 'detail_toko.qrcode', '=', 'dt_barang.qrcode')
                ->where('detail_toko.id_toko', $id_toko)
                ->select('detail_toko.id_barang', 'barang.nama_barang', 'detail_toko.qty', 'dt_barang.qrcode', 'dt_barang.id as id_detail');
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(barang.nama_barang) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(dt_barang.qrcode) LIKE ?", ["%$searchTerm%"]);
            });
        } else {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Silahkan masukkan nama barang / qrcode',
            ], 400);
        }

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['qrcode'] . '/' . $item['id_detail'],
                'text' => "{$item['nama_barang']} / Sisa Stock: ({$item['qty']}) / Supplier: {$item['nama_supplier']} / QRcode : {$item['qrcode']}",
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getBarangKasir(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $id_toko = $request->id_toko;

        if (!$id_toko) {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'ID Toko harus diisi',
            ], 400);
        }

        $query = DetailToko::join('barang', 'detail_toko.id_barang', '=', 'barang.id')
            ->join('supplier', 'detail_toko.id_supplier', '=', 'supplier.id')
            ->join('detail_pembelian_barang as dt_barang', 'detail_toko.qrcode', '=', 'dt_barang.qrcode')
            ->where('detail_toko.id_toko', $id_toko)
            ->select(
                'detail_toko.id',
                'detail_toko.id_supplier',
                'supplier.nama_supplier',
                'detail_toko.id_toko',
                'detail_toko.id_barang',
                'barang.nama_barang',
                'detail_toko.qty',
                'detail_toko.harga',
                'dt_barang.qrcode'
            );

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(dt_barang.qrcode) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereRaw("LOWER(barang.nama_barang) LIKE ?", ["%$searchTerm%"]);

            });
        } else {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Silahkan masukkan qrcode',
            ], 400);
        }

        $data = $query->paginate($meta['limit']);

        $paginationMeta = [
            'total'        => $data->total(),
            'per_page'     => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages'  => $data->lastPage()
        ];

        if ($data->isEmpty()) {
            return response()->json([
                'status_code' => 400,
                'errors' => true,
                'message' => 'Tidak ada data',
            ], 400);
        }

        $mappedData = $data->map(function ($item) {
            return [
                'id' => $item->qrcode . '/' . $item->id_barang,
                'text' => "{$item->nama_barang} / Sisa Stock: ({$item->qty}) /  QRcode: {$item->qrcode}",
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $paginationMeta
        ], 200);
    }

    public function getKasbon(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $userId = $request->id_user;

        if ($userId == 1) {
            // Jika user ID adalah 1, tampilkan semua data kasbon
            $query = Kasbon::query();
        } else {
            // Ambil id_toko dari user yang sedang login
            $userTokoId = User::where('id', $userId)->value('id_toko');

            // Ambil semua member yang memiliki id_toko yang sama
            $memberIds = Member::where('id_toko', $userTokoId)->pluck('id');

            // Tampilkan kasbon yang sesuai dengan member dari toko tersebut
            $query = Kasbon::with('member')->whereIn('id_member', $memberIds);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(id_member) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereHas('member', function ($query) use ($searchTerm) {
                    $query->whereRaw("LOWER(nama_member) LIKE ?", ["%$searchTerm%"]);
                    $query->whereRaw("LOWER(no_hp) LIKE ?", ["%$searchTerm%"]);
                });
            });
        }

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'nama_member' => $item['member']['nama_member'],
                'no_hp' => $item['member']['no_hp'],
                'utang' => intval($item['utang']),
                'tgl_kasbon' => $item['created_at'],
                'status' => $item['status'] === 'BL' ? 'Belum Lunas' : 'Lunas',
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }

    public function getLevelUser(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = LevelUser::query();

        if ($request['id_level'] == 3) {
            $query->where('id', 4);
        }

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_level) LIKE ?", ["%$searchTerm%"]);
            });
        }


        $query->orderBy('id', $meta['orderBy']);

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

        $mappedData = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['nama_level'],
            ];
        }, $data['data']);

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Berhasil',
            'pagination' => $data['meta']
        ], 200);
    }
}
