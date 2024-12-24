@extends('layouts.main')

@section('title')
    Tambah Data Jenis Barang
@endsection

@section('content')

<div class="pcoded-main-container">
    <div class="pcoded-content pt-1 mt-1">
        @include('components.breadcrumbs')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- Tombol Tambah -->
                        <a href="{{ route('master.jenisbarang.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <form action="{{ route('master.jenisbarang.store')}}" method="post" class="">
                                @csrf
                                <div class="form-group">
                                    <label for="nama_jenis_barang" class=" form-control-label">Nama Jenis Barang<span style="color: red">*</span></label>
                                    <input type="text" id="nama_jenis_barang" name="nama_jenis_barang" placeholder="Contoh : Barang Kali" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-dot-circle-o"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

@endsection
