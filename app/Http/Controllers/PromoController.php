<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        return view('master.promo.index');
    }

    public function create(){
        return view('master.promo.create');
    }
}
