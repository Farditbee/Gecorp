<?php

namespace App\Http\Controllers;

use App\Models\DetailPengeluaran;
use App\Models\JenisPengeluaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengeluaranController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Pengeluaran Lainnya',
            'Tambah Data',
            'Edit Data'
        ];
    }

    public function index()
    {
        if (!in_array(Auth::user()->id_level, [1, 2, 3, 4])) {
            abort(403, 'Unauthorized');
        }
        $menu = [$this->title[0], $this->label[5]];

        return view('pengeluaran.index', compact('menu'));
    }

    public function getpengeluaran(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Pengeluaran::query();

        $query->with(['toko', 'jenis_pengeluaran'])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                // Pencarian pada kolom langsung
                $query->orWhereRaw("LOWER(nama_pengeluaran) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });
                $query->orWhereHas('jenis_pengeluaran', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_jenis) LIKE ?", ["%$searchTerm%"]);
                });
            });
        }

        if ($request->has('id_toko')) {
            $idToko = $request->input('id_toko');
            if ($idToko != 1) {
                $query->where('id_toko', $idToko);
            }
        }

        if ($request->has('toko')) {
            $idToko = $request->input('toko');
                $query->where('id_toko', $idToko);
        }

        if ($request->has('jenis')) {
            $id_jenis = $request->input('jenis');
            $query->where('id_jenis_pengeluaran', $id_jenis);
        }

        if ($request->has('is_hutang')) {
            $isHutang = $request->input('is_hutang');
                $query->where('is_hutang', $isHutang);
        }

        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }
        $totalNilai = $query->sum('nilai');

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
                'id_toko' => $item['toko']->id,
                'nama_toko' => $item['toko']->nama_toko,
                'nama_pengeluaran' => $item->nama_pengeluaran,
                'nama_jenis' => $item['jenis_pengeluaran']->nama_jenis ?? '-',
                'nilai' => 'Rp. ' . number_format($item->nilai, 0, '.', '.'),
                'tanggal' => Carbon::parse($item['tanggal'])->format('d-m-Y'),
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'status_code' => 200,
            'errors' => true,
            'message' => 'Sukses',
            'pagination' => $data['meta'],
            'total_nilai' => 'Rp. ' . number_format($totalNilai, 0, '.', '.')
        ], 200);
    }

    public function store(Request $request)
    {
        $is_asset = $request->input('is_asset');

        $validation = [
            'id_toko' => 'required|exists:toko,id',
            'nama_pengeluaran' => 'nullable|string',
            'nilai' => 'required|numeric',
            'tanggal' => 'required|date',
            'is_asset' => 'nullable',
            'id_jenis_pengeluaran' => 'nullable|exists:jenis_pengeluaran,id'
        ];

        $messages = [
            'id_jenis_pengeluaran.required' => 'Jenis pengeluaran tidak dapat dipilih ketika memilih Asset',
            'is_asset.required' => 'Asset harus dipilih untuk jenis pengeluaran ini',
            'is_asset.in' => 'Nilai Asset tidak valid'
        ];

        if ($request->has('id_jenis_pengeluaran') && $request->id_jenis_pengeluaran == 1) {
            $validation['is_asset'] = 'required|in:Asset Peralatan Kecil,Asset Peralatan Besar';
        } else {
            $validation['is_asset'] = 'nullable';
            $validatedData['is_asset'] = null;
        }

        $validatedData = $request->validate($validation, $messages);

        try {
            DB::beginTransaction();

            $id_jenis_pengeluaran = null;
                $id_jenis_pengeluaran = $validatedData['id_jenis_pengeluaran'] ?? null;
                if (empty($id_jenis_pengeluaran) && isset($validatedData['nama_jenis'])) {
                    $jenis_pengeluaran = JenisPengeluaran::create([
                        'nama_jenis' => $validatedData['nama_jenis']
                    ]);
                    $id_jenis_pengeluaran = $jenis_pengeluaran->id;
                }

            Pengeluaran::create([
                'id_toko' => $validatedData['id_toko'],
                'id_jenis_pengeluaran' => $validatedData['id_jenis_pengeluaran'],
                'nama_pengeluaran' => $validatedData['nama_pengeluaran'],
                'nilai' => $validatedData['nilai'],
                'tanggal' => $validatedData['tanggal'],
                'is_asset' => $validatedData['is_asset'] ?? null,
            ]);

            DB::commit();
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

    public function delete (string $id)
    {
        DB::beginTransaction();
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data pengeluaran'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data pengeluaran: ' . $th->getMessage()
            ], 500);
        }
    }


}
