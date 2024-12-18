@extends('layouts.main')

@section('title')
    Tambah Data Barang
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            @include('components.breadcrumbs')
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
                                <form action="{{ route('master.barang.store') }}" method="post" class=""
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="nama_barang" class=" form-control-label">Nama Barang<span
                                                    style="color: red">*</span></label>
                                            <input type="text" id="nama_barang" name="nama_barang" value=""
                                                class="form-control" placeholder="Contoh : Barang Baru">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_jenis_barang" class=" form-control-label">Jenis Barang<span
                                                style="color: red">*</span></label>
                                        <select name="id_jenis_barang" id="selector" class="form-control">
                                            <option value="" required>~Pilih Jenis Barang~</option>
                                            @foreach ($jenis as $jn)
                                                <option value="{{ $jn->id }}">{{ $jn->nama_jenis_barang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_brand_barang" class=" form-control-label">Brand Barang<span
                                                style="color: red">*</span></label>
                                        <select name="id_brand_barang" id="selectors" class="form-control">
                                            <option value="">~Pilih Brand~</option>
                                            @foreach ($brand as $br)
                                                <option value="{{ $br->id }}">{{ $br->nama_brand }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="form-control-label">Gambar Barang<span style="font-size: 11px; color: rgb(193, 79, 79)"> (Ukuran tidak lebih dari 1MB)</span></label>
                                            <input type="file" id="gambar_barang" name="gambar_barang"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="barcode" class=" form-control-label">Barcode</label>
                                            <input type="text" id="barcode" name="barcode" value=""
                                                class="form-control" placeholder="Kosongkan jika tidak ada barcode">
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
