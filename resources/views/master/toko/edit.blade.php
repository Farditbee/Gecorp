<title>Edit Toko - Gecorp</title>
@extends('layouts.main')

@section('content')

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Data Toko</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('master.toko.index')}}">Data Toko</a></li>
                            <li class="breadcrumb-item"><a>Edit Data Toko</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- Tombol Tambah -->
                        <a href="{{ route('master.toko.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <form action="{{ route('master.toko.update', $toko->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                <div class="form-group">
                                    <label for="nama_toko" class=" form-control-label">Nama Toko<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('nama_toko') is-invalid @enderror" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}">
                                </div>
                                <div class="form-group">
                                    <label for="singkatan" class=" form-control-label">Singkatan<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('singkatan') is-invalid @enderror" name="singkatan" value="{{ old('singkatan', $toko->singkatan) }}">
                                </div>
                                <div class="form-group">
                                    <label for="id_level_harga" class="form-control-label">Level Harga<span style="color: red">*</span></label>
                                    <select class="form-control" id="id_level_harga" name="id_level_harga[]" multiple>
                                        @foreach ($levelharga as $lh)
                                            <option value="{{ $lh->id }}"
                                                @if(in_array($lh->id, json_decode($toko->id_level_harga, true))) selected @endif>
                                                {{ $lh->nama_level_harga }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="wilayah" class=" form-control-label">Wilayah<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('wilayah') is-invalid @enderror" name="wilayah" value="{{ old('wilayah', $toko->wilayah) }}" placeholder="Masukkan wilayah">
                                </div>
                                <div class="form-group">
                                    <label for="wilayah" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                                    <textarea name="alamat" id="alamat" rows="4" @error('alamat') is-invalid @enderror" name="alamat" class="form-control">{{ old('alamat', $toko->alamat) }}</textarea>
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('id_level_harga');
    const choices = new Choices(element, {
        removeItemButton: true, // Memungkinkan penghapusan item
        searchEnabled: true,    // Mengaktifkan pencarian
    });
});
</script>

@endsection
