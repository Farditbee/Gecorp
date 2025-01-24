<?php

namespace App\Http\Controllers\Reture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RetureSuplierController extends Controller
{
    private array $menu = [];

    public function __construct()
    {
        $this->menu;
        $this->title = [
            'Reture Suplier',
        ];
    }

    public function index()
    {
        $menu = [$this->title[0]];
        return view('reture.suplier.index', compact('menu'));
    }
}
