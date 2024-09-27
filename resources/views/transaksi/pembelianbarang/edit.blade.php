<title>Edit Detail Pembelian - Gecorp</title>
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
                                            <h4 class="m-b-10 ml-3">Detail Pembelian</h4>
                                        </div>
                                        <ul class="breadcrumb ">
                                            <li class="breadcrumb-item ml-3"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('master.pembelianbarang.index')}}">Detail Pembelian</a></li>
                                            <li class="breadcrumb-item"><a>Edit Detail Pembelian</a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- [ Main Content ] start -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <a href="{{ route('master.pembelianbarang.index')}}" class="btn btn-danger">Kembali</a>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('master.pembelianbarang.update_status', $pembelian->id) }}" method="POST">
                                        @csrf
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-barcode"></i> Nomor Nota
                                                        </h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pembelian->no_nota }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-home"></i> Nama Supplier
                                                        </h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pembelian->supplier->nama_supplier }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h5 class="mb-0"><i class="fa fa-calendar"></i> Tanggal Nota
                                                        </h5>
                                                    </div>
                                                    <div class="col">
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ $pembelian->tgl_nota }}</span>
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
                                                            <th scope="col">Level Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $statuses = ['progress', 'success', 'failed'];
                                                        @endphp
                                                        @foreach ($pembelian->detail as $detail)
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
                                                            <td>Rp {{ number_format($detail->harga_barang, 0, ',', '.') }}</td>
                                                            <td>Rp {{ number_format($detail->harga_barang * $detail->qty, 0, ',', '.') }}</td>
                                                            <td>
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-primary mb-1 atur-harga-btn"
                                                                    data-toggle="modal"
                                                                    data-target="#mediumModal-{{ $detail->id }}"
                                                                    data-id_barang="{{ $detail->id_barang }}"
                                                                    data-id="{{ $detail->id }}"
                                                                    {{ $detail->status == 'success' ? 'disabled' : '' }}>
                                                                    Atur Harga
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <!-- Modal for each item -->
                                                        <div class="modal fade" id="mediumModal-{{ $detail->id }}" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel-{{ $detail->id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="mediumModalLabel-{{ $detail->id }}">Atur Harga - {{ $detail->barang->nama_barang }}</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <!-- Form atau konten modal untuk mengatur harga -->
                                                                        <div class="row">
                                                                            <div class="col-7">
                                                                                <!-- Jumlah Item -->
                                                                                <div class="card border border-primary">
                                                                                    <div class="card-body">
                                                                                        <p class="card-text">Detail Stock<strong> (GSS)</strong></p>
                                                                                        <p class="card-text">Stock :<strong class="stock">0</strong></p>
                                                                                        <p class="card-text">Hpp Awal : <strong class="hpp-awal">Rp 0</strong></p>
                                                                                        <p class="card-text">Hpp Baru : <strong class="hpp-baru">Rp 0</strong></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-5">
                                                                                <!-- Harga Barang -->
                                                                                <div>
                                                                                    @foreach ($LevelHarga as $index => $level)
                                                                                    <div class="form-group">
                                                                                        <div class="input-group">
                                                                                            <div class="input-group-addon">{{ $level->nama_level_harga }}</div>
                                                                                            <input type="hidden" name="level_nama[]" value="{{ $level->nama_level_harga }}">
                                                                                            <input type="text" class="form-control level-harga" name="level_harga[{{ $detail->id }}][]" id="level_harga_{{ $detail->id }}_{{ $index }}" data-index="{{ $index }}" data-hpp-baru="0"> <!-- Tambahkan data-hpp-baru untuk menyimpan nilai hpp_baru -->
                                                                                            <div class="input-group-addon persen" id="persen_{{ $detail->id }}_{{ $index }}">0%</div> <!-- Tambahkan id persen untuk menampung persentase -->
                                                                                        </div>
                                                                                    </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th scope="col" colspan="5" style="text-align:right">SubTotal</th>
                                                            <th scope="col">Rp {{ number_format($pembelian->detail->sum(function($detail) {
                                                                return $detail->harga_barang * $detail->qty;
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

                        <!-- [ Main Content ] end -->
                        <!-- [ Main Content ] start -->
                        <!-- [ Main Content ] end -->
                    </div>
                </div>
            </div>
</div>

<style>
    .atur-harga-btn {
        display: none; /* Sembunyikan tombol secara default */
    }
</style>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-select');

    statusSelects.forEach(select => {
        const row = select.closest('tr');
        const aturHargaBtn = row.querySelector('.atur-harga-btn');

        // Set tombol "Atur Harga" tidak muncul secara default
        aturHargaBtn.style.display = 'none';

        // Event listener untuk mengubah visibilitas tombol berdasarkan status
        select.addEventListener('change', function() {
            const status = this.value;

            if (status === 'success') {
                aturHargaBtn.style.display = 'inline-block'; // Tampilkan tombol jika status "success"
            } else {
                aturHargaBtn.style.display = 'none'; // Sembunyikan tombol jika status bukan "success"
            }
        });
    });

    const aturHargaButtons = document.querySelectorAll('.atur-harga-btn');

    aturHargaButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            const id_barang = button.getAttribute('data-id_barang');
            const id_modal = button.getAttribute('data-id');
            const modalId = `#mediumModal-${id_modal}`; // Gunakan id_modal untuk modal yang tepat

            fetch(`/admin/get-stock/${id_barang}`)
                .then(response => response.json())
                .then(data => {
                    const modal = document.querySelector(modalId);
                    if (modal) {
                        let hppBaru = parseFloat(data.hpp_baru) || 0;

                        // Set nilai HPP baru di setiap input level harga
                        modal.querySelectorAll('.level-harga').forEach(function(input, index) {
                            input.setAttribute('data-hpp-baru', hppBaru);
                        });

                        modal.querySelector('.stock').textContent = data.stock;
                        modal.querySelector('.hpp-awal').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(data.hpp_awal)}`;
                        modal.querySelector('.hpp-baru').textContent = `Rp ${hppBaru.toLocaleString('id-ID')}`;

                        // Mengisi nilai level harga dari server ke dalam input
                        Object.keys(data.level_harga).forEach(function(level_name, index) {
                            const inputField = modal.querySelectorAll('input[name="level_harga[' + id_modal + '][]"]')[index];
                            if (inputField) {
                                inputField.value = data.level_harga[level_name];

                                // Hitung persentase langsung setelah mengisi nilai dari server
                                let levelHarga = parseFloat(inputField.value) || 0;
                                let persen = 0;
                                if (hppBaru > 0) {
                                    persen = ((levelHarga - hppBaru) / hppBaru) * 100;
                                }

                                // Tampilkan persentase
                                const persenElement = modal.querySelector(`#persen_${id_modal}_${index}`);
                                if (persenElement) {
                                    persenElement.textContent = `${persen.toFixed(2)}%`;
                                }
                            }
                        });
                    } else {
                        console.error(`Modal dengan ID ${modalId} tidak ditemukan.`);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    });

    // Event listener untuk menghitung ulang persentase saat input level harga berubah
    document.querySelectorAll('.level-harga').forEach(function(input) {
        input.addEventListener('input', function() {
            let hppBaru = parseFloat(input.getAttribute('data-hpp-baru')) || 0;
            let levelHarga = parseFloat(this.value) || 0;

            // Hitung persentase jika HPP baru lebih dari 0
            let persen = 0;
            if (hppBaru > 0) {
                persen = ((levelHarga - hppBaru) / hppBaru) * 100;
            }

            const index = this.getAttribute('data-index');
            const persenElement = document.getElementById(`persen_${this.getAttribute('name').match(/\d+/g)[0]}_${index}`);
            if (persenElement) {
                persenElement.textContent = `${persen.toFixed(2)}%`;
            }
        });
    });

});
</script>

@endsection
