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
                                            <div class="col-6 col-xxl-2 col-xl-2 col-lg-4">
                                                <h5 class="mb-0"><i class="fa fa-barcode"></i> Nomor Nota</h5>
                                            </div>
                                            <div class="col-6 col-xxl-10 col-xl-10 col-lg-8">
                                                <span
                                                    class="badge badge-pill badge-primary">{{ $pembelian->no_nota }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-6 col-xxl-2 col-xl-2 col-lg-4">
                                                <h5 class="mb-0"><i class="fa fa-user"></i> Nama Supplier</h5>
                                            </div>
                                            <div class="col-6 col-xxl-10 col-xl-10 col-lg-8">
                                                <span
                                                    class="badge badge-pill badge-secondary">{{ $pembelian->supplier->nama_supplier }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-6 col-xxl-2 col-xl-2 col-lg-4">
                                                <h5 class="mb-0"><i class="fa fa-calendar-day"></i> Tanggal Nota</h5>
                                            </div>
                                            <div class="col-6 col-xxl-10 col-xl-10 col-lg-8">
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
                                                        <th>Action</th>
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
                                                                <div class="d-flex align-items-start" style="gap: 10px;">
                                                                    <!-- Gambar di kiri -->
                                                                    <img src="{{ asset($detail->qrcode_path) }}"
                                                                        alt="QR Code"
                                                                        style="max-width: 50px; height: auto;">

                                                                    <!-- Kontainer kanan: span di atas, button di bawah -->
                                                                    <div class="d-flex flex-column">
                                                                        <span class="mr-2 mb-1 text-dark font-weight-bold"
                                                                            id="qrcode-text-{{ $detail->id }}">{{ $detail->qrcode }}</span>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-primary copy-btn"
                                                                            data-toggle="tooltip"
                                                                            title="Salin: {{ $detail->qrcode }}"
                                                                            data-target="qrcode-text-{{ $detail->id }}">
                                                                            <i class="fas fa-copy"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="max-width: 200px;" class="text-wrap">
                                                                {{ $detail->barang->nama_barang }}</td>
                                                            <td class="text-right">{{ $detail->qty }}</td>
                                                            <td class="text-right">Rp
                                                                {{ number_format($detail->harga_barang, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-right">Rp
                                                                {{ number_format($detail->harga_barang * $detail->qty, 0, ',', '.') }}
                                                            </td>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-12 col-xl-6 col-lg-12">
                                                                        <a href="{{ asset($detail->qrcode_path) }}"
                                                                            download
                                                                            class="btn btn-outline-success btn-sm w-100"
                                                                            title="Unduh QR Code Pembelian Barang"
                                                                            data-toggle="tooltip" data-placement="top">
                                                                            <i class="fa fa-download"></i> Unduh
                                                                        </a>
                                                                    </div>
                                                                    <div class="col-12 col-xl-6 col-lg-12">
                                                                        <button type="button"
                                                                            class="btn btn-outline-info btn-sm w-100 open-modal-print"
                                                                            title="Atur Print QR Code Pembelian Barang"
                                                                            data-qty="{{ $detail->qty }}"
                                                                            data-barang="{{ $detail->barang->nama_barang }}"
                                                                            data-qrcode="{{ asset($detail->qrcode_path) }}"
                                                                            data-toggle="tooltip" data-placement="top">
                                                                            <i class="fa fa-print"></i> Print
                                                                        </button>
                                                                    </div>
                                                                </div>
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
    <div id="modal-form" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="btn-close reset-all close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="fa fa-xmark"></i></button>
                </div>
                <div class="modal-body">
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
        $('#modal-form').on('hidden.bs.modal', function() {
            $(this).find('.modal-body').html('');
        });

async function showData() {
    $('#modal-form').on('hidden.bs.modal', function () {
        $(this).find('.modal-body').html('');
    });

    $(document).off("click", "#confirm-print").on("click", "#confirm-print", function () {
        const qty = parseInt($("#qty_print").val());
        const maxQty = parseInt($(this).data("max"));
        const qrCodePath = $(this).data("qrcode");
        const namaBarang = $(this).data("barang");

        if (isNaN(qty) || qty < 1 || qty > maxQty) {
            notificationAlert('error', 'Error', `Jumlah print tidak valid. Harus antara 1 hingga ${maxQty}`);
            return;
        }

        const printWindow = window.open('', '_blank');
        let imagesHtml = '';

        for (let i = 0; i < qty; i++) {
            imagesHtml += `
                <div class="label">
                    <img src="${qrCodePath}" alt="QR Code">
                    <div class="label-text">${namaBarang}</div>
                </div>
            `;
        }

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print QR Labels</title>
                    <style>
                        @media print {
                            @page {
                                size: auto;
                                margin: 0mm;
                            }
                            body {
                                margin: 0;
                                padding: 0;
                            }
                        }

                        body {
                            font-family: Arial, sans-serif;
                        }

                        .label-container {
    display: flex;
    flex-wrap: wrap;
    column-gap: 5mm; /* kiri-kanan */
    row-gap: 3.3mm;     /* bawah */
    margin-top: 0;    /* pastikan tidak ada gap atas */
}


                        .label {
                            width: 30mm;
                            height: 15mm;
                            display: flex;
                            align-items: center;
                            padding: 0mm;
                            box-sizing: border-box;
                        }

                        .label img {
                            width: 12mm;
                            height: 12mm;
                            object-fit: contain;
                            margin-right: 2mm;
                        }

                        .label-text {
                            font-size: 8px;
                            line-height: 1.2;
                            word-break: break-word;
                            flex: 1;
                        }
                    </style>
                </head>
                <body>
                    <div class="label-container">
                        ${imagesHtml}
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();

        printWindow.onload = function () {
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
            }, 0);
        };

        const handleAfterPrint = () => {
            printWindow.close();
            setTimeout(() => {
                const input = document.getElementById('qty_print');
                if (input) {
                    input.blur();
                    setTimeout(() => {
                        input.focus();
                        input.select();
                    }, 10);
                }
            }, 300);
            window.removeEventListener('afterprint', handleAfterPrint);
        };

        window.addEventListener('afterprint', handleAfterPrint);
    });
}




        $(document).on("click", ".open-modal-print", function() {
            const maxQty = $(this).data("qty");
            const qrCodePath = $(this).data("qrcode");
            const namaBarang = $(this).data("barang");

            $("#modal-form .modal-body").html("");
            $("#modal-title").html(`Form Print QR Code Pembelian Barang`);
            $("#modal-form").modal("show");

            $("#modal-form .modal-body").html(`
        <div class="mb-3">
            <label for="qty_print" class="form-label">Jumlah Print</label>
            <input type="number" id="qty_print" class="form-control" min="1" max="${maxQty}" value="${maxQty}">
            <small class="form-text text-danger">Maksimum: ${maxQty}</small>
        </div>
        <div class="justify-content-end">
            <button type="button" class="btn btn-primary w-100" id="confirm-print"
                data-qrcode="${qrCodePath}" data-barang="${namaBarang}" data-max="${maxQty}">
                <i class="fa fa-print mr-1"></i>Konfirmasi Print
            </button>
        </div>
    `);
        });

        async function initPageLoad() {
            await showData();
        }

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
