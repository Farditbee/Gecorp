@extends('layouts.main')

@section('title')
    Tambah Data Reture
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content"> 
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <!-- Tombol Tambah -->
                            <a href="{{ route('master.barang.index') }}" class="btn btn-danger">
                                <i class="ti-plus menu-icon"></i> Kembali
                            </a>
                            <!-- Input Search -->
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <form action="{{ route('reture.store') }}" method="post" class="">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="nama_barang" class=" form-control-label">Scan QrCode Barang<span style="color: red">*</span></label>
                                            <div class="input-group mb-6">
                                                    <input type="text" class="form-control" placeholder="Masukkan / scan Qr Code Barang" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" type="button">Button</button>
                                                    </div>
                                            </div><br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="barang" class="form-control-label">Nama Toko</label>
                                                <select name="barang" id="barang" class="form-control">
                                                    <option value="" selected>Pilih Barang</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="barang" class="form-control-label">ID Transaksi</label>
                                                <select name="barang" id="barang" class="form-control">
                                                    <option value="" selected>Pilih Barang</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="barang" class="form-control-label">Tipe Transaksi</label>
                                                <select name="barang" id="barang" class="form-control">
                                                    <option value="" selected>Pilih Barang</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="barang" class="form-control-label">Nama Member</label>
                                                <select name="barang" id="barang" class="form-control">
                                                    <option value="" selected>Pilih Barang</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="barang" class="form-control-label">Harga Jual</label>
                                                <select name="barang" id="barang" class="form-control">
                                                    <option value="" selected>Pilih Barang</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="barang" class="form-control-label">Nama Barang</label>
                                                <select name="barang" id="barang" class="form-control">
                                                    <option value="" selected>Pilih Barang</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="barang" class="form-control-label">Qty</label>
                                                <select name="barang" id="barang" class="form-control">
                                                    <option value="" selected>Pilih Barang</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('selector');
            const choices = new Choices(element, {
                removeItemButton: true, // Memungkinkan penghapusan item
                searchEnabled: true, // Mengaktifkan pencarian
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('selectors');
            const choices = new Choices(element, {
                removeItemButton: true, // Memungkinkan penghapusan item
                searchEnabled: true, // Mengaktifkan pencarian
            });
        });
    </script>
@endsection
