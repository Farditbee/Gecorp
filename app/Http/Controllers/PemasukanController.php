<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetailPemasukan;
use App\Models\JenisPemasukan;
use App\Models\Pemasukan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemasukanController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Pemasukan',
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

        return view('pemasukan.index', compact('menu'));
    }

    public function getpemasukan(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Pemasukan::query();

        $query->with(['toko', 'jenis_pemasukan'])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(nama_pemasukan) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });
                $query->orWhereHas('jenis_pemasukan', function ($subquery) use ($searchTerm) {
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
            $query->where('id_jenis_pemasukan', $id_jenis);
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
                'id_toko' => $item['toko'] ? $item['toko']->id : null,
                'nama_toko' => $item['toko']->nama_toko,
                'nama_pemasukan' => $item->nama_pemasukan ?? '-',
                'nama_jenis' => $item['jenis_pemasukan']->nama_jenis ?? '-',
                'nilai' => 'Rp. ' . number_format($item->nilai ?? 0, 0, '.', '.'),
                'tanggal' => $item['tanggal'] ? Carbon::parse($item['tanggal'])->format('d-m-Y') : '-',
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
            'nama_pemasukan' => 'nullable|string',
            'nilai' => 'required|numeric',
            'tanggal' => 'required|date',
            'id_jenis_pemasukan' => 'required|exists:jenis_pemasukan,id'
        ];

        $validatedData = $request->validate($validation);

        try {
            DB::beginTransaction();

            Pemasukan::create([
                'id_toko' => $validatedData['id_toko'],
                'id_jenis_pemasukan' => $validatedData['id_jenis_pemasukan'],
                'nama_pemasukan' => $validatedData['nama_pemasukan'],
                'nilai' => $validatedData['nilai'],
                'tanggal' => $validatedData['tanggal'],
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
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sukses menghapus Data pemasukan'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus Data pemasukan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function updatepinjam(Request $request, string $id)
    {
        $validation = [
            'nilai' => 'required|numeric',
        ];

        $validatedData = $request->validate($validation);

        try {
            DB::beginTransaction();

            $pemasukan = Pemasukan::findOrFail($id);
            if ($pemasukan->is_pinjam != '1') {
                throw new \Exception('Data bukan merupakan pinjaman!');
            }

            if ($validatedData['nilai'] > $pemasukan->nilai) {
                throw new \Exception('Nilai bayar melebihi nilai pinjaman!');
            }

            DetailPemasukan::create([
                'id_pemasukan' => $pemasukan->id,
                'nilai' => $validatedData['nilai'],
            ]);

            $totalBayar = DetailPemasukan::where('id_pemasukan', $pemasukan->id)->sum('nilai');

            if ($totalBayar >= $pemasukan->nilai) {
                $pemasukan->update(['is_pinjam' => '2']);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil melakukan pembayaran pinjaman'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detail(string $id)
    {
        try {
            $pemasukan = Pemasukan::with(['toko', 'jenis_pemasukan'])->findOrFail($id);
            $detailPembayaran = DetailPemasukan::where('id_pemasukan', $id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nilai' => 'Rp. ' . number_format($item->nilai, 0, '.', '.'),
                        'tanggal' => Carbon::parse($item->created_at)->format('d-m-Y H:i:s')
                    ];
                });

            $totalPembayaran = DetailPemasukan::where('id_pemasukan', $id)->sum('nilai');
            $sisaPinjaman = $pemasukan->nilai - $totalPembayaran;

            return response()->json([
                'success' => true,
                'data' => [
                    'pemasukan' => [
                        'id' => $pemasukan->id,
                        'nama_toko' => $pemasukan->toko->nama_toko,
                        'nama_pemasukan' => $pemasukan->nama_pemasukan ?? '-',
                        'nama_jenis' => $pemasukan->jenis_pemasukan->nama_jenis ?? '-',
                        'nilai' => 'Rp. ' . number_format($pemasukan->nilai, 0, '.', '.'),
                        'is_pinjam' => $pemasukan->is_pinjam,
                        'ket_pinjam' => $pemasukan->ket_pinjam,
                        'tanggal' => Carbon::parse($pemasukan->tanggal)->format('d-m-Y'),
                    ],
                    'detail_pembayaran' => $detailPembayaran,
                    'total_pembayaran' => 'Rp. ' . number_format($totalPembayaran, 0, '.', '.'),
                    'sisa_pinjaman' => 'Rp. ' . number_format($sisaPinjaman, 0, '.', '.')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pemasukan: ' . $e->getMessage()
            ], 500);
        }
    }
}
