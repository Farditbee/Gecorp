@extends('layouts.main')

@section('title')
    Edit Kasbon
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
                            <a href="{{ route('kasbon.index') }}" class="btn btn-danger">
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
                                            <strong><i class="fa fa-barcode"></i> Nama Member</strong>
                                            <span class="badge badge-primary">{{ $kasbon->member->nama_member }}</span>
                                        </li>
                                        <hr class="m-0">
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <hr class="m-0">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong><i class="fa fa-truck"></i> Tgl Kasbon</strong>
                                            <span>{{ $kasbon->created_at }}</span>
                                        </li>
                                        <hr class="m-0">
                                    </ul>
                                </div>
                            </div>

                            <div id="item-container">
                                <div class="item-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="qr_barang" class="form-control-label">
                                                    <i class="mr-2 fa fa-qrcode"></i>Jumlah Bayar <sup
                                                        class="text-success">*</sup>
                                                </label>
                                                <input id="qr_barang" type="text" class="form-control"
                                                    placeholder="Gunakan alat Scan QRCode" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="qr_barang" class="form-control-label">
                                                    <i class="mr-2 fa fa-qrcode"></i>Tipe Bayar <sup
                                                        class="text-success">*</sup>
                                                </label>
                                                <input id="qr_barang" type="text" class="form-control"
                                                    placeholder="Gunakan alat Scan QRCode" >
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center">
                                            <button type="button" id="add-item-detail"
                                                class="btn btn-success btn-sm">
                                                <i class="fa fa-circle-plus"></i> Bayar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive-md">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-wrap text-center">No</th>
                                                    <th scope="col" class="text-wrap text-center">Tgl Bayar</th>
                                                    <th scope="col" class="text-wrap text-center">Jml Bayar</th>
                                                    <th scope="col" class="text-wrap text-center">Tipe Bayar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (empty($dt_kasbon))
                                                    @foreach ($dt_kasbon as $dt)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td class="text-wrap">{{ $dt->tgl_bayar }}</td>
                                                            <td class="text-wrap">{{ $dt->bayar }}</td>
                                                            <td class="text-wrap">{{ $dt->tipe_bayar }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada data.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
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
@endsection

@section('js')
    <script>

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
                        id_pengiriman_barang: '',
                        detail_ids: detailIds,
                        status_detail: statusArray,
                        tipe_kirim: '',
                    };

                    try {
                        const postData = await renderAPI('POST',
                            '{{ route('kasbon.bayar') }}',
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
            await saveData();
        }
    </script>
@endsection
