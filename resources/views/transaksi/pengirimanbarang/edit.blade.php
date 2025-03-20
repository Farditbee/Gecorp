@extends('layouts.main')

@section('title')
    Edit Pengiriman Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <style>
        .neumorphic-checkbox {
            width: 24px;
            height: 24px;
            appearance: none;
            background: #e0e0e0;
            border-radius: 6px;
            box-shadow: 2px 2px 2px #606060, -4px -4px 8px #ffffff;
            position: relative;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .neumorphic-checkbox:checked {
            background: #2ecc71;
            box-shadow: inset 4px 4px 8px #0056b3, inset -4px -4px 8px #339dff;
        }

        .neumorphic-checkbox:checked::after {
            content: '✔';
            color: white;
            font-size: 16px;
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
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
                            <a href="{{ route('transaksi.pengirimanbarang.index') }}" class="btn btn-danger">
                                <i class="ti-plus menu-icon"></i> Kembali
                            </a>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive-md">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    @if ($pengiriman_barang->toko_penerima == auth()->user()->id_toko && $pengiriman_barang->status !== 'success')
                                                        <th scope="col" class="text-wrap text-center">
                                                            <input type="checkbox" id="checkAll"
                                                                class="neumorphic-checkbox mr-2">
                                                        </th>
                                                    @endif
                                                    <th scope="col" class="text-wrap text-center">No</th>
                                                    <th scope="col" class="text-wrap">Status</th>
                                                    <th scope="col" class="text-wrap">Nama Barang</th>
                                                    <th scope="col" class="text-wrap">Nama Supplier</th>
                                                    <th scope="col" class="text-wrap">Qty</th>
                                                    <th scope="col" class="text-wrap text-right">Harga</th>
                                                    <th scope="col" class="text-wrap text-right">Total Harga</th>
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
                                                        @if (
                                                            $pengiriman_barang->toko_penerima == auth()->user()->id_toko &&
                                                                $pengiriman_barang->status !== 'success' &&
                                                                $detail->status !== 'success')
                                                            <td class="text-wrap text-center">
                                                                <input type="checkbox" data-id="{{ $detail->id }}"
                                                                    value=""
                                                                    class="neumorphic-checkbox status-check mr-2">
                                                            </td>
                                                        @elseif (
                                                            $pengiriman_barang->toko_penerima == auth()->user()->id_toko &&
                                                                $pengiriman_barang->status !== 'success' &&
                                                                $detail->status == 'success')
                                                            <td class="text-wrap text-center">
                                                                <input type="checkbox" data-id="{{ $detail->id }}"
                                                                    checked disabled value="{{ $detail->status }}"
                                                                    class="neumorphic-checkbox mr-2">
                                                            </td>
                                                        @endif
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td class="text-wrap">
                                                            @if ($detail->status == 'success')
                                                                <span class="badge badge-success"><i
                                                                        class="mr-1 fa fa-circle-check"></i>Sukses</span>
                                                            @elseif($detail->status == 'progress')
                                                                <span class="badge badge-warning"><i
                                                                        class="mr-1 fa fa-spinner"></i>Proses</span>
                                                            @else
                                                                <span class="badge badge-secondary">Tidak Diketahui</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-wrap">{{ $detail->barang->nama_barang }}</td>
                                                        <td class="text-wrap">{{ $detail->supplier->nama_supplier }}</td>
                                                        <td class="text-wrap">{{ $detail->qty }}</td>
                                                        <td class="text-nowrap text-right">Rp
                                                            {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                        <td class="text-nowrap text-right">Rp
                                                            {{ number_format($detail->harga * $detail->qty, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th scope="col" colspan="{{ $pengiriman_barang->status !== 'success' ? '7' : '6' }}" class="text-right text-wrap">SubTotal
                                                    </th>
                                                    <th class="text-nowrap text-right" scope="col">Rp
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
                                    </div>
                                    @if ($pengiriman_barang->status == 'progress')
                                        <div class="form-group">
                                            <button type="button" id="save-data" class="btn btn-success w-100">
                                                <i class="fa fa-save"></i> Simpan
                                            </button>
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
@endsection

@section('js')
    <script>
        async function setCheckAll() {
            const checkAll = document.getElementById("checkAll");
            const itemCheckboxes = document.querySelectorAll(".neumorphic-checkbox");

            checkAll.addEventListener("change", function() {
                itemCheckboxes.forEach(checkbox => {
                    if (!checkbox.disabled) {
                        checkbox.checked = checkAll.checked;
                        checkbox.value = checkAll.checked ? "success" : "";
                    }
                });
            });

            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    this.value = this.checked ? "success" : "";
                    checkAll.checked = [...itemCheckboxes].every(cb => cb.checked || cb.disabled);
                });
            });
        }

        async function saveData() {
            $(document).on("click", "#save-data", async function(e) {
                console.log('test kirim');
                e.preventDefault();
                const saveButton = document.getElementById('save-data');

                if (saveButton.disabled) return;

                swal({
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin mengonfirmasi semua data ini?",
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
                    
                    let detailIds = [];
                    let statusArray = [];
                    
                    $(".status-check").each(function() {
                        detailIds.push($(this).data("id"));
                        statusArray.push($(this).val());
                    });
                    
                    const formData = {
                        id_pengiriman_barang: '{{ $pengiriman_barang->id }}',
                        detail_ids: detailIds,
                        status_detail: statusArray,
                        tipe_kirim: '{{ $pengiriman_barang->tipe_pengiriman }}',
                        id_retur: '{{ $pengiriman_barang->id_retur }}' ? JSON.parse('{!! $pengiriman_barang->id_retur !!}').map(Number) : null,
                    };

                    try {
                        const postData = await renderAPI('POST',
                            '{{ route('transaksi.pengirimanbarang.update_status', $pengiriman_barang->id) }}',
                            formData);
                        loadingPage(false);

                        if (postData.status >= 200 && postData.status < 300) {
                            swal("Berhasil!", "Data berhasil disimpan.", "success");
                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('transaksi.pengirimanbarang.index') }}';
                            }, 1000);
                        } else {
                            swal("Pemberitahuan", postData.message ||
                                "Terjadi kesalahan saat menyimpan.", "warning");
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        swal("Error", "Terjadi kesalahan dalam mengirim data.", "error");
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
            if (
                '{{ $pengiriman_barang->toko_penerima == auth()->user()->id_toko && $pengiriman_barang->status !== 'success' }}'
            ) {
                await setCheckAll();
                await saveData();
            }
        }
    </script>
@endsection
