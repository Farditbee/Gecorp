<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function post_login(Request $request)
    {
        if (Auth::attempt(["username" => $request->username, "password" => $request->password])) {
            Log::info('User logged in: ' . Auth::user()->username);
            return redirect()->route('ds_admin')->with('message', 'Berhasil Login');
        } else {
            Log::error('Login failed for username: ' . $request->username);
            return back()->with('error', 'Login failed');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
