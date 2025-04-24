<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hutang;
use App\Models\JenisHutang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HutangController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Hutang',
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

        return view('hutang.index', compact('menu'));
    }

    public function getHutang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Hutang::query();

        $query->with(['toko', 'jenis_hutang'])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(keterangan) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });
                $query->orWhereHas('jenis_hutang', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_jenis) LIKE ?", ["%$searchTerm%"]);
                });
            });
        }

        if ($request->has('toko')) {
            $idToko = $request->input('toko');
            if ($idToko != 1) {
                $query->where(function ($q) use ($idToko) {
                    $q->where('toko', $idToko);
                });
            }
        }

        if ($request->has('jenis')) {
            $idJenis = $request->input('jenis');
            $query->where(function ($q) use ($idJenis) {
                $q->where('id_jenis', $idJenis);
            });
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            $query->where(function ($q) use ($status) {
                $q->where('status', $status);
            });
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
            $jangka = null;
            if ($item->jangka == '1') {
                $jangka = 'Jangka Pendek';
            } elseif ($item->jangka == '2') {
                $jangka = 'Jangka Panjang';
            } else {
                $jangka = 'Tidak ada';
            }

            return [
                'id' => $item['id'],
                'id_toko' => $item['toko'] ? $item['toko']->id : null,
                'nama_toko' => $item['toko'] ? $item['toko']->singkatan : null,
                'nama_jenis' => $item['jenis_hutang']->nama_jenis ?? '-',
                'keterangan' => $item->keterangan,
                'jangka' => $jangka,
                'tanggal' => Carbon::parse($item['tanggal'])->format('d-m-Y'),
                'nilai' => 'Rp. ' . number_format($item->nilai ?? 0, 0, '.', '.'),
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
        $validation = [
            'id_toko' => 'required|exists:toko,id',
            'id_jenis' => 'nullable|exists:jenis_hutang,id',
            'keterangan' => 'required|string',
            'nilai' => 'required|numeric',
            'jangka' => 'nullable|in:1,2',
            'tanggal' => 'required|date',
            'nama_jenis' => 'required_without:id_jenis|string'
        ];

        $validatedData = $request->validate($validation);

        try {
            DB::beginTransaction();

            $id_jenis = null;
            $id_jenis = $validatedData['id_jenis'] ?? null;
            if (empty($id_jenis) && isset($validatedData['nama_jenis'])) {
                $jenis_hutang = JenisHutang::create([
                    'nama_jenis' => $validatedData['nama_jenis']
                ]);
                $id_jenis = $jenis_hutang->id;
            }

            Hutang::create([
                'id_toko' => $validatedData['id_toko'],
                'id_jenis' => $id_jenis,
                'keterangan' => $validatedData['keterangan'],
                'nilai' => $validatedData['nilai'],
                'status' => '1',
                'jangka' => $validatedData['jangka'] ?? null,
                'tanggal' => $validatedData['tanggal'],
            ]);

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'status_code' => 500,
            ], 500);
        }
    }

    public function delete(String $id)
    {
        DB::beginTransaction();
        try {
            $data = Hutang::findOrFail($id);
            $data->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data: ' . $th->getMessage()
            ], 500);
        }
    }
}
