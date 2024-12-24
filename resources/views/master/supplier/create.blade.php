@extends('layouts.main')

@section('title')
    Tambah Data Supplier
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
                        <a href="{{ route('master.supplier.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
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
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
        <!-- /.content -->

        <!-- Footer -->
@endsection
