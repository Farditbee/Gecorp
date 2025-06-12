<?php

namespace App\Http\Controllers\LaporanKeuangan;

use App\Http\Controllers\Controller;
use App\Services\ArusKasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (!in_array(Auth::user()->id_level, [1])) {
            abort(403, 'Unauthorized');
        }

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
