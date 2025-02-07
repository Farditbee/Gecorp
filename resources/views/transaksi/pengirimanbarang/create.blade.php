@extends('layouts.main')

@section('title')
    Tambah Pengiriman Barang
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('transaksi.pengirimanbarang.index') }}" class="btn btn-danger">Kembali</a>
                        </div>
                        <div class="card-body">
                            <x-adminlte-alerts />
                            <div class="custom-tab">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link {{ session('tab') == 'detail' ? '' : 'active' }}"
                                            id="tambah-tab" data-toggle="tab" href="#tambah" role="tab"
                                            aria-controls="tambah" aria-selected="true"
                                            {{ session('tab') == 'detail' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>Tambah
                                            Pengiriman</a>
                                        <a class="nav-item nav-link {{ session('tab') == 'detail' ? 'active' : '' }}"
                                            id="detail-tab" data-toggle="tab" href="#detail" role="tab"
                                            aria-controls="detail" aria-selected="false"
                                            {{ session('tab') == '' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>Detail
                                            Pengiriman</a>
                                    </div>
                                </nav>
                                <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                    <div class="tab-pane fade show {{ session('tab') == 'detail' ? '' : 'active' }}"
                                        id="tambah" role="tabpanel" aria-labelledby="tambah-tab">
                                        <br>
                                        <form action="{{ route('transaksi.pengirimanbarang.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="no_resi" class=" form-control-label">Nomor Resi<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" id="no_resi" name="no_resi"
                                                            placeholder="Contoh : 001" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="tgl_kirim" class="form-control-label">Tanggal
                                                            Kirim</label>
                                                        <input class="form-control" type="date" name="tgl_kirim"
                                                            id="tgl_kirim">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class=" form-control-label">Toko Pengirim<span
                                                                style="color: red">*</span></label>
                                                        <select class="form-control select2" name="toko_pengirim"
                                                            id="toko_pengirim" style="display: block;">
                                                            <option value="{{ $myToko->id }}">{{ $myToko->nama_toko }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="nama_pengirim" class=" form-control-label">Nama Pengirim
                                                            (Admin
                                                            Toko)<span style="color: red">*</span></label>
                                                        <select name="nama_pengirim" id="nama_pengirim"
                                                            class="form-control select2">
                                                            <option value="{{ auth()->user()->nama }}">
                                                                {{ auth()->user()->nama }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class=" form-control-label">Toko Penerima<span
                                                        style="color: red">*</span></label>
                                                <select class="form-control select2" name="toko_penerima" id="toko_penerima"
                                                    style="display: block;">
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="ekspedisi" class=" form-control-label">Ekspedisi</label>
                                                <input type="text" id="ekspedisi" name="ekspedisi"
                                                    placeholder="Contoh : Sicepat" class="form-control">
                                            </div>

                                            <button type="submit" style="float: right" class="btn btn-success"><i
                                                    class="fa fa-save"></i> Selanjutnya</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade {{ session('tab') == 'detail' ? 'show active' : '' }}"
                                        id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                        <br>
                                        @php
                                            $pengiriman_barang = session(
                                                'pengiriman_barang',
                                                $pengiriman_barang ?? null,
                                            );
                                        @endphp
                                        @if ($pengiriman_barang)
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <ul class="list-group list-group-flush">
                                                        <hr class="m-0">
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <strong><i class="fa fa-barcode"></i> Nomor Resi</strong>
                                                            <span
                                                                class="badge badge-primary">{{ $pengiriman_barang->no_resi }}</span>
                                                            <input type="hidden" id="id_pengiriman_barang"
                                                                value="{{ $pengiriman_barang->id }}">
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
                                            <div id="item-container">
                                                <div class="item-group">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <input type="hidden" name="tk_pengirim" id="tk_pengirim"
                                                                value="{{ $pengiriman_barang->toko_pengirim }}">
                                                            <div class="form-group">
                                                                <label for="id_barang" class="form-control-label">Nama
                                                                    Barang<span style="color: red">*</span></label>
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
                                            <div class="row">
                                                <div class="col-12">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Action</th>
                                                                <th scope="col">No</th>
                                                                <th scope="col">Nama Barang</th>
                                                                <th scope="col">Supplier</th>
                                                                <th scope="col">Qty</th>
                                                                <th scope="col" class="text-right">Harga</th>
                                                                <th scope="col" class="text-right">Total Harga</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="listData"></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th scope="col" colspan="6" class="text-right">
                                                                    SubTotal</th>
                                                                <th id="subTotal" scope="col" class="text-right">
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    <div class="form-group">
                                                        <button id="save-data" type="button"
                                                            class="btn btn-primary w-100">
                                                            <i class="fa fa-save"></i> Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <strong>Perhatian!</strong> Anda perlu menambahkan data pengiriman di tab
                                                "Tambah Pengiriman" terlebih dahulu.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('asset_js')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
@endsection

@section('js')
    <script>
        let selectOptions = [{
            id: '#toko_penerima',
            isUrl: '{{ route('master.toko') }}',
            isFilter: {
                is_delete: '{{ auth()->user()->id_toko }}',
                is_admin: true,
            },
            placeholder: 'Pilih Nama Toko',
        }, {
            id: '#id_barang',
            isFilter: {
                id_toko: '{{ auth()->user()->id_toko }}',
            },
            isUrl: '{{ route('master.barangKirim') }}',
            placeholder: 'Pilih Barang',
            isMinimum: 3,
        }];

        let subtotal = 0;
        let addedItems = new Set();
        let lastDeletedItem = null;
        const tglKirim = document.getElementById('tgl_kirim');

        if (tglKirim) {
            tglKirim.addEventListener('focus', function() {
                this.showPicker();
            });
        }

        function selectFormat(isParameter, isPlaceholder, isDisabled = true) {
            if (!$(isParameter).find('option[value=""]').length) {
                $(isParameter).prepend('<option value=""></option>');
            }

            $(isParameter).select2({
                disabled: isDisabled,
                dropdownAutoWidth: true,
                width: '100%',
                placeholder: isPlaceholder,
                allowClear: true,
            });
        }

        function addData() {
            document.getElementById('add-item-detail')?.addEventListener('click', async function() {
                let idBarang = document.getElementById('id_barang').value.trim();

                if (!idBarang) {
                    notificationAlert('error', 'Error', 'Harap pilih barang dengan benar!');
                    return;
                }

                try {
                    let response = await renderAPI('GET', '{{ route('master.getBarangKirim') }}', {
                        id_toko: '{{ auth()->user()->id_toko }}',
                        id_barang: idBarang
                    });

                    if (response.status === 200 && response.data.data) {
                        let item = response.data.data;
                        let idSupplier = item.id_supplier;

                        if (item.qty === 0) {
                            notificationAlert('error', 'Error', 'Barang ini tidak tersedia (qty = 0)!');
                            $('#id_barang').val(null).trigger('change');
                            return;
                        }

                        let existingRow = [...document.querySelectorAll('#listData tr')].find(row => {
                            let existingIdBarang = row.querySelector('input[name="id_barang[]"]')
                                ?.value;
                            let existingIdSupplier = row.querySelector('input[name="id_supplier[]"]')
                                ?.value;
                            return existingIdBarang == item.id_barang && existingIdSupplier ==
                                idSupplier;
                        });

                        if (existingRow) {
                            notificationAlert('warning', 'Pemberitahuan',
                                'Barang dengan supplier yang sama sudah ada!');
                            $('#id_barang').val(null).trigger('change');
                            return;
                        }

                        let elementData = encodeURIComponent(JSON.stringify(item));
                        let minQty = item.qty > 0 ? 1 : 0;
                        let maxQty = item.qty;
                        let harga = item.harga;
                        let qty = 1;

                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-trash-alt mr-1"></i>Remove</button></td>
                            <td class="numbered">${document.querySelectorAll('#listData tr').length + 1}</td>
                            <td>
                                <input type="hidden" name="id_barang[]" value="${item.id_barang}">
                                <input type="hidden" name="id_supplier[]" value="${idSupplier}">
                                ${item.nama_barang}
                            </td>
                            <td class="supplier-text">${item.nama_supplier}</td>
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
                            }

                            qtyInput.value = newQty;
                            updateTotalHarga(row);
                            await updateRowTable(elementData, newQty);
                        }, 500));

                        document.querySelector('#listData').appendChild(row);
                        updateTotalHarga(row);

                        $('#id_barang').val(null).trigger('change');
                    } else {
                        notificationAlert('error', 'Pemberitahuan', 'Harga barang tidak ditemukan.');
                    }
                } catch (error) {
                    notificationAlert('error', 'Pemberitahuan', 'Gagal mendapatkan harga barang.');
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
                        id_pengiriman_barang: $('#id_pengiriman_barang').val(),
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

        async function deleteRowTable(rawData) {
            try {
                let data = JSON.parse(decodeURIComponent(rawData));
                const postDataRest = await renderAPI(
                    'DELETE',
                    '{{ route('delete.temp.pengiriman') }}', {
                        id_pengiriman_barang: $('#id_pengiriman_barang').val(),
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

        async function createRowTable(rawData) {
            try {
                let data = JSON.parse(decodeURIComponent(rawData));

                let formData = {
                    id_pengiriman_barang: $('#id_pengiriman_barang').val(),
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
                    dangerMode: true,
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

                    $("#listData tr").each(function() {
                        const barang = $(this).find("input[name='id_barang[]']").val();
                        const supplier = $(this).find("input[name='id_supplier[]']")
                            .val();
                        const jumlah = $(this).find(".qty-input").val();
                        const harga_barang = $(this).find(".harga-text").data("value");
                        const total = $(this).find(".total-harga").data("value");

                        if (barang && supplier && jumlah && harga_barang) {
                            id_barang.push(barang);
                            id_supplier.push(supplier);
                            qty.push(parseInt(jumlah, 10));
                            harga.push(parseInt(harga_barang, 10));
                            total_harga.push(parseInt(total, 10));
                        }
                    });

                    const formData = {
                        id_pengiriman_barang: $('#id_pengiriman_barang').val(),
                        id_barang,
                        id_supplier,
                        qty,
                        harga,
                        total_harga
                    };

                    try {
                        const postData = await renderAPI('POST',
                            '{{ route('save.pengiriman') }}', formData);
                        loadingPage(false);

                        if (postData.status >= 200 && postData.status < 300) {
                            swal("Berhasil!", "Data berhasil disimpan.", "success");

                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('transaksi.pengirimanbarang.index') }}';
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
                });
            });
        }

        async function initPageLoad() {
            await selectData(selectOptions);
            await selectFormat('#toko_pengirim', 'Pilih Toko');
            await selectFormat('#nama_pengirim', 'Pilih Pengirim');
            await addData();
            await saveData();
        }
    </script>
@endsection
