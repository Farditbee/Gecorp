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

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        ActivityLogger::log('Login', []);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $user->update([
                'ip_login' => $request->ip(),
                'last_activity' => Carbon::now(),
            ]);

            $route = '';
            if ($user->id_level == 1) {
                $route = route('dashboard.index');
            } elseif ($user->nama_level == 'petugas') {
                $route = redirect('/petugas/dashboard');
            } else {
                $route = route('dashboard.index');
            }
            return response()->json([
                'status_code' => 200,
                'error' => false,
                'message' => "Successfully",
                'data' => array(
                    'route_redirect' => $route
                )
            ], 200);
        } else {
            return response()->json([
                'status_code' => 300,
                'error' => true,
                'message' => "Terjadi Kesalahan",
            ], 300);
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
        $toko = Toko::where('id', '!=', 1)->get();

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

        // Filter berdasarkan id_toko dari request atau tampilkan semua jika id_toko login = 1
        $idToko = $request->input('id_toko', $user->id_toko);
        if ($user->id_toko != 1) {
            $kasirQuery->where('id_toko', $idToko);
        }

        $kasir = $kasirQuery->get();

        // Ambil data barang dan member berdasarkan level user
        if ($user->id_level == 1) {
            $barang = StockBarang::all();
            $member = Member::all();
        } else {
            $barang = DetailToko::where('id_toko', $idToko)->get();
            $member = Member::where('id_toko', $idToko)->get();
        }

        // Hitung total nilai berdasarkan id_toko atau semua jika id_toko login = 1
        $totalSemuaNilai = $user->id_toko == 1
            ? Kasir::sum('total_nilai')
            : Kasir::where('id_toko', $idToko)->sum('total_nilai');

        return view('dashboard', compact('menu', 'barang', 'kasir', 'member', 'detail_kasir', 'users', 'toko', 'totalSemuaNilai'));
    }
}
