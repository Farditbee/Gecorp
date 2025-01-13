<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Promo;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PromoController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Data Promo',
        ];
    }

    public function getpromo(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Promo::query();

        $query->with([])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->orWhereHas('barang', function ($subquery) use ($searchTerm) {
                $subquery->whereRaw("LOWER(nama_barang) LIKE ?", ["%$searchTerm%"]);
            });
            $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
            });
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            // Lakukan filter berdasarkan tanggal
            $query->whereBetween('created_at', [$startDate, $endDate]);
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

        $mappedData = collect($data['data'])->map(function ($item) {
            return [
                'id' => $item['id'],
                'id_barang' => $item['barang']->id_barang,
                'nama_barang' => $item['barang']->nama_barang,
                'id_toko' => $item['toko']->id_toko ?? null,
                'nama_toko' => $item['toko']->nama_toko ?? "Tak Ada Toko",
                'minimal' => $item->minimal,
                'diskon' => $item->diskon,
                'jumlah' => $item->jumlah,
                'terjual' => $item->terjual,
                'dari' => $item->dari,
                'sampai' => $item->sampai,
                'status' => match ($item->status) {
                    'done' => 'Sukses',
                    'ongoing' => 'On Going',
                    'queue' => 'Antrean',
                    default => $item->status,
                },
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => true,
            'message' => 'Sukses',
            'pagination' => $data['meta']
        ], 200);
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[0]];
        // Ambil ID barang yang memiliki promo dengan status "ongoing"
        $ongoingPromoBarangIds = Promo::where('status', 'ongoing')
            ->pluck('id_barang')
            ->toArray();
        $promo = Promo::all();
        $toko = Toko::where('id', '!=', 1)->get();
        $barang = Barang::all();

        // Ambil barang yang tidak memiliki promo "ongoing" atau memiliki promo dengan status selain "ongoing"
        $barang = Barang::whereNotIn('id', $ongoingPromoBarangIds)
            ->orWhereHas('promo', function ($query) {
                $query->where('status', '!=', 'ongoing');
            })
            ->get();

        return view('master.promo.index', compact('menu', 'barang', 'promo', 'toko'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_barang' => 'required|exists:barang,id', // Validasi barang harus ada di tabel barang
            'id_toko' => 'required|exists:toko,id', // Validasi barang harus ada di tabel toko
            'minimal' => 'required|integer|min:0',
            'jumlah' => 'required|integer|min:0',
            'diskon' => 'required|integer|between:0,100',
            'dari' => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ]);

        try {
            $barang = Barang::findOrFail($validatedData['barang']);
            $toko = Toko::findOrFail($validatedData['toko']);

            Promo::create([
                'id_barang' => $barang->id,
                'id_toko' => $toko->id,
                'nama_barang' => $barang->nama_barang,
                'minimal' => $validatedData['minimal'],
                'jumlah' => $validatedData['jumlah'],
                'diskon' => $validatedData['diskon'],
                'dari' => $validatedData['dari'],
                'sampai' => $validatedData['sampai'],
            ]);
            return response()->json(['message' => 'Data berhasil disimpan!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Tambah Data: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'status_code' => 500,
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validatedData = $request->validate([
            'id_barang' => 'nullable|exists:barang,id',
            'id_toko' => 'nullable|exists:toko,id',
            'minimal' => 'nullable|integer|min:0',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'jumlah' => 'nullable|integer|min:0',
            'terjual' => 'nullable|integer|min:0',
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ]);

        $promo = Promo::find($id);

        if (!$promo) {
            return response()->json([
                'status_code' => 404,
                'errors' => true,
                'message' => 'Promo tidak ditemukan',
            ], 404);
        }

        $promo->fill($validatedData);
        $promo->save();

        return response()->json([
            'status_code' => 200,
            'errors' => false,
            'message' => 'Data promo berhasil diperbarui',
            'data' => $promo,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'status' => 'required|string|in:done,ongoing,queue', // Status yang diperbolehkan
        ]);

        // Cari promo berdasarkan ID
        $promo = Promo::find($id);

        if (!$promo) {
            return response()->json([
                'status_code' => 404,
                'errors' => true,
                'message' => 'Promo tidak ditemukan',
            ], 404);
        }

        // Periksa apakah status yang diinginkan adalah "done" atau lainnya
        $newStatus = $validatedData['status'];

        // Update status
        $promo->status = $newStatus;
        $promo->save();

        return response()->json([
            'status_code' => 200,
            'errors' => false,
            'message' => 'Status berhasil diperbarui',
            'data' => [
                'id' => $promo->id,
                'status' => $promo->status,
            ],
        ], 200);
    }
}
