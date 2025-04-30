<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\DetailKasir;
use App\Models\DetailPembelianBarang;
use App\Models\Hutang;
use App\Models\Pemasukan;
use App\Models\StockBarang;
use App\Services\ArusKasService;
use App\Services\LabaRugiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    private array $menu = [];
    protected $arusKasService;
    protected $labaRugiService;

    public function __construct(ArusKasService $arusKasService, LabaRugiService $labaRugiService)
    {
        $this->menu;
        $this->title = [
            'Neraca',
        ];

        $this->arusKasService = $arusKasService;
        $this->labaRugiService = $labaRugiService;
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[4]];

        return view('laporankeuangan.neraca.index', compact('menu'));
    }

    public function getNeraca(Request $request)
    {
        try {
            $month = $request->has('month') ? $request->month : Carbon::now()->month;
            $year = $request->has('year') ? $request->year : Carbon::now()->year;

            $result = $this->arusKasService->getArusKasData($request);

            $hutang = Hutang::where('status', '1')->get();

            $hutangItems = $hutang->map(function ($item, $index) {
                // Hitung total nilai dari detail pemasukan yang terkait
                $totalDetail = $item->detailhutang()->sum('nilai');

                // Kurangi nilai pemasukan dengan total dari detail
                $sisaNilai = $item->nilai - $totalDetail;

                // Jika nilai tersisa 0 atau kurang, anggap sudah lunas
                if ($sisaNilai <= 0) {
                    return null;
                }

                $jenis = $item->jangka == 1 ? 'Hutang Jangka Pendek' : 'Hutang Jangka Panjang';

                return [
                    "kode" => "III." . ($index + 1),
                    "nama" => $jenis . ' - ' . $item->keterangan,
                    "nilai" => $sisaNilai,
                ];
            })->filter()->values()->toArray();

            $ekuitasItems = [];

            for ($i = 1; $i <= $month; $i++) {
                $periode = Carbon::create($year, $i);
                $namaPeriode = $periode->translatedFormat('F Y');

                $nilaiLabaRugi = $this->labaRugiService->hitungLabaRugi($i, $year);

                $kode = "IV." . ($i + 1);

                $ekuitasItems[] = [
                    "kode" => $kode,
                    "nama" => $i == $month
                        ? "Laba (Rugi) Berjalan Periode $namaPeriode"
                        : "Laba (Rugi) Ditahan Periode $namaPeriode",
                    "nilai" => $nilaiLabaRugi,
                ];
            }

            array_unshift($ekuitasItems, [
                "kode" => "IV.1",
                "nama" => "Modal",
                "nilai" => $result['data_total']['modal']['total_modal'],
            ]);

            // Sisa Stock Keseluruhan Gudang
            $totalStock = StockBarang::with('detailToko')->get()->sum(function ($item) {
                $stockUtama = $item->stock ?? 0;
                $stockDetail = $item->detailToko->sum('qty') ?? 0;
                return $stockUtama + $stockDetail;
            });

            $detailBeli = DetailPembelianBarang::select('id_barang', DB::raw('SUM(qty * harga_barang) as total_beli'))
                ->groupBy('id_barang')
                ->pluck('total_beli', 'id_barang');

            $detailKasir = DetailKasir::select('id_barang', DB::raw('SUM(qty * hpp_jual) as total_jual'))
                ->groupBy('id_barang')
                ->pluck('total_jual', 'id_barang');

            // Gabungkan kedua hasil per id_barang
            $totalKasir = 0;
            foreach ($detailBeli as $id => $totalBeli) {
                $totalJual = $detailKasir[$id] ?? 0;
                $selisih = $totalBeli - $totalJual;
                $totalKasir += $selisih;
            }

            $asetLancarTotal = $result['data_total']['kas_besar']['saldo_akhir']
                 + $result['data_total']['kas_kecil']['saldo_akhir']
                 + $result['data_total']['piutang']['saldo_akhir']
                 + $totalKasir;

            $asetTetapTotal = $result['data_total']['aset_besar']['aset_peralatan_besar']
                + $result['data_total']['aset_kecil']['aset_peralatan_kecil'];

            $totalAktiva = $asetLancarTotal + $asetTetapTotal;

            $totalHutang = collect(array_merge($hutangItems))->sum('nilai');

            $totalEkuitas = collect($ekuitasItems)->sum('nilai');

            $totalPasiva = $totalHutang + $totalEkuitas;

            $data = [
                [
                    'kategori' => 'AKTIVA',
                    'total' => $totalAktiva,
                    'subkategori' => [
                        [
                            'judul' => 'I. ASET LANCAR',
                            'total' => $asetLancarTotal,
                            'item' => [
                                [
                                    "kode" => "I.1",
                                    "nama" => "Kas Besar",
                                    "nilai" => $result['data_total']['kas_besar']['saldo_akhir'],
                                ],
                                [
                                    "kode" => "I.2",
                                    "nama" => "Kas Kecil",
                                    "nilai" => $result['data_total']['kas_kecil']['saldo_akhir'],
                                ],
                                [
                                    "kode" => "I.3",
                                    "nama" => "Piutang (Kasbon)",
                                    "nilai" => $result['data_total']['piutang']['saldo_akhir'],
                                ],
                                [
                                    "kode" => "I.4",
                                    "nama" => "Stock Gudang ({$totalStock})",
                                    "nilai" => $totalKasir,
                                ],
                            ],
                        ],
                        [
                            'judul' => 'II. ASET TETAP',
                            'total' => $asetTetapTotal,
                            'item' => [
                                [
                                    "kode" => "II.1",
                                    "nama" => "Peralatan Besar",
                                    "nilai" => $result['data_total']['aset_besar']['aset_peralatan_besar'],
                                ],
                                [
                                    "kode" => "II.2",
                                    "nama" => "Peralatan Kecil",
                                    "nilai" => $result['data_total']['aset_kecil']['aset_peralatan_kecil'],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'kategori' => 'PASIVA',
                    'total' => $totalPasiva,
                    'subkategori' => [
                        [
                            'judul' => 'III. HUTANG',
                            'total' => $totalHutang,
                            'item' => $hutangItems,
                        ],
                        [
                            'judul' => 'IV. EKUITAS',
                            'total' => $totalEkuitas,
                            'item' => $ekuitasItems,
                        ],
                    ],
                ],
            ];

            return response()->json([
                'data' => $data,
                'status_code' => 200,
                'errors' => false,
                'message' => 'Berhasil'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Tidak Ada',
                'message_back' => $th->getMessage(),
                'status_code' => 500,
            ]);
        }
    }

}
