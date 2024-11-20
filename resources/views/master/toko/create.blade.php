<title>Tambah Tambah Data Toko - Gecorp</title>
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
                            <h5 class="m-b-10">Tambah Data Toko</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('master.toko.index')}}">Data Toko</a></li>
                            <li class="breadcrumb-item"><a>Tambah Data Toko</a></li>
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
                            <form action="{{ route('master.toko.store')}}" method="post" class="">
                                @csrf
                                <div class="form-group">
                                    <label for="nama_toko" class=" form-control-label">Nama Toko<span style="color: red">*</span></label>
                                    <input type="text" id="nama_toko" name="nama_toko" placeholder="Contoh : Toko Sejahtera" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="singkatan" class="form-control-label">Singkatan Toko<span style="color: red">*</span></label>
                                    <input type="text" id="singkatan" name="singkatan" placeholder="Maksimal 4 Karakter" class="form-control" value="{{ old('singkatan') }}">
                                    @error('singkatan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="id_level_harga" class="form-control-label">Level Harga</label>
                                    <select class="form-control" id="selector" name="id_level_harga[]" multiple>
                                        <option value="">~Silahkan Pilih~</option>
                                        @foreach ($levelharga as $lh)
                                            <option value="{{ $lh->id }}">{{ $lh->nama_level_harga }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="wilayah" class=" form-control-label">Wilayah<span style="color: red">*</span></label>
                                    <input type="text" id="wilayah" name="wilayah" placeholder="Contoh : Cirebon Timur" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="wilayah" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                                    <textarea name="alamat" id="alamat" rows="4" placeholder="Contoh : Jl. Nyimas Gandasari No.18 Plered - Cirebon" class="form-control"></textarea>
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
    document.addEventListener('DOMContentLoaded', function () {
    const element = document.getElementById('selector');
    const choices = new Choices(element, {
        removeItemButton: true,
        searchEnabled: true,
    });
});
</script>

@endsection
