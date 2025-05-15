<?php

namespace App\Http\Controllers\Pengeluaran;

use App\Http\Controllers\Controller;

class PengeluaranController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Pengeluaran',
        ];
    }

    public function index()
    {
        $menu = [$this->title[0]];

        return view('pengeluaran.index', compact('menu'));
    }
}
