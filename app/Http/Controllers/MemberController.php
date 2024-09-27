<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use App\Models\LevelHarga;
use App\Models\LevelUser;
use App\Models\Member;
use App\Models\Toko;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $member = Member::with('leveluser', 'toko')->get();
        return view('master.member.index', compact('member'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $toko = Toko::all();
        $leveluser = LevelUser::all();
        $levelharga = LevelHarga::all();
        $jenis_barang = JenisBarang::all();
        return view('master.member.create', compact('toko', 'leveluser', 'levelharga', 'jenis_barang'));
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
