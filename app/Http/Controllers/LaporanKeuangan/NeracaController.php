<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Services\ArusKasService;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    private array $menu = [];
    protected $arusKasService;

    public function __construct(ArusKasService $arusKasService)
    {
        $this->menu;
        $this->title = [
            'Neraca',
        ];

        $this->arusKasService = $arusKasService;
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[4]];

        return view('laporankeuangan.neraca.index', compact('menu'));
    }

    public function getNeraca(Request $request)
    {
        try {
            $result = $this->arusKasService->getArusKasData($request);

            $data = [
                [
                    'kategori' => 'AKTIVA',
                    'total' => 0,
                    'subkategori' => [
                        [
                            'judul' => 'I. ASET LANCAR',
                            'total' => 0,
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
                            ],
                        ],
                        [
                            'judul' => 'II. ASET TETAP',
                            'total' => 0,
                            'item' => [
                                [
                                    "kode" => "II.1",
                                    "nama" => "Peralatan Besar",
                                    "nilai" => $result['data_total']['kas_besar']['saldo_akhir'],
                                ],
                                [
                                    "kode" => "II.2",
                                    "nama" => "Peralatan Besar",
                                    "nilai" => $result['data_total']['kas_kecil']['saldo_akhir'],
                                ],
                            ],
                        ],
                    ],
                ],

                [
                    'kategori' => 'PASIVA',
                    'total' => 0,
                    'subkategori' => [
                        [
                            'judul' => 'III. HUTANG',
                            'total' => 0,
                            'item' => [
                                [
                                    "kode" => "III.1",
                                    "nama" => "Hutang Jangka Pendek (Pak Wandi & Gata)",
                                    "nilai" => $result['data_total']['kas_besar']['saldo_akhir'],
                                ],
                                [
                                    "kode" => "III.2",
                                    "nama" => "Hutang Jangka Panjang (Bpk. Gata)",
                                    "nilai" => $result['data_total']['kas_kecil']['saldo_akhir'],
                                ],
                            ],
                        ],
                        [
                            'judul' => 'IV. EKUITAS',
                            'total' => 0,
                            'item' => [
                                [
                                    "kode" => "IV.1",
                                    "nama" => "Peralatan Besar",
                                    "nilai" => $result['data_total']['kas_besar']['saldo_akhir'],
                                ],
                                [
                                    "kode" => "IV.2",
                                    "nama" => "Peralatan Besar",
                                    "nilai" => $result['data_total']['kas_kecil']['saldo_akhir'],
                                ],
                            ],
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
