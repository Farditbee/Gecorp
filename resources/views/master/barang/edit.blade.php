@extends('layouts.main')

@section('title')
    Edit Data Barang
@endsection

@section('content')

<div class="pcoded-main-container">
    <div class="pcoded-content">
        @include('components.breadcrumbs')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- Tombol Edit -->
                        <a href="{{ route('master.barang.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <form action="{{ route('master.barang.update', $barang->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="nama_barang" class=" form-control-label">Nama barang<span style="color: red">*</span></label>
                                    <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" placeholder="Contoh : Bearbarang" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="id_jenis_barang" class=" form-control-label">Jenis Barang</label>
                                    <select name="id_jenis_barang" id="selector" class="form-control" tabindex="1">
                                        <option value="">~Pilih~</option>
                                        @foreach ($jenis as $jn)
                                        <option value="{{ $jn->id }}" {{ old('id_jenis_barang', $barang->id_jenis_barang) == $jn->id ? 'selected' : '' }}>
                                            {{ $jn->nama_jenis_barang }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_brand_barang" class=" form-control-label">Brand Barang</label>
                                    <select name="id_brand_barang" class="form-control" tabindex="1">
                                        <option value="">~Pilih~</option>
                                        @foreach ($brand as $br)
                                        <option value="{{ $jn->id }}" {{ old('id_brand_barang', $barang->id_brand_barang) == $br->id ? 'selected' : '' }}>
                                            {{ $br->nama_brand }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-actions form-group">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
    document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('selector');
    const choices = new Choices(element, {
        removeItemButton: true, // Memungkinkan penghapusan item
        searchEnabled: true,    // Mengaktifkan pencarian
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('selectors');
    const choices = new Choices(element, {
        removeItemButton: true, // Memungkinkan penghapusan item
        searchEnabled: true,    // Mengaktifkan pencarian
    });
});
</script>

@endsection
