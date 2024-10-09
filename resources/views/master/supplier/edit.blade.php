<title>Edit Data Supplier - Gecorp</title>
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
                            <h5 class="m-b-10">Edit Data Supplier</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('master.index')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('master.supplier.index')}}">Data Supplier</a></li>
                            <li class="breadcrumb-item"><a>Edit Data Supplier</a></li>
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
                        <a href="{{ route('master.supplier.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <form action="{{ route('master.supplier.update', $supplier->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                <div class="form-group">
                                    <label for="nama_supplier" class=" form-control-label">Nama Supplier<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" name="nama_supplier" value="{{ old('nama_supplier', $supplier->nama_supplier) }}">
                                </div>
                                <div class="form-group">
                                    <label for="email" class=" form-control-label">Email<span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $supplier->email) }}" placeholder="Masukkan email">
                                </div>
                                <div class="form-group">
                                    <label for="wilayah" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                                    <textarea name="alamat" id="alamat" rows="4" @error('alamat') is-invalid @enderror" name="alamat" class="form-control">{{ old('alamat', $supplier->alamat) }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="contact" class=" form-control-label">Contact<span style="color: red">*</span></label>
                                    <input type="number" id="contact" @error('contact') is-invalid @enderror" name="contact" value="{{ old('contact', $supplier->contact) }}" class="form-control">
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
