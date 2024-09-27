<title>Edit Toko - Gecorp</title>
@extends('layouts.main')

@section('content')

<div class="pcoded-main-container">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="page-header-title">
                                            <h4 class="m-b-10 ml-3">Data Toko</h4>
                                        </div>
                                        <ul class="breadcrumb ">
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('master.toko.index')}}">Data Toko</a></li>
                                            <li class="breadcrumb-item"><a>Edit Data Toko</a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
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
                                                    <label for="id_level_harga" class="form-control-label">Level Harga<span style="color: red">*</span></label>
                                                    <select class="form-control select2" name="id_level_harga[]">
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
                        <!-- [ Main Content ] start -->
                        <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih level...",
            allowClear: true
        });
    });
</script>

@endsection
