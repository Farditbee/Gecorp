@extends('layouts.main')

@section('title')
    Detail Pengiriman Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/notyf.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <style>
        #qr_barang {
            background-color: white !important;
            cursor: text;
        }

        @media (max-width: 768px) {

            .table td,
            .table th {
                white-space: normal !important;
                word-wrap: break-word;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <a href="{{ route('distribusi.pengirimanbarang.index') }}" class="btn btn-danger">
                                <i class="ti-plus menu-icon"></i> Kembali
                            </a>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body">
                            @if ($pengiriman_barang)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <hr class="m-0">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong><i class="fa fa-barcode"></i> Nomor Resi</strong>
                                                <span class="badge badge-primary">{{ $pengiriman_barang->no_resi }}</span>
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
                                @if ($pengiriman_barang->status === 'pending' && $pengiriman_barang->toko_pengirim == auth()->user()->id_toko)
                                    <div id="item-container">
                                        <div class="item-group">
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="qr_barang" class="form-control-label">
                                                            <i class="mr-2 fa fa-qrcode"></i>Scan QRCode Barang <sup
                                                                class="text-success">*</sup>
                                                        </label>
                                                        <input id="qr_barang" type="text" class="form-control"
                                                            placeholder="Gunakan alat Scan QRCode" readonly
                                                            onfocus="this.removeAttribute('readonly');"
                                                            onblur="this.setAttribute('readonly', true);">
                                                        <input type="hidden" id="hidden_qr">
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="form-group">
                                                        <label for="id_barang" class="form-control-label">
                                                            <i class="mr-2 fa fa-hand-pointer"></i>QRCode / Nama Barang <sup
                                                                class="text-success">*</sup>
                                                        </label>
                                                        <select class="form-control select2" name="id_barang"
                                                            id="id_barang"></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center">
                                                    <button type="button" id="add-item-detail"
                                                        class="btn btn-secondary w-100">
                                                        <i class="fa fa-circle-plus"></i> Add
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                @if ($pengiriman_barang->status === 'pending' && $pengiriman_barang->toko_pengirim == auth()->user()->id_toko)
                                                    <th>Aksi</th>
                                                @endif
                                                <th>No</th>
                                                <th>Qr Code Pembelian Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Nama Supplier</th>
                                                <th>Qty
                                                </th>
                                                <th class="text-right">
                                                    Harga</th>
                                                <th class="text-right">
                                                    Total Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData" class="text-wrap"></tbody>
                                        <tfoot id="totalData" class="text-wrap"></tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <strong>Perhatian!</strong> Anda perlu menambahkan data pengiriman di tab "Tambah
                                    Pengiriman" terlebih dahulu.
                                </div>
                            @endif
                        </div>
                        @if ($pengiriman_barang->status === 'pending' && $pengiriman_barang->toko_pengirim == auth()->user()->id_toko)
                            <div class="card-footer">
                            </div>
                        @endif
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
        let selectOptions = [{
            id: '#id_barang',
            isFilter: {
                id_toko: '{{ auth()->user()->id_toko }}',
            },
            isUrl: '{{ route('master.barangKirim') }}',
            placeholder: 'Pilih Barang',
            isMinimum: 3,
        }];

        $(document).ready(function() {
            let debounceTimer;

            $('#qr_barang').focus();

            $(document).on('click', function(event) {
                let target = $(event.target);

                if (
                    target.is('input, select, textarea') ||
                    target.closest('.select2-container').length > 0
                ) {
                    clearTimeout(debounceTimer);
                    return;
                }

                debounceTimer = setTimeout(function() {
                    $('#qr_barang').focus();
                }, 2000);
            });
        });

        $(document).ready(function() {
            $('#id_barang').on('select2:open', function() {
                setTimeout(function() {
                    let searchField = document.querySelector(
                        '.select2-container--open .select2-search__field');
                    if (searchField) {
                        searchField.focus();
                    }
                }, 100);
            });
        });

        async function getListData() {
            let filterParams = {};
            let getDataRest = await renderAPI(
                'GET',
                '{{ route('get.temp.pengiriman') }}', {
                    id_pengiriman_barang: '{{ $pengiriman_barang->id }}',
                    status: '{{ $pengiriman_barang->status }}',
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest.status === 200 && Array.isArray(getDataRest.data.data)) {
                setListData(getDataRest.data.data);
            } else {
                $('#listData').html('<tr><td colspan="7" class="text-center">Data tidak ditemukan</td></tr>');
            }
        }

        function setListData(data) {
            let rows = '';
            let subTotal = 0;
            let colSpan = ('{{ $pengiriman_barang->status }}' === 'pending' &&
                '{{ $pengiriman_barang->toko_pengirim }}' == '{{ auth()->user()->id_toko }}') ? 7 : 6;

            data.forEach((item, index) => {
                subTotal += item.total_harga;
                let minQty = item.qty > 0 ? 1 : 0;
                let maxQty = item.qty;
                let harga = item.harga;
                let qty = item.qty;
                let td_nama_barang = '';
                let td_qty = '';
                let actionButtons = '';

                if ('{{ $pengiriman_barang->status }}' === 'pending' &&
                    '{{ $pengiriman_barang->toko_pengirim }}' == '{{ auth()->user()->id_toko }}') {
                    actionButtons =
                        `<td><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-trash-alt mr-1"></i>Remove</button></td>`;
                    td_nama_barang = `
                        <input type="hidden" name="id_barang[]" value="${item.id_barang}">
                        <input type="hidden" name="id_supplier[]" value="${item.id_supplier}">
                        <input type="hidden" name="qrcode[]" value="${item.qrcode}">
                        <input type="hidden" name="id_detail_pembelian[]" value="${item.id_detail_pembelian}">
                        ${item.nama_barang}`;
                    td_qty = `
                        <input type="number" name="qty[]" style="min-width: 100px;" class="qty-input form-control" value="${qty}" min="${minQty}" max="${item.stock}" data-harga="${harga}">
                        <small class="text-danger">Max: ${item.stock}</small>`;
                } else {
                    td_nama_barang = item.nama_barang;
                    td_qty = item.qty;
                }

                rows += `
                    <tr data-id="${item.id_barang}" data-supplier="${item.id_supplier}">
                        ${actionButtons}
                        <td>${index + 1}</td>
                        <td style="min-width: 250px;" class="text-wrap">
                            <div class="d-flex flex-wrap align-items-center">
                                <span class="mr-1 mb-1 text-break" id="qrcode-text-${item.id}">${item.qrcode}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary copy-btn"
                                    data-toggle="tooltip" title="Salin: ${item.qrcode}" data-target="qrcode-text-${item.id}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td style="max-width: 150px;" class="text-wrap">${td_nama_barang}</td>
                        <td style="max-width: 150px;" class="text-wrap">${item.nama_supplier}</td>
                        <td class="text-right" style="min-width: 120px;">${td_qty}</td>
                        <td class="text-right harga-text" data-value="${harga}" style="min-width: 100px;">${formatRupiah(harga)}</td>
                        <td class="text-right total-harga" data-value="${harga * qty}" style="min-width: 100px;">${formatRupiah(harga * qty)}</td>
                    </tr>
                `;
            });

            const notyf = new Notyf({
                duration: 3000,
                position: {
                    x: 'center',
                    y: 'top',
                }
            });

            $('#listData').html(rows);

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
            $('[data-toggle="tooltip"]').tooltip();

            $('#totalData').html(`
                <tr>
                    <th scope="col" colspan="${colSpan}" class="text-right">SubTotal</th>
                    <th id="subTotal" scope="col" class="text-right">${formatRupiah(subTotal)}</th>
                </tr>
            `);

            if ('{{ $pengiriman_barang->status }}' === 'pending') {
                $('.card-footer').html(`
                    <button id="save-data" type="button" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Simpan
                    </button>`);

                document.querySelectorAll('.remove-item').forEach(button => {
                    button.addEventListener('click', function() {
                        let row = this.closest('tr');
                        let idBarang = row.getAttribute('data-id');
                        let idSupplier = row.getAttribute('data-supplier');
                        let elementData = JSON.stringify({
                            id_barang: idBarang,
                            id_supplier: idSupplier
                        });
                        removeItem(row, elementData);
                    });
                });

                document.querySelectorAll('.qty-input').forEach(input => {
                    input.addEventListener('input', debounce(async function() {
                        let row = this.closest('tr');
                        let newQty = parseInt(this.value) || 1;
                        let maxQty = parseInt(this.getAttribute('max'));
                        let harga = parseInt(row.querySelector('.harga-text').dataset.value) || 0;
                        let idBarang = row.getAttribute('data-id');
                        let idSupplier = row.getAttribute('data-supplier');
                        let elementData = JSON.stringify({
                            id_barang: idBarang,
                            id_supplier: idSupplier,
                            harga: harga
                        });

                        if (newQty < 1) {
                            newQty = 1;
                        } else if (newQty > maxQty) {
                            newQty = maxQty;
                        }

                        this.value = newQty;
                        updateTotalHarga(row);
                        await updateRowTable(elementData, newQty);
                    }, 500));
                });
            }
        }

        async function saveData() {
            $(document).on("click", "#save-data", async function(e) {
                e.preventDefault();
                const saveButton = document.getElementById('save-data');

                if (saveButton.disabled) return;

                swal({
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin menyimpan data ini?",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#2ecc71',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(async (willSave) => {
                    if (!willSave) return;

                    saveButton.disabled = true;
                    const originalContent = saveButton.innerHTML;
                    saveButton.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan`;

                    loadingPage(true);

                    const id_barang = [];
                    const id_supplier = [];
                    const qty = [];
                    const harga = [];
                    const total_harga = [];
                    const qrcode = [];

                    $("#listData tr").each(function() {
                        const barang = $(this).find("input[name='id_barang[]']").val();
                        const supplier = $(this).find("input[name='id_supplier[]']")
                            .val();
                        const qrCodes = $(this).find("input[name='qrcode[]']").val();
                        const jumlah = $(this).find(".qty-input").val();
                        const harga_barang = $(this).find(".harga-text").data("value");
                        const total = $(this).find(".total-harga").data("value");

                        if (barang && supplier && jumlah && harga_barang) {
                            id_barang.push(barang);
                            id_supplier.push(supplier);
                            qty.push(parseInt(jumlah, 10));
                            harga.push(parseInt(harga_barang, 10));
                            total_harga.push(parseInt(total, 10));
                            qrcode.push(qrCodes);
                        }
                    });

                    const formData = {
                        id_pengiriman_barang: '{{ $pengiriman_barang->id }}',
                        id_barang,
                        id_supplier,
                        qty,
                        harga,
                        total_harga,
                        qrcode,
                    };

                    try {
                        const postData = await renderAPI('POST',
                            '{{ route('save.pengiriman') }}', formData);
                        loadingPage(false);

                        if (postData.status >= 200 && postData.status < 300) {
                            swal("Berhasil!", "Data berhasil disimpan.", "success");

                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('distribusi.pengirimanbarang.index') }}';
                            }, 1000);
                        } else {
                            swal("Pemberitahuan", postData.message || "Terjadi kesalahan",
                                "info");
                        }
                    } catch (error) {
                        loadingPage(false);
                        const resp = error.response || {};
                        swal("Kesalahan", resp.data?.message ||
                            "Terjadi kesalahan saat menyimpan data.", "error");
                    } finally {
                        saveButton.disabled = false;
                        saveButton.innerHTML = originalContent;
                    }
                }).catch(function(error) {
                    let resp = error.response;
                    swal("Kesalahan", resp ||
                        "Terjadi kesalahan saat menyimpan data.", "error");
                    return resp;
                });
            });
        }

        function addData() {
            let qrInput = document.getElementById('qr_barang');
            let hiddenQrInput = document.getElementById('hidden_qr');

            qrInput.addEventListener('keypress', async function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    let qrCode = qrInput.value.trim();
                    if (!qrCode) return;

                    try {
                        let response = await renderAPI('GET', '{{ route('master.barangKirim') }}', {
                            page: 1,
                            limit: 30,
                            ascending: 1,
                            search: qrCode,
                            id_toko: '{{ auth()->user()->id }}',
                        });

                        if (response.status === 200 && response.data.data) {
                            let idBarang = response.data.data[0].id;

                            if (!idBarang) {
                                notificationAlert('error', 'Error', 'Barang tidak ditemukan!');
                                return;
                            }

                            $('#hidden_qr').val(idBarang);
                            document.getElementById('add-item-detail').click();
                        } else {
                            notificationAlert('error', 'Error',
                                'QR Code tidak valid atau barang tidak ditemukan.');
                        }
                    } catch (error) {
                        notificationAlert('error', 'Error', 'Terjadi kesalahan saat mencari barang.');
                    }

                    qrInput.value = '';
                    hiddenQrInput.value = '';
                }
            });

            document.getElementById('add-item-detail')?.addEventListener('click', async function() {
                let idBarang = document.getElementById('id_barang').value.trim();
                let hiddenQr = document.getElementById('hidden_qr').value.trim();

                if (!idBarang) {
                    idBarang = hiddenQr;
                }

                if (!idBarang) {
                    notificationAlert('error', 'Error', 'Harap pilih barang dengan benar!');
                    return;
                }

                let response = await renderAPI('GET', '{{ route('master.getBarangKirim') }}', {
                    id_toko: '{{ auth()->user()->id_toko }}',
                    id_barang: idBarang
                });

                if (response.status === 200 && response.data.data) {
                    let item = response.data.data;
                    let idSupplier = item.id_supplier;
                    let qrcode = item.qrcode;

                    if (item.qty === 0) {
                        notificationAlert('error', 'Error', 'Barang ini tidak tersedia (qty = 0)!');
                        $('#id_barang').val(null).trigger('change');
                        return;
                    }

                    let existingRow = [...document.querySelectorAll('#listData tr')].find(row => {
                        let existingIdBarang = row.querySelector('input[name="id_barang[]"]')?.value;
                        let existingQrcode = row.querySelector('input[name="qrcode[]"]')?.value;
                        return existingIdBarang == item.id_barang && existingQrcode == qrcode;
                    });

                    let elementData = encodeURIComponent(JSON.stringify(item));
                    let minQty = item.qty > 0 ? 1 : 0;
                    let maxQty = item.qty;
                    let harga = item.harga;

                    if (existingRow) {
                        let qtyInput = existingRow.querySelector('.qty-input');
                        let currentQty = parseInt(qtyInput.value) || 1;
                        let newQty = currentQty + 1;

                        if (newQty > maxQty) {
                            notificationAlert('error', 'Error',
                                `Maksimum Barang: ${item.nama_barang} (Stock: ${maxQty}) dari Supplier: ${item.nama_supplier} sudah tercapai!`
                            );
                            return;
                        }

                        qtyInput.value = newQty;
                        updateTotalHarga(existingRow);
                        await updateRowTable(elementData, newQty);
                    } else {
                        let qty = 1;

                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-trash-alt mr-1"></i>Remove</button></td>
                            <td class="numbered">${document.querySelectorAll('#listData tr').length + 1}</td>
                            <td>
                                <input type="hidden" name="id_barang[]" value="${item.id_barang}">
                                <input type="hidden" name="id_supplier[]" value="${idSupplier}">
                                <input type="hidden" name="qrcode[]" value="${qrcode}">
                                ${item.nama_barang}
                            </td>
                            <td class="supplier-text">${item.nama_supplier}</td>
                            <td class="qrcode-text">${item.qrcode}</td>
                            <td>
                                <input type="number" name="qty[]" class="qty-input form-control" value="${qty}"
                                    min="${minQty}" max="${maxQty}" data-harga="${harga}">
                                <small class="text-danger">Max: ${maxQty}</small>
                            </td>
                            <td class="text-right harga-text" data-value="${harga}">${formatRupiah(harga)}</td>
                            <td class="text-right total-harga" data-value="${harga * qty}">${formatRupiah(harga * qty)}</td>
                        `;

                        row.querySelector('.remove-item').addEventListener('click', function() {
                            removeItem(row, elementData);
                        });

                        await createRowTable(elementData);

                        let qtyInput = row.querySelector('.qty-input');
                        qtyInput.addEventListener('input', debounce(async function() {
                            let newQty = parseInt(qtyInput.value) || 1;

                            if (newQty < 1) {
                                newQty = 1;
                            } else if (newQty > maxQty) {
                                newQty = maxQty;
                                notificationAlert('error', 'Error',
                                    `Maksimum Barang: ${item.nama_barang} (Stock: ${maxQty}) dari Supplier: ${item.nama_supplier} sudah tercapai!`
                                );
                            }

                            qtyInput.value = newQty;
                            updateTotalHarga(row);
                            await updateRowTable(elementData, newQty);
                        }, 500));

                        document.querySelector('#listData').appendChild(row);
                        updateTotalHarga(row);
                    }

                    $('#id_barang').val(null).trigger('change');
                } else {
                    notificationAlert('error', 'Pemberitahuan', 'Harga barang tidak ditemukan.');
                }
            });
        }

        function updateTotalHarga(row) {
            let qtyInput = row.querySelector('.qty-input');
            let harga = parseInt(row.querySelector('.harga-text').dataset.value) || 0;
            let qty = parseInt(qtyInput.value) || 0;
            let total = qty * harga;

            row.querySelector('.total-harga').dataset.value = total;
            row.querySelector('.total-harga').textContent = formatRupiah(total);

            let newSubtotal = [...document.querySelectorAll('.total-harga')].reduce((sum, el) => {
                return sum + parseInt(el.dataset.value || 0);
            }, 0);

            document.getElementById('subTotal').textContent = formatRupiah(newSubtotal);
            document.getElementById('subTotal').dataset.value = newSubtotal;
        }

        async function updateRowTable(rawData, newQty) {
            try {
                let data = JSON.parse(decodeURIComponent(rawData));
                const postDataRest = await renderAPI(
                    'PUT',
                    '{{ route('update.temp.pengiriman') }}', {
                        id_pengiriman_barang: '{{ $pengiriman_barang->id }}',
                        id_barang: data.id_barang,
                        id_supplier: data.id_supplier,
                        qty: newQty,
                        harga: data.harga,
                    }
                );

                if (postDataRest && postDataRest.status === 200) {
                    let row = [...document.querySelectorAll('#listData tr')].find(tr => {
                        let rowIdBarang = tr.querySelector('input[name="id_barang[]"]')?.value;
                        let rowIdSupplier = tr.querySelector('input[name="id_supplier[]"]')?.value;
                        return rowIdBarang == data.id_barang && rowIdSupplier == data.id_supplier;
                    });

                    if (row) {
                        let qtyInput = row.querySelector('.qty-input');
                        if (qtyInput) {
                            let existingMsg = row.querySelector('.update-success');
                            if (existingMsg) existingMsg.remove();

                            let successMsg = document.createElement('small');
                            successMsg.innerHTML = '<i class="fas fa-circle-check"></i> Berhasil diperbarui';
                            successMsg.style.color = 'green';
                            successMsg.style.marginLeft = '8px';
                            successMsg.style.opacity = '1';
                            successMsg.style.transition = 'opacity 0.5s ease-in-out';

                            successMsg.classList.add('update-success');
                            qtyInput.parentElement.appendChild(successMsg);

                            setTimeout(() => {
                                successMsg.style.opacity = '0';
                                setTimeout(() => successMsg.remove(), 500);
                            }, 1000);
                        }
                    }
                }
            } catch (error) {
                const resp = error.response;
                const errorMessage = resp?.data?.message || 'Terjadi kesalahan saat memperbarui data.';
                notificationAlert('error', 'Kesalahan', errorMessage);
            }
        }

        function debounce(func, delay) {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), delay);
            };
        }

        function removeItem(row, data) {
            let totalHargaItem = parseInt(row.querySelector('.total-harga').dataset.value);
            let subtotal = parseInt(document.getElementById('subTotal').dataset.value || 0);

            subtotal -= totalHargaItem;
            document.getElementById('subTotal').textContent = formatRupiah(subtotal);
            document.getElementById('subTotal').dataset.value = subtotal;

            row.remove();
            deleteRowTable(data);
        }

        async function createRowTable(rawData) {
            try {
                let data = JSON.parse(decodeURIComponent(rawData));

                let formData = {
                    id_pengiriman_barang: '{{ $pengiriman_barang->id }}',
                    id_detail: data.id_detail,
                    id_barang: data.id_barang,
                    id_supplier: data.id_supplier,
                    qty: 1,
                    harga: data.harga,
                };

                const postData = await renderAPI('POST', '{{ route('temp.store.pengiriman') }}', formData);

                if (postData.status >= 200 && postData.status < 300) {
                    const response = postData.data.data;
                } else {
                    notificationAlert('info', 'Pemberitahuan', postData.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                loadingPage(false);
                const resp = error.response || {};
                notificationAlert('error', 'Kesalahan', resp.data?.message || 'Terjadi kesalahan saat menyimpan data.');
            }
        }

        async function deleteRowTable(rawData) {
            try {
                let data = JSON.parse(decodeURIComponent(rawData));
                const postDataRest = await renderAPI(
                    'DELETE',
                    '{{ route('delete.temp.pengiriman') }}', {
                        id_pengiriman_barang: '{{ $pengiriman_barang->id }}',
                        id_barang: data.id_barang,
                        id_supplier: data.id_supplier
                    }
                );
                if (postDataRest && postDataRest.status === 200) {}
            } catch (error) {
                const resp = error.response;
                const errorMessage = resp?.data?.message || 'Terjadi kesalahan saat menghapus data.';
                notificationAlert('error', 'Kesalahan', errorMessage);
            }
        }

        async function initPageLoad() {
            if ('{{ $pengiriman_barang->status }}' === 'pending' && '{{ $pengiriman_barang->toko_pengirim }}' ==
                '{{ auth()->user()->id_toko }}') {
                await selectData(selectOptions);
                await addData();
                await saveData();
            }
            await getListData();
        }
    </script>
@endsection
