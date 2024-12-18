<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\DetailKasir;
use App\Models\DetailToko;
use App\Models\Kasir;
use App\Models\Member;
use App\Models\StockBarang;
use App\Models\Toko;
use App\Models\User;
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

            ActivityLogger::log('Login', []);

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
        $title = 'Dashboard';

        return view('dashboard', compact('title', 'toko'));
    }

    public function logout(Request $request)
    {
        ActivityLogger::log('Logout', []);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function index(Request $request)
    {
        $menu = ['Dashboard'];
        $user = Auth::user();
        $users = User::all();
        $detail_kasir = DetailKasir::all();
        $toko = Toko::all();

        // Mengambil data berdasarkan level user
        if ($user->id_level == 1) {
            $kasirQuery = Kasir::orderBy('id', 'desc');
        } else {
            $kasirQuery = Kasir::where('id_toko', $user->id_toko)
                ->orderBy('id', 'desc');
        }

        // Filter berdasarkan tgl_transaksi
        if ($request->has(['start_date', 'end_date'])) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $kasirQuery->whereBetween('tgl_transaksi', [$startDate, $endDate]);
        }

        $kasir = $kasirQuery->get();

        // Ambil data barang dan member berdasarkan level user
        if ($user->id_level == 1) {
            $barang = StockBarang::all();
            $member = Member::all();
        } else {
            $barang = DetailToko::where('id_toko', $user->id_toko)->get();
            $member = Member::where('id_toko', $user->id_toko)->get();
        }

        $totalSemuaNilai = Kasir::sum('total_nilai');

        // dd($totalSemuaNilai);

        return view('dashboard', compact('menu', 'barang', 'kasir', 'member', 'detail_kasir', 'users', 'toko', 'totalSemuaNilai'));
    }
}
