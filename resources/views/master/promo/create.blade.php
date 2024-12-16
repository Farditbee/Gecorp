@extends('layouts.main')

@section('title')
    Tambah Data Promo
@endsection

@section('content')

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header">
                    <div class="page-title">
                        <h1 class="card-title"><strong>Tambah Data - Promo</strong></h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('master.index')}}">Dashboard</a></li>
                            <li><a href="{{ route('master.promo.index')}}">Data Promo</a></li>
                            <li class="active">Tambah Data Promo</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="content">
            <x-adminlte-alerts />
            <!-- Animated -->
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('master.promo.index')}}" class="btn btn-danger"></i> Kembali</a>
                            </div>
                            <div class="card-body">
                                {{-- Content --}}
                                <div class="card-body card-block">
                                    <form action="#" method="post" class="">
                                        @csrf

                                        <input type="hidden" name="id" id="">

                                        <div class="form-group">
                                            <label for="id_jenis_barang" class=" form-control-label">Nama Barang</label>
                                                <select name="id_jenis_barang" id="select" class="form-control">
                                                    <option value="1">Option #1</option>
                                                    <option value="2">Option #2</option>
                                                    <option value="3">Option #3</option>
                                                </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga" class=" form-control-label">Harga<span style="color: red">*</span></label>
                                            <input type="number" id="harga" name="harga" placeholder="Contoh : 700000" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_toko" class=" form-control-label">Nama Toko<span style="color: red">*</span></label>
                                            <input type="text" id="nama_toko" name="nama_toko" placeholder="Contoh : Toko Bangun Jaya" class="form-control">
                                        </div>

                                        <br>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-dot-circle-o"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- end Content --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->

        <!-- Footer -->
@endsection
