<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailKasir;
use App\Models\DetailPengirimanBarang;
use App\Models\DetailToko;
use App\Models\StockBarang;
use App\Models\Toko;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanOrderController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Plan Order',
            'Tambah Data',
            'Edit Data'
        ];
    }

    public function getplanorder(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        // Ambil id_toko dari request
        $selectedTokoIds = $request->input('id_toko', []);

        // Ambil semua toko jika tidak ada yang dipilih
        if (empty($selectedTokoIds)) {
            $selectedTokoIds = Toko::pluck('id')->toArray();
        }

        // Query barang
        $query = Barang::select('barang.id', 'barang.nama_barang')
            ->orderBy('id', $meta['orderBy']);

        // Pencarian
        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_barang) LIKE ?", ["%$searchTerm%"]);
            });
        }

        // Paginate data barang
        $data = $query->paginate($meta['limit']);

        // Metadata pagination
        $paginationMeta = [
            'total'        => $data->total(),
            'per_page'     => $data->perPage(),
            'current_page' => $data->currentPage(),
            'total_pages'  => $data->lastPage(),
        ];

        // Format data barang, stok, otw, dan lo
        $mappedData = collect($data->items())->map(function ($item) use ($selectedTokoIds) {
            $stokPerToko = Toko::whereIn('id', $selectedTokoIds)->get()->mapWithKeys(function ($tk) use ($item) {
                // Ambil stok
                if ($tk->id == 1) {
                    $stock = StockBarang::where('id_barang', $item->id)->first()?->stock ?? 0;
                } else {
                    $stock = DetailToko::where('id_barang', $item->id)->where('id_toko', $tk->id)->first()?->qty ?? 0;
                }

                // Ambil jumlah otw
                $otw = DetailPengirimanBarang::where('id_barang', $item->id)
                    ->whereHas('pengiriman', function ($query) use ($tk) {
                        $query->where('toko_penerima', $tk->id)
                            ->where('status', '!=', 'success');
                    })
                    ->sum('qty');

                // Ambil last order (lo)
                $lastOrder = DetailKasir::where('id_barang', $item->id)
                    ->whereHas('kasir', function ($query) use ($tk) {
                        $query->where('id_toko', $tk->id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();

                $lo = $lastOrder && $lastOrder->created_at
                    ? abs(now()->startOfDay()->diffInDays(Carbon::parse($lastOrder->created_at)->startOfDay()))
                    : null;


                return [$tk->singkatan => ['stock' => $stock, 'otw' => $otw, 'lo' => $lo]];
            });

            return [
                'id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'stok_per_toko' => $stokPerToko,
            ];
        });

        // Kembalikan data dalam format JSON
        return response()->json([
            "error" => false,
            "message" => $mappedData->isEmpty() ? "No data found" : "Data retrieved successfully",
            "status_code" => 200,
            "pagination" => $paginationMeta,
            "data" => $mappedData,
        ]);
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[0]];
        $user = Auth::user(); // Mendapatkan user yang sedang login

        // Mengambil stock barang beserta relasi ke barang dan toko
        $stock = StockBarang::with('barang', 'toko')
            ->orderBy('id', 'desc')
            ->get();

        // Ambil stok barang dari tabel 'detail_toko' untuk semua toko kecuali id = 1
        $stokTokoLain = DetailToko::with('barang', 'toko')
            ->where('id_toko', '!=', 1)
            ->get();

        // Ambil semua toko
        $toko = Toko::all();
        $barang = Barang::all();

        return view('master.planorder.index', compact('menu', 'stock', 'stokTokoLain', 'toko', 'barang'));
    }
}
