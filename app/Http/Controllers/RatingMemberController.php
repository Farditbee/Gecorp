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
        $selectedTokoIds = $request->input('id_toko'); // Ambil toko dari request
        $query = Member::select(
            'member.id',
            'member.nama_member',
            'kasir.id_toko',
            'toko.nama_toko', // Tambahkan nama_toko ke dalam select
            DB::raw('COUNT(detail_kasir.id_barang) as total_barang_dibeli'),
            DB::raw('SUM(detail_kasir.qty * detail_kasir.harga) as total_pembayaran')
        )
            ->join('kasir', 'member.id', '=', 'kasir.id_member')
            ->join('detail_kasir', 'kasir.id', '=', 'detail_kasir.id_kasir')
            ->join('toko', 'kasir.id_toko', '=', 'toko.id'); // Join dengan tabel toko

        if (!empty($selectedTokoIds) && $selectedTokoIds !== 'all') {
            $query->where('kasir.id_toko', $selectedTokoIds)
                ->groupBy('kasir.id_toko', 'toko.nama_toko', 'member.id', 'member.nama_member');
        } else {
            $query->groupBy('kasir.id_toko', 'toko.nama_toko', 'member.id', 'member.nama_member');
        }

        $dataMember = $query->orderBy('total_pembayaran', 'desc')->limit(10)->get();

        // Format data menjadi array yang sesuai
        $data = $dataMember->map(function ($item) {
            return [
                'nama_member' => $item->nama_member,
                'id_toko' => $item->id_toko,
                'nama_toko' => $item->toko->singkatan,
                'total_barang_dibeli' => $item->total_barang_dibeli,
                'total_pembayaran' => $item->total_pembayaran,
            ];
        });

        return response()->json([
            "error" => false,
            "message" => $data->isEmpty() ? "No data found" : "Data retrieved successfully",
            "status_code" => 200,
            "data" => $data
        ]);
    }

    public function index(Request $request)
    {
        $menu = [$this->title[0], $this->label[2]];

        return view('laporan.ratingmember.index', compact('menu'));
    }
}
