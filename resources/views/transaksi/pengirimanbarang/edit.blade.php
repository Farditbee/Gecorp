<title>Edit Detail Pengiriman - Gecorp</title>
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
                                            <h4 class="m-b-10 ml-3">Detail Pengiriman</h4>
                                        </div>
                                        <ul class="breadcrumb ">
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('master.pengirimanbarang.index')}}">Detail Pengiriman</a></li>
                                            <li class="breadcrumb-item"><a>Edit Detail Pengiriman</a></li>
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
                                        <a href="{{ route('master.pengirimanbarang.index')}}" class="btn btn-danger">
                                            <i class="ti-plus menu-icon"></i> Kembali
                                        </a>
                                        <!-- Input Search -->
                                    </div>
                                    <x-adminlte-alerts />
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <form action="{{ route('master.pengirimanbarang.update_status', $pengiriman_barang->id) }}" method="POST" class="">
                                                @csrf
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-barcode"></i> Nomor Resi</h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->no_resi }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-home"></i> Toko Pengirim</h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->toko->nama_toko }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-tag"></i> Nama Pengirim</h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->user->nama }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-truck"></i> Ekspedisi</h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->ekspedisi }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-home"></i> Toko Penerima</h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->tokos->nama_toko }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h5 class="mb-0"><i class="fa fa-calendar"></i> Tanggal Kirim</h5>
                                                            </div>
                                                            <div class="col">
                                                                <span class="badge badge-pill badge-secondary">{{ $pengiriman_barang->tgl_kirim }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <br>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Status</th>
                                                                    <th scope="col">No</th>
                                                                    <th scope="col">Nama Barang</th>
                                                                    <th scope="col">Qty</th>
                                                                    <th scope="col">Harga</th>
                                                                    <th scope="col">Total Harga</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $statuses = ['progress', 'success', 'failed'];
                                                                @endphp
                                                                @foreach ($pengiriman_barang->detail as $detail)
                                                                <input type="hidden" name="detail_ids[{{ $detail->id }}]" value="{{ $detail->id }}">
                                                                <tr>
                                                                    <td>
                                                                        @if ($detail->status == 'success')
                                                                        <!-- Jika status sudah 'success', tampilkan badge -->
                                                                        <span class="badge badge-success">Success</span>
                                                                        @else
                                                                        <select name="status_detail[{{ $detail->id }}]" id="status_detail_{{ $detail->id }}" class="form-control status-select">
                                                                            <option value="" disabled>Pilih Status</option>
                                                                            @foreach($statuses as $status)
                                                                                <option value="{{ $status }}" {{ $detail->status == $status ? 'selected' : '' }}>
                                                                                    {{ $status }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $detail->barang->nama_barang }}</td>
                                                                    <td>{{ $detail->qty }}</td>
                                                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                                    <td>Rp {{ number_format($detail->harga * $detail->qty, 0, ',', '.') }}</td>
                                                                </tr>
                                                                <!-- Modal for each item -->
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th scope="col" colspan="5" style="text-align:right">SubTotal</th>
                                                                    <th scope="col">Rp {{ number_format($pengiriman_barang->detail->sum(function($detail) {
                                                                        return $detail->harga * $detail->qty;
                                                                    }), 0, ',', '.') }}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <!-- Submit Button -->
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fa fa-dot-circle-o"></i> Simpan
                                                            </button>
                                                        </div>
                                                    </div>
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
