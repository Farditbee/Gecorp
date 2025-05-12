@extends('layouts.main')

@section('title')
    Detail Pembelian Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/notyf.min.css') }}">
    <style>
        .atur-harga-btn {
            display: none;
            /* Sembunyikan tombol secara default */
        }

        .table tbody tr {
            height: 20px;
            line-height: 1.2;
        }

        .table tbody tr td {
            padding: 8px;
        }

        .btn-small {
            padding: 4px 8px;
            /* Mengatur padding tombol */
            font-size: 12px;
            /* Ukuran teks lebih kecil */
            line-height: 1.2;
            /* Tinggi baris */
        }

        .status-select-small {
            height: 30px;
            /* Tinggi elemen */
            font-size: 12px;
            /* Ukuran teks */
            padding: 4px 8px;
            /* Padding dalam elemen */
        }
    </style>
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <a href="{{ url()->previous() }}" class="btn btn-danger mb-2">Kembali</a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('transaksi.pembelianbarang.update_status', $pembelian->id) }}"
                                method="POST">
                                @csrf
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-2">
                                                <h5 class="mb-0"><i class="fa fa-barcode"></i> Nomor Nota</h5>
                                            </div>
                                            <div class="col">
                                                <span
                                                    class="badge badge-pill badge-primary">{{ $pembelian->no_nota }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-2">
                                                <h5 class="mb-0"><i class="fa fa-user"></i> Nama Supplier</h5>
                                            </div>
                                            <div class="col">
                                                <span
                                                    class="badge badge-pill badge-secondary">{{ $pembelian->supplier->nama_supplier }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-2">
                                                <h5 class="mb-0"><i class="fa fa-calendar-day"></i> Tanggal Nota</h5>
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
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th style="width: 40px;" class="text-center">No</th>
                                                        <th style="width: 50px;">Status</th>
                                                        <th style="min-width: 200px;">QR Code Pembelian Barang</th>
                                                        <th style="min-width: 200px;">Nama Barang</th>
                                                        <th class="text-right">Qty</th>
                                                        <th class="text-right">Harga</th>
                                                        <th class="text-right">Total</th>
                                                        <th>Download</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $statuses = ['progress', 'success', 'failed']; @endphp
                                                    @foreach ($pembelian->detail as $detail)
                                                        <input type="hidden" name="detail_ids[{{ $detail->id }}]"
                                                            value="{{ $detail->id }}">
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>
                                                                @if ($detail->status == 'success')
                                                                    <span class="badge badge-success w-100"><i
                                                                            class="fas fa-circle-check mr-1"></i>Success</span>
                                                                @else
                                                                    <select name="status_detail[{{ $detail->id }}]"
                                                                        id="status_detail_{{ $detail->id }}"
                                                                        class="form-control status-select">
                                                                        <option value="" disabled>Pilih Status
                                                                        </option>
                                                                        @foreach ($statuses as $status)
                                                                            <option value="{{ $status }}"
                                                                                {{ $detail->status == $status ? 'selected' : '' }}>
                                                                                {{ $status }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                            </td>
                                                            <td style="min-width: 200px;" class="text-wrap">
                                                                <div class="d-flex flex-wrap align-items-center">
                                                                    <span class="mr-2 mb-1"
                                                                        id="qrcode-text-{{ $detail->id }}">{{ $detail->qrcode }}</span>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-primary copy-btn"
                                                                        data-toggle="tooltip"
                                                                        title="Salin: {{ $detail->qrcode }}"
                                                                        data-target="qrcode-text-{{ $detail->id }}">
                                                                        <i class="fas fa-copy"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                            <td style="max-width: 200px;" class="text-wrap">{{ $detail->barang->nama_barang }}</td>
                                                            <td class="text-right">{{ $detail->qty }}</td>
                                                            <td class="text-right">Rp
                                                                {{ number_format($detail->harga_barang, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-right">Rp
                                                                {{ number_format($detail->harga_barang * $detail->qty, 0, ',', '.') }}
                                                            </td>
                                                            <td>
                                                                <a href="{{ asset($detail->qrcode_path) }}" download
                                                                    class="btn btn-outline-success btn-sm w-100">
                                                                    <i class="fa fa-download"></i> Unduh
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="6" class="text-right">SubTotal</th>
                                                        <th class="text-right">Rp
                                                            {{ number_format($pembelian->detail->sum(fn($d) => $d->harga_barang * $d->qty), 0, ',', '.') }}
                                                        </th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div> <!-- table-responsive -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('asset_js')
    <script src="{{ asset('js/notyf.min.js') }}"></script>
@endsection

@section('js')
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
                        aturHargaBtn.style.display =
                            'inline-block'; // Tampilkan tombol jika status "success"
                    } else {
                        aturHargaBtn.style.display =
                            'none'; // Sembunyikan tombol jika status bukan "success"
                    }
                });
            });

            const aturHargaButtons = document.querySelectorAll('.atur-harga-btn');

            aturHargaButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const id_barang = button.getAttribute('data-id_barang');
                    const id_modal = button.getAttribute('data-id');
                    const modalId =
                        `#mediumModal-${id_modal}`; // Gunakan id_modal untuk modal yang tepat

                    fetch(`/admin/get-stock/${id_barang}`)
                        .then(response => response.json())
                        .then(data => {
                            const modal = document.querySelector(modalId);
                            if (modal) {
                                let hppBaru = parseFloat(data.hpp_baru) || 0;

                                // Set nilai HPP baru di setiap input level harga
                                modal.querySelectorAll('.level-harga').forEach(function(input,
                                    index) {
                                    input.setAttribute('data-hpp-baru', hppBaru);
                                });

                                modal.querySelector('.stock').textContent = data.stock;
                                modal.querySelector('.hpp-awal').textContent =
                                    `Rp ${new Intl.NumberFormat('id-ID').format(data.hpp_awal)}`;
                                modal.querySelector('.hpp-baru').textContent =
                                    `Rp ${hppBaru.toLocaleString('id-ID')}`;

                                // Mengisi nilai level harga dari server ke dalam input
                                Object.keys(data.level_harga).forEach(function(level_name,
                                    index) {
                                    const inputField = modal.querySelectorAll(
                                        'input[name="level_harga[' + id_modal +
                                        '][]"]')[index];
                                    if (inputField) {
                                        inputField.value = data.level_harga[level_name];

                                        // Hitung persentase langsung setelah mengisi nilai dari server
                                        let levelHarga = parseFloat(inputField.value) ||
                                            0;
                                        let persen = 0;
                                        if (hppBaru > 0) {
                                            persen = ((levelHarga - hppBaru) /
                                                hppBaru) * 100;
                                        }

                                        // Tampilkan persentase
                                        const persenElement = modal.querySelector(
                                            `#persen_${id_modal}_${index}`);
                                        if (persenElement) {
                                            persenElement.textContent =
                                                `${persen.toFixed(2)}%`;
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

            const notyf = new Notyf({
                duration: 3000,
                position: {
                    x: 'center',
                    y: 'top',
                }
            });

            document.querySelectorAll('.copy-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const textToCopy = document.getElementById(targetId).innerText;

                    navigator.clipboard.writeText(textToCopy).then(function() {
                        notyf.success('QR Code berhasil disalin');
                    }).catch(function(err) {
                        notyf.error('Gagal menyalin QR Code');
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
                    const persenElement = document.getElementById(
                        `persen_${this.getAttribute('name').match(/\d+/g)[0]}_${index}`);
                    if (persenElement) {
                        persenElement.textContent = `${persen.toFixed(2)}%`;
                    }
                });
            });

        });
    </script>
@endsection
