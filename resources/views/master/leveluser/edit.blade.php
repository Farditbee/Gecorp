@extends('layouts.main')

@section('title')
    Edit Data Level User
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
                        <a href="{{ route('master.leveluser.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <form action="{{ route('master.leveluser.update', $leveluser->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="nama_level" class=" form-control-label">Nama Level User<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('nama_level') is-invalid @enderror" name="nama_level" value="{{ old('nama_level', $leveluser->nama_level) }}">
                                </div>
                                <div class="form-group">
                                    <label for="informasi" class=" form-control-label">Informasi<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('informasi') is-invalid @enderror" name="informasi" value="{{ old('informasi', $leveluser->informasi) }}">
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

@endsection
