@extends('layouts.main')

@section('title')
    Edit Data Brand
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
                        <a href="{{ route('master.brand.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <form action="{{ route('master.brand.update', $brand->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="nama_brand" class=" form-control-label">Nama Brand<span style="color: red">*</span></label>
                                    <input type="text" id="nama_brand" name="nama_brand" value="{{ old('nama_brand', $brand->nama_brand) }}" placeholder="Contoh : Bearbrand" class="form-control">
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

@endsection
