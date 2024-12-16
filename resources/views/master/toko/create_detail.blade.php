@extends('layouts.main')

@section('title')
    Tambah Detail Toko
@endsection

@section('content')

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header">
                    <div class="page-title">
                        <h1 class="card-title"><strong>Tambah Detail - Toko</strong></h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('master.index')}}">Dashboard</a></li>
                            <li><a href="{{ route('master.toko.detail', ['id' => $toko->id]) }}">Detail Toko</a></li>
                            <li class="active">Tambah Detail Toko</li>
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
                                <a href="{{ route('master.toko.detail', ['id' => $toko->id]) }}" class="btn btn-danger"></i> Kembali</a>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <h3><i class="fa fa-home"></i> Nama Toko <span class="badge badge-secondary pull-right">{{ old('nama_toko', $toko->nama_toko) }}</span></h3
                                    </li>
                                    <li class="list-group-item">
                                        <h3><i class="fa fa-globe"></i> Wilayah <span class="badge badge-secondary pull-right">{{ old('wilayah', $toko->wilayah) }}</span></h3
                                    </li>
                                    <li class="list-group-item">
                                        <h3><i class="fa fa-map-marker"></i> &nbsp;Alamat <span class="badge badge-secondary pull-right">{{ old('alamat', $toko->alamat) }}</span></h3
                                    </li>
                                </ul>
                                <br>
                                {{-- Content --}}
                                <div class="card-body card-block">
                                    <form action="{{ route('master.toko.store_detail')}}" method="post" class="">
                                        @csrf
                                        <input type="hidden" name="id_toko" id="" value="{{ $toko->id }}">
                                        <div class="form-group">
                                            <label for="id_barang" class=" form-control-label">Nama Barang</label>
                                            <select name="id_barang" required class="standardSelect">
                                                <option value="" required>~Pilih Barang~</option>
                                                @foreach ($barang as $brg)
                                                    <option value="{{ $brg->id }}">{{ $brg->nama_barang }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label for="stock" class=" form-control-label">Stock<span style="color: red">*</span></label>
                                            <input type="number" id="stock" name="stock" placeholder="Contoh : 20" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="harga" class=" form-control-label">Harga/Item<span style="color: red">*</span></label>
                                            <input type="text" id="harga" name="harga" placeholder="Contoh : 50000" class="form-control">
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-dot-circle-o"></i> Tambah
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
        <script>
            document.getElementById('harga').addEventListener('input', function (e) {
                let value = e.target.value;

                // Hapus semua karakter non-digit kecuali tanda desimal
                value = value.replace(/[^\d,]/g, '');

                // Pisahkan angka menjadi bagian integer dan desimal jika ada
                let parts = value.split(',');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Ganti koma dengan titik sebagai pemisah ribuan

                // Gabungkan kembali bagian integer dan desimal (jika ada)
                e.target.value = parts.join(',');
            });
        </script>
@endsection
