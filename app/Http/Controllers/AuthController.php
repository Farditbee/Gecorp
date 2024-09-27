<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();

                // dd($user);
                if ($user->id_level == 1) {
                    return redirect()->route('master.index')->with('message', 'Berhasil Login');
                } elseif ($user->nama_level == 'petugas') {
                    return redirect('/petugas/dashboard')->with('message', 'Berhasil Login');
                } else {
                    return redirect('/default/dashboard')->with('message', 'Berhasil Login');
                }
            } else {
                // Jika kredensial salah
                return redirect()->back()->withErrors(['error' => 'Username atau password salah'])->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }


    public function dashboard()
    {
        return view('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
