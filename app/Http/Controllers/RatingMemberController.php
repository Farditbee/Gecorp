<?php

namespace App\Http\Controllers;

use App\Models\DetailKasir;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RatingMemberController extends Controller
{

    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Rating Member',
        ];
    }

    public function getMember(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $selectedTokoIds = $request->input('id_toko'); // Ambil toko dari request
        $startDate = $request->input('startDate'); // Ambil startDate dari request
        $endDate = $request->input('endDate'); // Ambil endDate dari request

        try {
            // Query utama untuk data member
            $query = Member::select(
                'member.id',
                'member.nama_member',
                'kasir.id_toko',
                'toko.nama_toko',
                DB::raw('COUNT(detail_kasir.id_barang) as total_trx'),
                DB::raw('SUM(detail_kasir.qty) as total_barang_dibeli'),
                DB::raw('SUM(detail_kasir.qty * detail_kasir.harga - detail_kasir.diskon) as total_pembayaran'),
                DB::raw('SUM(detail_kasir.qty * detail_kasir.hpp_jual) as laba') // Hitung laba
            )
                ->join('kasir', 'member.id', '=', 'kasir.id_member')
                ->join('detail_kasir', 'kasir.id', '=', 'detail_kasir.id_kasir')
                ->join('toko', 'kasir.id_toko', '=', 'toko.id');

            // Tambahkan filter toko jika diperlukan
            if (!empty($selectedTokoIds) && $selectedTokoIds !== 'all') {
                $query->where('kasir.id_toko', $selectedTokoIds);
            }

            // Tambahkan filter berdasarkan tanggal
            if (!empty($startDate) && !empty($endDate)) {
                $query->whereBetween('kasir.created_at', [$startDate, $endDate]);
            }

            // Tambahkan grouping
            $query->groupBy('kasir.id_toko', 'toko.nama_toko', 'member.id', 'member.nama_member');

            // Tambahkan sorting
            $query->orderBy('total_pembayaran', $meta['orderBy']);

            if (!empty($request['search'])) {
                $searchTerm = trim(strtolower($request['search']));

                $query->where(function ($query) use ($searchTerm) {
                    // Pencarian pada kolom langsung
                    $query->orWhereRaw("LOWER(nama_member) LIKE ?", ["%$searchTerm%"]);
                    $query->orWhereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });
            }

            // Eksekusi query dengan pagination
            $dataMember = $query->paginate($meta['limit']);

            // Format data menjadi array yang sesuai
            $mappedData = collect($dataMember->items())->map(function ($item) {
                return [
                    'nama_member' => $item->nama_member,
                    'id_toko' => $item->id_toko,
                    'nama_toko' => $item->nama_toko,
                    'total_trx' => $item->total_trx,
                    'total_barang_dibeli' => $item->total_barang_dibeli,
                    'total_pembayaran' => $item->total_pembayaran,
                    'laba' => $item->laba, // Tambahkan laba ke data yang dikembalikan
                ];
            });

            // Buat metadata pagination
            $paginationMeta = [
                'total' => $dataMember->total(),
                'per_page' => $dataMember->perPage(),
                'current_page' => $dataMember->currentPage(),
                'total_pages' => $dataMember->lastPage(),
            ];

            return response()->json([
                'data' => $mappedData,
                'status_code' => 200,
                'errors' => false,
                'message' => $dataMember->isEmpty() ? 'No data found' : 'Data retrieved successfully',
                'pagination' => $paginationMeta,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Error retrieving data',
                'status_code' => 500,
                'data' => $th->getMessage(),
            ]);
        }
    }


    public function index(Request $request)
    {
        if (!in_array(Auth::user()->id_level, [1, 2])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[2]];

        return view('laporan.ratingmember.index', compact('menu'));
    }
}
