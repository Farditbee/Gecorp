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
                                            <strong><i class="fa fa-user-tie"></i> Nama Member</strong>
                                            <span class="badge badge-primary">{{ $kasbon->member->nama_member }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong><i class="fa fa-file-text"></i> Sisa Hutang (Rp)</strong>
                                            <span>{{ intval($kasbon->utang_sisa) }}</span>
                                        </li>
                                        <hr class="m-0">
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <hr class="m-0">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <strong><i class="fa fa-calendar-day"></i> Tgl Kasbon</strong>
                                            <span>{{ $kasbon->created_at }}</span>
                                        </li>
                                        <hr class="m-0">
                                    </ul>
                                </div>
                            </div>
                            @if ($kasbon == 'BL')
                                <div id="item-container">
                                    <div class="item-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="bayar" class="form-control-label">
                                                        Jumlah Bayar <sup class="text-success">*</sup>
                                                    </label>
                                                    <input id="bayar" type="number" class="form-control" min="0"
                                                        max="{{ $kasbon->utang_sisa }}" placeholder="Masukkan Jumlah Bayar"
                                                        value="{{ intval($kasbon->utang_sisa) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tipe_bayar" class="form-control-label">
                                                        Tipe Bayar <sup class="text-success">*</sup>
                                                    </label>
                                                    <select class="form-control" name="tipe_bayar" id="tipe_bayar">
                                                        <option value="Tunai">Tunai</option>
                                                        <option value="Non Tunai">Non Tunai</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" id="save-data" class="btn btn-success btn-md mt-2">
                                                    <i class="fa fa-circle-plus"></i> Bayar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                                                @if (!empty($dt_kasbon))
                                                    @foreach ($dt_kasbon as $dt)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td class="text-wrap">{{ $dt->tgl_bayar }}</td>
                                                            <td class="text-wrap">Rp. {{ intval($dt->bayar) }}</td>
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
                }).then((result) => {
                    if (!result) return;

                    saveButton.disabled = true;
                    const originalContent = saveButton.innerHTML;
                    saveButton.innerHTML =
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan`;
                    loadingPage(true);

                    const formData = {
                        id_kasbon: {{ $kasbon->id }},
                        bayar: $('#bayar').val(),
                        tipe_bayar: $('#tipe_bayar').val(),
                        id_member: {{ $kasbon->member->id }},
                    };

                    return renderAPI('POST', '{{ route('kasbon.bayar') }}', formData)
                        .then((postData) => {
                            loadingPage(false);

                            if (postData.status >= 200 && postData.status < 300) {
                                swal("Berhasil!", "Data berhasil disimpan.", "success");
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                swal("Pemberitahuan", postData.data.message ||
                                    "Terjadi kesalahan saat menyimpan.", "warning");
                            }
                        })
                        .catch((error) => {
                            loadingPage(false);
                            swal("Pemberitahuan", error.response?.data?.message ||
                                "Terjadi kesalahan saat menyimpan.", "warning");
                        })
                        .finally(() => {
                            saveButton.disabled = false;
                            saveButton.innerHTML = originalContent;
                        });
                });
            });
        }

        async function initPageLoad() {
            await saveData();
        }
    </script>
@endsection
