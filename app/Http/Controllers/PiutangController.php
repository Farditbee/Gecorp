<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetailPiutang;
use App\Models\JenisPiutang;
use App\Models\Piutang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PiutangController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Piutang',
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

        return view('piutang.index', compact('menu'));
    }

    public function getPiutang(Request $request)
    {
        $meta['orderBy'] = $request->ascending ? 'asc' : 'desc';
        $meta['limit'] = $request->has('limit') && $request->limit <= 30 ? $request->limit : 30;

        $query = Piutang::query();

        $query->with(['toko', 'jenis_piutang'])->orderBy('id', $meta['orderBy']);

        if (!empty($request['search'])) {
            $searchTerm = trim(strtolower($request['search']));

            $query->where(function ($query) use ($searchTerm) {
                $query->orWhereRaw("LOWER(keterangan) LIKE ?", ["%$searchTerm%"]);
                $query->orWhereHas('toko', function ($subquery) use ($searchTerm) {
                    $subquery->whereRaw("LOWER(nama_toko) LIKE ?", ["%$searchTerm%"]);
                });
                $query->orWhereHas('jenis_piutang', function ($subquery) use ($searchTerm) {
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
                'nama_jenis' => $item['jenis_piutang']->nama_jenis ?? '-',
                'keterangan' => $item->keterangan,
                'status' => $item->status,
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
            'id_jenis' => 'nullable|exists:jenis_piutang,id',
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
                $jenis_piutang = JenisPiutang::create([
                    'nama_jenis' => $validatedData['nama_jenis']
                ]);
                $id_jenis = $jenis_piutang->id;
            }

            Piutang::create([
                'id_toko' => $validatedData['id_toko'],
                'id_jenis' => $id_jenis,
                'keterangan' => $validatedData['keterangan'],
                'nilai' => $validatedData['nilai'],
                'jangka' => $validatedData['jangka'] ?? null,
                'status' => '1',
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
            $data = Piutang::findOrFail($id);
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

    public function detail(string $id)
    {
        try {
            $piutang = Piutang::with(['toko', 'jenis_piutang'])->findOrFail($id);
            $detailPembayaran = DetailPiutang::where('id_piutang', $id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nilai' => 'Rp. ' . number_format($item->nilai, 0, '.', '.'),
                        'tanggal' => Carbon::parse($item->created_at)->format('d-m-Y H:i:s')
                    ];
                });

            $totalPembayaran = DetailPiutang::where('id_piutang', $id)->sum('nilai');
            $sisaPiutang = $piutang->nilai - $totalPembayaran;

            return response()->json([
                'success' => true,
                'data' => [
                    'piutang' => [
                        'id' => $piutang->id,
                        'nama_toko' => $piutang->toko->nama_toko,
                        'keterangan' => $piutang->keterangan ?? '-',
                        'nama_jenis' => $piutang->jenis_piutang->nama_jenis ?? '-',
                        'nilai' => 'Rp. ' . number_format($piutang->nilai, 0, '.', '.'),
                        'status' => $piutang->status,
                        'jangka' => $piutang->jangka,
                        'tanggal' => Carbon::parse($piutang->tanggal)->format('d-m-Y'),
                    ],
                    'detail_pembayaran' => $detailPembayaran,
                    'total_pembayaran' => 'Rp. ' . number_format($totalPembayaran, 0, '.', '.'),
                    'sisa_piutang' => 'Rp. ' . number_format($sisaPiutang, 0, '.', '.')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail piutang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatepiutang(Request $request, string $id)
    {
        $validation = [
            'nilai' => 'required|numeric',
        ];

        $validatedData = $request->validate($validation);

        try {
            DB::beginTransaction();

            $piutang = Piutang::findOrFail($id);
            if ($validatedData['nilai'] > $piutang->nilai) {
                throw new \Exception('Nilai bayar melebihi nilai piutang!');
            }

            DetailPiutang::create([
                'id_piutang' => $piutang->id,
                'nilai' => $validatedData['nilai'],
            ]);

            $totalBayar = DetailPiutang::where('id_piutang', $piutang->id)->sum('nilai');

            if ($totalBayar >= $piutang->nilai) {
                $piutang->update(['status' => '2']);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil melakukan pembayaran piutang'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}
