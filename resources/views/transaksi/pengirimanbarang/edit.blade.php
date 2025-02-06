@extends('layouts.main')

@section('title')
    Edit Pengiriman Barang
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <a href="{{ route('transaksi.pengirimanbarang.index') }}" class="btn btn-danger">
                                <i class="ti-plus menu-icon"></i> Kembali
                            </a>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <form
                                    action="{{ route('transaksi.pengirimanbarang.update_status', $pengiriman_barang->id) }}"
                                    method="POST" class="">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <hr class="m-0">
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong><i class="fa fa-barcode"></i> Nomor Resi</strong>
                                                    <span
                                                        class="badge badge-primary">{{ $pengiriman_barang->no_resi }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong><i class="fa fa-user"></i> Nama Pengirim</strong>
                                                    <span>{{ $pengiriman_barang->nama_pengirim }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong><i class="fa fa-home"></i> Toko Pengirim</strong>
                                                    <span>{{ $pengiriman_barang->toko->nama_toko }}</span>
                                                </li>
                                                <hr class="m-0">
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <hr class="m-0">
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong><i class="fa fa-truck"></i> Ekspedisi</strong>
                                                    <span>{{ $pengiriman_barang->ekspedisi }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong><i class="fa fa-calendar"></i> Tanggal Kirim</strong>
                                                    <span>{{ $pengiriman_barang->tgl_kirim }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <strong><i class="fa fa-home"></i> Toko Penerima</strong>
                                                    <span>{{ $pengiriman_barang->tokos->nama_toko }}</span>
                                                </li>
                                                <hr class="m-0">
                                            </ul>
                                        </div>
                                    </div>
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
                                                        <input type="hidden" name="detail_ids[{{ $detail->id }}]"
                                                            value="{{ $detail->id }}">
                                                        <tr>
                                                            <td>
                                                                @if ($detail->status == 'success')
                                                                    <span class="badge badge-success">Success</span>
                                                                @else
                                                                    <select name="status_detail[{{ $detail->id }}]"
                                                                        id="status_detail_{{ $detail->id }}"
                                                                        class="form-control status-select">
                                                                        <option value="" disabled>Pilih Status
                                                                        </option>
                                                                        @foreach ($statuses as $status)
                                                                            <option value="{{ $status }}"
                                                                                {{ $detail->status == $status ? 'selected' : '' }}>
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
                                                            <td>Rp
                                                                {{ number_format($detail->harga * $detail->qty, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th scope="col" colspan="5" style="text-align:right">SubTotal
                                                        </th>
                                                        <th scope="col">Rp
                                                            {{ number_format(
                                                                $pengiriman_barang->detail->sum(function ($detail) {
                                                                    return $detail->harga * $detail->qty;
                                                                }),
                                                                0,
                                                                ',',
                                                                '.',
                                                            ) }}
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            @if ($pengiriman_barang->status == 'progress')
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-dot-circle-o"></i> Simpan
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
