<title>Tambah Data Supplier - Gecorp</title>

@extends('layouts.main')

@section('content')

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header">
                    <div class="page-title">
                        <h1 class="card-title"><strong>Tambah Data - Supplier</strong></h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('master.index')}}">Dashboard</a></li>
                            <li><a href="{{ route('master.supplier.index')}}">Data Supplier</a></li>
                            <li class="active">Tambah Data Supplier</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="content">
            <x-adminlte-alerts />
            <!-- Animated -->
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('master.supplier.index')}}" class="btn btn-danger"></i> Kembali</a>
                            </div>
                            <div class="card-body">
                                {{-- Content --}}
                                <div class="card-body card-block">
                                    <form action="{{ route('master.supplier.store')}}" method="post" class="">
                                        @csrf

                                        <input type="hidden" name="id" id="">

                                        <div class="form-group">
                                            <label for="nama_supplier" class=" form-control-label">Nama Supplier<span style="color: red">*</span></label>
                                            <input type="text" id="nama_supplier" name="nama_supplier" placeholder="Contoh : Supplier1" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class=" form-control-label">Email<span style="color: red">*</span></label>
                                            <input type="email" id="email" name="email" placeholder="Contoh : supplier1@gmail.com" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="wilayah" class=" form-control-label">Alamat<span style="color: red">*</span></label>
                                            <textarea name="alamat" id="alamat" rows="4" placeholder="Contoh : Jl. Nyimas Gandasari No.18 Plered - Cirebon" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact" class=" form-control-label">Contact<span style="color: red">*</span></label>
                                            <input type="number" id="contact" name="contact" placeholder="Contoh : 081xxxxxxxxx" class="form-control">
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-dot-circle-o"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- end Content --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->

        <!-- Footer -->
@endsection
