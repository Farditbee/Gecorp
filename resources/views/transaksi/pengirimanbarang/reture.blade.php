@extends('layouts.main')

@section('title')
    Retur Pengiriman Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <style>
        #qr_barang {
            background-color: white !important;
            cursor: text;
        }
    </style>
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('distribusi.pengirimanbarang.index') }}" class="btn btn-danger">Kembali</a>
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
                                        <form action="{{ route('transaksi.pengirimanbarang.storeReture') }}" method="POST">
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

                                            $detail_reture = session('detail_reture', $detail_reture ?? null);
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
                                            <div class="row">
                                                <div class="col-12">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">No</th>
                                                                <th scope="col">Metode Reture</th>
                                                                <th scope="col">QrCode</th>
                                                                <th scope="col">Nama Barang</th>
                                                                <th scope="col">Qty</th>
                                                                <th scope="col" class="text-right">Harga Beli</th>
                                                                <th scope="col" class="text-right">HPP Akhir</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="listData">
                                                            @php $subtotal = 0; @endphp
                                                            @foreach ($detail_reture as $dt)
                                                                @php $subtotal += $dt->harga_barang; @endphp
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td class="data-metode">{{ $dt->metode }}</td>
                                                                    <td class="data-qrcode">{{ $dt->qrcode }}</td>
                                                                    <td class="data-nama-barang">
                                                                        {{ $dt->barang->nama_barang }}</td>
                                                                    <td class="data-qty-acc">{{ $dt->qty_acc }}</td>
                                                                    <td class="data-harga-barang text-right">
                                                                        {{ number_format($dt->harga_barang, 2) }}</td>
                                                                    <td class="data-hpp-jual text-right">
                                                                        {{ number_format($dt->hpp_jual, 2) }}</td>

                                                                    <!-- Hidden input for data-detail -->
                                                                    <input type="hidden" class="data-detail" value="{{ $dt->detail_retur_id }}">
                                                                    <input type="hidden" class="data-idRetur" value="{{ $dt->retur_id }}">
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th scope="col" colspan="5" class="text-right">
                                                                    SubTotal</th>
                                                                <th scope="col" class="text-right">
                                                                    {{ number_format($subtotal, 2) }}</th>
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
    <script src="{{ asset('js/flatpickr.js') }}"></script>
@endsection

@section('js')
    <script>
        let selectOptions = [{
            id: '#toko_penerima',
            isUrl: '{{ route('master.toko') }}',
            isFilter: {
                is_delete: '{{ auth()->user()->id_toko }}',
                super_admin: true,
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

        async function setDatePicker() {
            flatpickr("#tgl_kirim", {
                dateFormat: "Y-m-d",
                defaultDate: new Date(),
                minDate: "today",
                allowInput: true,
                position: "above",
                onDayCreate: (dObj, dStr, fp, dayElem) => {
                    dayElem.addEventListener('click', () => {
                        fp.calendarContainer.querySelectorAll('.selected').forEach(el => {
                            el.style.backgroundColor = "#1abc9c";
                            el.style.color = "#fff";
                        });
                    });
                }
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

                    const id_pengiriman = $('#id_pengiriman_barang').val();
                    const qrcode = [];
                    const qty = [];
                    const harga_beli = [];
                    const detail_ids = [];
                    const returId = [];

                    $("#listData tr").each(function() {
                        qrcode.push($(this).find(".data-qrcode").text().trim());
                        qty.push(parseInt($(this).find(".data-qty-acc").text().trim(),
                            10));
                        harga_beli.push(parseFloat($(this).find(".data-harga-barang")
                            .text().replace(/,/g, '')));
                        detail_ids.push($(this).find(".data-detail").val());
                        returId.push($(this).find(".data-idRetur").val());
                    });

                    const formData = {
                        id_pengiriman,
                        qrcode,
                        qty,
                        harga_beli,
                        detail_ids,
                        returId,
                    };

                    try {
                        const postData = await renderAPI('POST',
                            '{{ route('transaksi.pengirimanbarang.storeDetailReture') }}', formData);
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
                    swal("Kesalahan", resp || "Terjadi kesalahan saat menyimpan data.", "error");
                    return resp;
                });
            });
        }

        async function initPageLoad() {
            await setDatePicker();
            await selectData(selectOptions);
            await selectFormat('#toko_pengirim', 'Pilih Toko');
            await selectFormat('#nama_pengirim', 'Pilih Pengirim');
            await saveData();
        }
    </script>
@endsection
