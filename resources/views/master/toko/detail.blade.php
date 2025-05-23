@extends('layouts.main')

@section('title')
    Detail Toko
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
                        <a href="{{ route('master.toko.index')}}" class="btn btn-danger">
                            <i class="ti-plus menu-icon"></i> Kembali
                        </a>
                        <!-- Input Search -->
                        <form class="d-flex" method="GET" action="{{ route('master.toko.detail', $toko->id) }}">
                            <input class="form-control me-2" id="search" type="search" name="search" placeholder="Cari Barang" aria-label="Search">
                        </form>
                    </div>
                    <x-adminlte-alerts />
                    <div class="card-body table-border-style">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-3">
                                        <span class="mb-0 font-weight-bold"><i class="fa fa-home"></i> Nama Toko</span>
                                    </div>
                                    <div class="col">
                                        <span style="font-size: 16px;" class="badge badge-pill badge-secondary">{{ old('nama_toko', $toko->nama_toko) }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-3">
                                        <span class="mb-0 font-weight-bold"><i class="fa fa-globe"></i> Wilayah</span>
                                    </div>
                                    <div class="col">
                                        <span style="font-size: 16px;" class="badge badge-pill badge-secondary">{{ old('wilayah', $toko->wilayah) }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-3">
                                        <span class="mb-0 font-weight-bold"><i class="fa fa-map-marker"></i> Alamat</span>
                                    </div>
                                    <div class="col">
                                        <span style="font-size: 16px;" class="badge badge-pill badge-secondary">{{ old('alamat', $toko->alamat) }}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-3">
                                        <span class="mb-0 font-weight-bold"><i class="fa fa-sitemap"></i> Level Harga</span>
                                    </div>
                                    <div class="col">
                                        @if(!empty($levelhargas))
                                        @foreach($levelhargas as $levelharga)
                                            <span style="font-size: 16px;" class="badge badge-pill badge-secondary">{{ $levelharga->nama_level_harga }}</span>
                                            @if (!$loop->last), @endif
                                        @endforeach
                                    @else
                                        Tidak Ada Level Harga
                                    @endif
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-striped" id="jsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="barcode-column">Barcode</th>
                                        <th>Nama Barang</th>
                                        <th>QR Code Pembelian</th>
                                        <th>Stock</th>
                                        <th>Harga Satuan (Hpp Baru)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @if($toko->id == 1)
                                    @forelse ($stock as $stk)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td class="barcode-column">{{ $stk->barang->barcode }}</td>
                                        <td>{{ $stk->nama_barang }}</td>
                                        <td>{{ $stk->qrcode }}</td>
                                        <td>{{ $stk->stock }}</td>
                                        <td>Rp. {{ number_format($stk->hpp_baru, 0, '.', '.') }}</td>
                                        <td>
                                            <form onsubmit="return confirm('Ingin menghapus Data ini ?');" action="#" method="post">
                                                <a href="#" class="btn btn-warning btn-sm disabled">
                                                    <i class="fa fa-edit menu-icon"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button disabled type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6">Tidak ada data.</td>
                                    </tr>
                                    @endforelse
                                    @else
                                    @forelse ($detail_toko as $dt)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td class="barcode-column">{{ $dt->barang->barcode }}</td>
                                        <td>{{ $dt->barang->nama_barang }}</td>
                                        <td>{{ $dt->qrcode }}</td>
                                        <td>{{ $dt->qty }}</td>
                                        <td>Rp. {{ number_format($dt->harga, 0, ',', '.') }}</td>
                                        <form onsubmit="return confirm('Ingin menghapus Data ini ?');" action="{{ route('master.toko.delete_detail', ['id_toko' => $dt->id_toko, 'id_barang' => $dt->id_barang, 'id' => $dt->id]) }}" method="post">
                                        <td>
                                                <a href="{{ route('master.toko.edit_detail', ['id_toko' => $dt->id_toko, 'id_barang' => $dt->id_barang, 'id' => $dt->id]) }}" class="btn btn-warning btn-sm disabled">
                                                    <i class="fa fa-edit menu-icon"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button disabled type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash menu-icon"></i></button>
                                            </td>
                                        </form>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" style="text-align: center">Tidak ada Barang di Toko ini!</td>
                                    </tr>
                                    @endforelse
                                    @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    Menampilkan <span id="current-count">0</span> data dari <span id="total-count">0</span> total data.
                                </div>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end" id="pagination">
                                      {{-- isian paginate --}}
                                    </ul>
                                  </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
    // Menyembunyikan kolom barcode
    $(".barcode-column").hide();
});
</script>

@endsection
