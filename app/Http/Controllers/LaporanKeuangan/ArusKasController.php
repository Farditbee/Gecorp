<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Models\DetailPemasukan;
use App\Models\Kasir;
use App\Models\Pengeluaran;
use App\Models\DetailPengeluaran;
use App\Models\Mutasi;
use App\Models\PembelianBarang;
use App\Models\Pemasukan;
use App\Services\ArusKasService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArusKasController extends Controller
{
    private array $menu = [];
    protected $arusKasService;

    public function __construct(ArusKasService $arusKasService)
    {
        $this->menu;
        $this->title = [
            'Arus Kas',
        ];

        $this->arusKasService = $arusKasService;
    }

    public function index()
    {
        $menu = [$this->title[0], $this->label[4]];

        return view('laporankeuangan.aruskas.index', compact('menu'));
    }

    public function getaruskas(Request $request)
    {
        try {
            $result = $this->arusKasService->getArusKasData($request);

            return response()->json([
                'data' => $result['data'],
                'data_total' => $result['data_total'],
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
