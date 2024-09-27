<title>Edit Data Barang - Gecorp</title>
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
                                            <h4 class="m-b-10 ml-3">Data Barang</h4>
                                        </div>
                                        <ul class="breadcrumb ">
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('master.barang.index')}}">Data Barang</a></li>
                                            <li class="breadcrumb-item"><a>Edit Data Barang</a></li>
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
                                                    <select name="id_jenis_barang" class="form-control select2" tabindex="1">
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
                                                    <select name="id_brand_barang" class="form-control select2" tabindex="1">
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

<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈'; // Mengubah ikon sesuai dengan tipe
    });
</script>

@endsection
