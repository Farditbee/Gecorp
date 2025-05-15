<?php

namespace App\Http\Controllers\Pengembalian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Pengembalian Barang',
        ];
    }
}
