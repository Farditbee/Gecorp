<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class SuperAdminController extends Controller
{
    public function dashboard_admin()
    {
        return view('master.index');
    }
}
