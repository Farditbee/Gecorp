<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanOrderController extends Controller
{
    public function index()
    {
        return view('master.planorder.index');
    }
}
