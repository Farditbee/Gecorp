@extends('layouts.main')

@section('title')
    Detail Pembelian Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/notyf.min.css') }}">
    <style>
        .atur-harga-btn {
            display: none;
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
            font-size: 12px;
            line-height: 1.2;
        }

        .status-select-small {
            height: 30px;
            font-size: 12px;
            padding: 4px 8px;
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
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-6 col-xxl-2 col-xl-2 col-lg-4">
                                            <h5 class="mb-0"><i class="fa fa-barcode"></i> Nomor Nota</h5>
                                        </div>
                                        <div class="col-6 col-xxl-10 col-xl-10 col-lg-8">
                                            <span id="no_nota" class="badge badge-pill badge-primary"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-6 col-xxl-2 col-xl-2 col-lg-4">
                                            <h5 class="mb-0"><i class="fa fa-user"></i> Nama Supplier</h5>
                                        </div>
                                        <div class="col-6 col-xxl-10 col-xl-10 col-lg-8">
                                            <span id="nama_supplier" class="badge badge-pill badge-secondary"></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-6 col-xxl-2 col-xl-2 col-lg-4">
                                            <h5 class="mb-0"><i class="fa fa-calendar-day"></i> Tanggal Nota</h5>
                                        </div>
                                        <div class="col-6 col-xxl-10 col-xl-10 col-lg-8">
                                            <span id="tgl_nota" class="badge badge-pill badge-secondary"></span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="tb-head">
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
                                            <tbody id="detail-body">
                                            </tbody>
                                            <tfoot id="detail-footer">
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-form" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="btn-close reset-all close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-xmark"></i></button>
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
        let title = 'Detail Pembelian Barang'
        // let storageUrl = '{{ asset('storage/uploads/po') }}'
        // let imageNullUrl = '{{ asset('assets/img/public/image_null.webp') }}'
        let urlParams = new URLSearchParams(window.location.search);
        let dataParams = urlParams.get('r');

        $('#modal-form').on('hidden.bs.modal', function() {
            $(this).find('.modal-body').html('');
        });

        async function showData() {
            $('#modal-form').on('hidden.bs.modal', function() {
                $(this).find('.modal-body').html('');
            });

            $(document).off("click", "#confirm-print").on("click", "#confirm-print", function() {
                const qty = parseInt($("#qty_print").val());
                const maxQty = parseInt($(this).data("max"));
                const qrCodePath = $(this).data("qrcode");
                const namaBarang = $(this).data("barang");

                if (isNaN(qty) || qty < 1 || qty > maxQty) {
                    notificationAlert('error', 'Error',
                        `Jumlah print tidak valid. Harus antara 1 hingga ${maxQty}`);
                    return;
                }

                const printWindow = window.open('', '_blank');

                let imagesHtml = '';
                for (let i = 0; i < qty; i++) {
                    if (i % 3 === 0) {
                        if (i !== 0) imagesHtml += `</div></div>`;
                        imagesHtml += `<div class="page"><div class="label-container">`;
                    }

                    let displayName = formatLabelText(namaBarang);


                    imagesHtml += `
                        <div class="label">
                            <img src="{{ asset('') }}/${qrCodePath}" alt="QR Code">
                            <div class="label-text">${displayName}</div>
                        </div>
                    `;

                    if (i === qty - 1) {
                        imagesHtml += `</div></div>`;
                    }
                }

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Print QR Code Pembelian</title>
                            <style>
                            @media print {
                                @page {
                                    size: 110mm 17mm;
                                    margin: 0; /* Hapus semua margin halaman */
                                }

                                body, html {
                                    margin: 0;
                                    padding: 0;
                                }

                                .page {
                                    page-break-after: always;
                                    width: 110mm;
                                    height: 17mm;
                                    margin: 0;   /* Pastikan tidak ada margin */
                                    padding: 0;  /* Pastikan tidak ada padding */
                                    box-sizing: border-box;
                                }
                            }

                                body {
                                    font-family: Arial, sans-serif;
                                    margin: 0;
                                    padding: 0;
                                }

                                .label-container {
                                    display: flex;
                                    flex-wrap: nowrap;
                                    justify-content: flex-start;
                                    column-gap: 2mm;
                                    padding: 0;
                                    margin: 0;
                                }

                                .label {
                                    width: 31mm;
                                    height: 15mm;
                                    display: flex;
                                    align-items: center;
                                    padding: 0;
                                    box-sizing: border-box;
                                    margin-top: 1mm;
                                    margin-bottom: 1mm;
                                    margin-left: 2mm;
                                }

                                .label img {
                                    width: 16mm;
                                    height: 16mm;
                                    object-fit: contain;
                                    margin-right: 1mm;
                                }

                                .label-text {
                                    font-size: 10px;
                                    line-height: 1.2;
                                    flex: 1;
                                }
                            </style>
                        </head>
                        <body>
                            ${imagesHtml}
                        </body>
                    </html>
                `);

                printWindow.document.close();

                printWindow.onload = function() {
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

        function formatLabelText(namaBarang) {
            const words = namaBarang.trim().split(/\s+/);
            let result = '';
            let totalLength = 0;

            for (let i = 0; i < words.length; i++) {
                let word = words[i];
                if (word.length > 7) {
                    word = word.substring(0, 7) + '..'; // now word is 9 chars
                }

                let wordWithSpace = (result ? ' ' : '') + word;
                if (totalLength + wordWithSpace.length > 40) {
                    result += '..';
                    break;
                }

                result += wordWithSpace;
                totalLength = result.length;
            }

            return result;
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

        $(document).on("click", ".open-modal-print-all", function() {
            const itemsJson = $(this).data("items"); // ini sudah otomatis decode URI dan parse JSON?

            // Kadang jQuery tidak otomatis parse JSON di data-* ketika berbentuk string JSON
            // Jadi perlu parse manual:
            let items;
            if (typeof itemsJson === "string") {
                items = JSON.parse(decodeURIComponent(itemsJson));
            } else {
                items = itemsJson;
            }

            $("#modal-form .modal-body").html("");
            $("#modal-title").html(`Form Print QR Code Semua Barang`);
            $("#modal-form").modal("show");

            let formHtml = `<form id="print-all-form">`;

            items.forEach((item, index) => {
                formHtml += `
                    <div class="mb-3">
                        <label class="form-label">${index + 1}. ${item.nama_barang}</label>
                        <input type="number" class="form-control qty-print-all"
                            name="qty_print_all[${item.id}]"
                            min="0" max="${item.qty}"
                            value="${item.qty}"
                            data-qrcode="${item.qrcode_path}"
                            data-nama="${item.nama_barang}">
                        <small class="form-text text-danger">Maksimum: ${item.qty}</small>
                    </div>
                `;
            });

            formHtml += `
                    <div class="justify-content-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-print mr-1"></i>Konfirmasi Print Semua
                        </button>
                    </div>
                </form>`;

            $("#modal-form .modal-body").html(formHtml);
        });


        $(document).off("submit", "#print-all-form").on("submit", "#print-all-form", function(e) {
            e.preventDefault();

            const printWindow = window.open('', '_blank');
            let imagesHtml = '';
            let count = 0;

            $(".qty-print-all").each(function() {
                const qty = parseInt($(this).val());
                const max = parseInt($(this).attr("max"));
                const qrCodePath = $(this).data("qrcode");
                const namaBarang = $(this).data("nama");

                if (!isNaN(qty) && qty > 0 && qty <= max) {
                    for (let i = 0; i < qty; i++) {
                        if (count % 3 === 0) {
                            if (count !== 0) imagesHtml += `</div></div>`;
                            imagesHtml += `<div class="page"><div class="label-container">`;
                        }

                        let displayName = formatLabelText(namaBarang);

                        imagesHtml += `
                            <div class="label">
                                <img src="{{ asset('') }}/${qrCodePath}" alt="QR Code">
                                <div class="label-text">${displayName}</div>
                            </div>
                        `;
                        count++;
                    }
                }
            });

            if (count > 0) {
                imagesHtml += `</div></div>`;

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Print QR Code Pembelian</title>
                            <style>
                                @media print {
                                    @page {
                                        size: 110mm 17mm;
                                        margin: 0;
                                    }

                                    body, html {
                                        margin: 0;
                                        padding: 0;
                                    }

                                    .page {
                                        page-break-after: always;
                                        width: 110mm;
                                        height: 17mm;
                                        margin: 0;
                                        padding: 0;
                                        box-sizing: border-box;
                                    }
                                }

                                body {
                                    font-family: Arial, sans-serif;
                                }

                                .label-container {
                                    display: flex;
                                    flex-wrap: nowrap;
                                    justify-content: flex-start;
                                    column-gap: 2mm;
                                }

                                .label {
                                    width: 31mm;
                                    height: 15mm;
                                    display: flex;
                                    align-items: center;
                                    padding: 0;
                                    box-sizing: border-box;
                                    margin-top: 1mm;
                                    margin-bottom: 1mm;
                                    margin-left: 2mm;
                                }

                                .label img {
                                    width: 16mm;
                                    height: 16mm;
                                    object-fit: contain;
                                    margin-right: 1mm;
                                }

                                .label-text {
                                    font-size: 10px;
                                    line-height: 1.2;
                                    flex: 1;
                                }
                            </style>
                        </head>
                        <body>${imagesHtml}</body>
                    </html>
                `);

                printWindow.document.close();

                printWindow.onload = function() {
                    printWindow.focus();
                    setTimeout(() => {
                        printWindow.print();
                    }, 0);
                };

                const handleAfterPrint = () => {
                    printWindow.close();
                    window.removeEventListener('afterprint', handleAfterPrint);
                };

                window.addEventListener('afterprint', handleAfterPrint);
            } else {
                notificationAlert('error', 'Error', 'Tidak ada barang yang dipilih untuk dicetak.');
            }
        });

        async function getDetailData() {
            try {
                let response = await renderAPI('GET', '{{ route('transaksi.pembelianbarang.Getdetail') }}', {
                    id_pembelian: dataParams
                });

                if (response.status === 200) {
                    const data = response.data.data;
                    const jsonItems = encodeURIComponent(JSON.stringify(data.detail));

                    $('#no_nota').text(data.no_nota || '-');
                    $('#nama_supplier').text(data.nama_supplier || '-');
                    $('#tgl_nota').text(data.tgl_nota || '-');

                    const detailBody = document.getElementById('detail-body');
                    const detailFooter = document.getElementById('detail-footer');

                    detailBody.innerHTML = ''; // kosongkan tabel
                    detailFooter.innerHTML = ''; // kosongkan footer

                    let subTotal = 0;

                    data.detail.forEach((item, index) => {
                        const total = item.qty * item.harga_barang;
                        subTotal += total;

                        detailBody.innerHTML += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td>
                            ${item.status === 'success'
                                ? `<span class="badge badge-success w-100"><i class="fas fa-circle-check mr-1"></i>Success</span>`
                                : `<select class="form-control">
                                                                                        <option value="" disabled ${!item.status ? 'selected' : ''}>Pilih Status</option>
                                                                                        <option value="progress" ${item.status === 'progress' ? 'selected' : ''}>progress</option>
                                                                                        <option value="success" ${item.status === 'success' ? 'selected' : ''}>success</option>
                                                                                        <option value="failed" ${item.status === 'failed' ? 'selected' : ''}>failed</option>
                                                                                    </select>`}
                        </td>
                        <td>
                            <div class="d-flex align-items-start" style="gap: 10px;">
                                <img src="{{ asset('') }}/${item.qrcode_path}" alt="QR Code" style="max-width: 50px; height: auto;">
                                <div class="d-flex flex-column">
                                    <span id="qrcode-text-${index}" class="mr-2 mb-1 text-dark font-weight-bold">${item.qrcode || '-'}</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-target="qrcode-text-${index}">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td style="word-wrap: break-word; white-space: normal;">${item.nama_barang}</td>
                        <td class="text-right">${item.qty}</td>
                        <td class="text-right">Rp ${Number(item.harga_barang).toLocaleString('id-ID')}</td>
                        <td class="text-right">Rp ${Number(total).toLocaleString('id-ID')}</td>
                        <td>
                            <div class="row">
                                <div class="col-12 col-xl-6 col-lg-12">
                                    <a href="{{ asset('') }}/${item.qrcode_path}" download class="btn btn-outline-success btn-sm w-100" data-container="body" data-toggle="tooltip" data-placement="top"
                                        title="Unduh QR Code Pembelian Barang">
                                        <i class="fa fa-download"></i> Unduh
                                    </a>
                                </div>
                                <div class="col-12 col-xl-6 col-lg-12">
                                    <button type="button" class="btn btn-outline-info btn-sm w-100 open-modal-print" data-container="body" data-toggle="tooltip" data-placement="top"
                                        title="Atur print QR Code Pembelian Barang"
                                        data-qty="${item.qty}" data-barang="${item.nama_barang}" data-qrcode="${item.qrcode_path}">
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                    });

                    // Footer subtotal
                    detailFooter.innerHTML = `
                    <tr>
                        <th colspan="6" class="text-right">SubTotal</th>
                        <th class="text-right">Rp ${Number(data.sub_total).toLocaleString('id-ID')}</th>
                        <th>
                            <button type="button" class="btn btn-info btn-sm w-100 open-modal-print-all" data-container="body" data-toggle="tooltip" data-placement="top"
                                title="Atur semua print QR Code Pembelian Barang"
                                data-items='${jsonItems}'>
                                <i class="fa fa-print"></i> Print Semua
                            </button>
                        </th>
                    </tr>`;
                    $('[data-toggle="tooltip"]').tooltip();
                    const notyf = new Notyf({
                        duration: 2000,
                        position: {
                            x: 'center',
                            y: 'top',
                        },
                    });

                    // Pasang event listener setelah elemen dimuat
                    document.querySelectorAll('.copy-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const targetId = this.getAttribute('data-target');
                            const targetText = document.getElementById(targetId)?.textContent;

                            if (targetText) {
                                navigator.clipboard.writeText(targetText).then(() => {
                                    notyf.success('QR Code berhasil disalin!');
                                }).catch(() => {
                                    notyf.error('Gagal menyalin QR Code');
                                });
                            } else {
                                notyf.error('Data QR Code tidak ditemukan');
                            }
                        });
                    });
                } else {
                    errorMessage = 'Tidak ada data';
                    let errorRow = `
                            <tr class="text-dark">
                                <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                            </tr>`;
                    $('#detail-body').html(errorRow);
                }
            } catch (err) {
                console.error('Fetch detail failed', err);
            }
        }

        async function initPageLoad() {
            await getDetailData();
            await showData();
        }
    </script>
@endsection
