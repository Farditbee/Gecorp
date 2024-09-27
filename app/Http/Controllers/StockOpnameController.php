<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function index()
    {
        return view('master.stockopname.index');
    }
}
