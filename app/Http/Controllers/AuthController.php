<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Toko;
use Carbon\Carbon;
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

                $user->update([
                    'ip_login' => $request->ip(),
                    'last_activity' => Carbon::now(),
                ]);

                // dd($user);
                if ($user->id_level == 1) {
                    return redirect()->route('master.index')->with('success', 'Berhasil Login');
                } elseif ($user->nama_level == 'petugas') {
                    return redirect('/petugas/dashboard')->with('success', 'Berhasil Login');
                } else {
                    return redirect()->route('master.index')->with('success', 'Berhasil Login');
                }
            } else {
                // Jika kredensial salah
                return redirect()->back()->withErrors(['error' => 'Username atau password salah'])->onlyInput('username');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->onlyInput('username');
        }
    }


    public function dashboard()
    {
        $toko = Toko::all();
        return view('dashboard', compact('toko'));
    }

    public function logout(Request $request)
    {
        ActivityLogger::log('Logout', []);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
