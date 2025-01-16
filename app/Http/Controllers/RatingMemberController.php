<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
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

        try {
            // Query utama untuk data member
            $query = Member::select(
                'member.id',
                'member.nama_member',
                'kasir.id_toko',
                'toko.nama_toko',
                DB::raw('COUNT(detail_kasir.id_barang) as total_barang_dibeli'),
                DB::raw('SUM(detail_kasir.qty * detail_kasir.harga) as total_pembayaran')
            )
                ->join('kasir', 'member.id', '=', 'kasir.id_member')
                ->join('detail_kasir', 'kasir.id', '=', 'detail_kasir.id_kasir')
                ->join('toko', 'kasir.id_toko', '=', 'toko.id');

            // Tambahkan filter toko jika diperlukan
            if (!empty($selectedTokoIds) && $selectedTokoIds !== 'all') {
                $query->where('kasir.id_toko', $selectedTokoIds);
            }

            // Tambahkan grouping
            $query->groupBy('kasir.id_toko', 'toko.nama_toko', 'member.id', 'member.nama_member');

            // Tambahkan sorting
            $query->orderBy('total_pembayaran', $meta['orderBy']);

            // Eksekusi query dengan pagination
            $dataMember = $query->paginate($meta['limit']);

            // Format data menjadi array yang sesuai
            $mappedData = collect($dataMember->items())->map(function ($item) {
                return [
                    'nama_member' => $item->nama_member,
                    'id_toko' => $item->id_toko,
                    'nama_toko' => $item->nama_toko,
                    'total_barang_dibeli' => $item->total_barang_dibeli,
                    'total_pembayaran' => $item->total_pembayaran,
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
        $menu = [$this->title[0], $this->label[2]];

        return view('laporan.ratingmember.index', compact('menu'));
    }
}
