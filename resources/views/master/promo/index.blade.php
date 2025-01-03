@extends('layouts.main')

@section('title')
    Data Promo
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-2">
                                <a class="btn btn-primary mb-2 mb-lg-0 text-white" data-toggle="modal"
                                    data-target=".bd-example-modal-lg">
                                    <span data-container="body" data-toggle="tooltip" data-placement="top"
                                        title="Tambah Data Promo"><i class="fa fa-plus-circle mr-1"></i>Tambah</span>
                                </a>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <select name="limitPage" id="limitPage" class="form-control mr-2 mb-2 mb-lg-0"
                                    style="width: 100px;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                </select>
                                <input id="tb-search" class="tb-search form-control mb-2 mb-lg-0" type="search"
                                    name="search" placeholder="Cari Data" aria-label="search" style="width: 200px;">
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-hover m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Nama Barang</th>
                                                <th class="text-wrap align-top">Toko</th>
                                                <th class="text-wrap align-top">Diskon</th>
                                                <th class="text-wrap align-top">Jumlah</th>
                                                <th class="text-wrap align-top">Terjual</th>
                                                <th class="text-wrap align-top">Dari</th>
                                                <th class="text-wrap align-top">Sampai</th>
                                                <th class="text-wrap align-top">Status</th>
                                                <th class="text-center text-wrap align-top">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
                                    <div class="text-center text-md-start mb-2 mb-md-0">
                                        <div class="pagination">
                                            <div>Menampilkan <span id="countPage">0</span> dari <span
                                                    id="totalPage">0</span> data</div>
                                        </div>
                                    </div>
                                    <nav class="text-center text-md-end">
                                        <ul class="pagination justify-content-center justify-content-md-end"
                                            id="pagination-js">
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lgs">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Data Promo</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-custom alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                            class="bi bi-info-circle" viewBox="0 0 20 20">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                            <path
                                d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                        </svg>
                        Informasi
                    </h4>
                    <div>
                        <div class="text-bold d-flex align-items-center mb-2">
                            <em class="fa fa-circle mx-1"></em>
                            <span><strong class="fw-bold"></strong> Minimal : Minimal pembelian item agar promo nya aktif</span>
                        </div>
                        <div class="text-bold d-flex align-items-center mb-2">
                            <em class="fa fa-circle mx-1"></em>
                            <span><strong class="fw-bold"></strong> Jumlah : Total barang yang dipromokan, dan barang tersebut harus berkelipatan sebanyak item Minimal</span>
                        </div>
                        <div class="text-bold d-flex align-items-center">
                            <em class="fa fa-circle mx-1"></em>
                            <span><strong class="fw-bold"></strong> Diskon : Barang yang di diskon adalah hanya per item</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="form-tambah-pembelian" action="{{ route('master.promo.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <!-- Nama Supplier -->
                                <div class="form-group">
                                    <label for="toko" class="form-control-label">Nama toko</label>
                                    <select name="toko" id="toko">
                                        <option value="" selected>Pilih Toko</option>
                                        @foreach ($toko as $tk)
                                            <option value="{{ $tk->id }}">{{ $tk->nama_toko }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="barang" class="form-control-label">Nama Barang</label>
                                    <select name="barang" id="barang">
                                        <option value="" selected>Pilih Barang</option>
                                        @foreach ($barang as $brg)
                                            <option value="{{ $brg->id }}">{{ $brg->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <label for="minimal" class="form-control-label">Minimal</label>
                                <input class="form-control" type="number" min='1' name="minimal"
                                    id="minimal">
                            </div>

                            <div class="col-3">
                                <label for="id_supplier" class="form-control-label">Jumlah</label>
                                <input class="form-control" type="number" min='0' name="jumlah"
                                    id="jumlah">
                                <small id="jumlah-error" style="color: red; display: none;">Jumlah harus kelipatan
                                    dari minimal</small>
                            </div>

                            <div class="col-3">
                                <label for="id_supplier" class="form-control-label">Diskon</label>
                                <input class="form-control" type="number" min='0' max='100' name="diskon"
                                    id="diskon">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label for="id_supplier" class="form-control-label">Dari</label>
                                <input class="form-control" type="datetime-local" name="dari" id="dari">
                            </div>
                            <div class="col-6">
                                <label for="id_supplier" class="form-control-label">Sampai</label>
                                <input class="form-control" type="datetime-local" name="sampai" id="sampai">
                            </div>
                        </div><br>
                        <button type="submit" style="float: right" id="save-btn" class="btn btn-primary">
                            <span id="save-btn-text"><i class="fa fa-save"></i> Simpan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($promo as $prm)
        <div class="modal fade" id="mediumModal-{{ $prm->id }}" tabindex="-1" role="dialog"
            aria-labelledby="mediumModalLabel-{{ $prm->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lgs" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title h4" id="myLargeModalLabel">Edit Promo</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-tambah-pembelian" action="{{ route('master.promo.store') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-3">
                                    <!-- Nama Supplier -->
                                    <div class="form-group">
                                        <label for="barang" class="form-control-label">Nama Barang</label>
                                        <select name="barang" id="barang" class="form-control">
                                            <option value="" selected>Pilih Barang</option>
                                            @foreach ($barang as $brg)
                                                <option value="{{ $brg->id }}"
                                                    {{ old('id_barang', $prm->id_barang) == $brg->id ? 'selected' : '' }}>
                                                    {{ $brg->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="toko" class="form-control-label">Toko
                                            <select name="toko" id="toko" class="form-control">
                                                <option value="" selected>Pilih toko</option>
                                                @foreach ($toko as $tk)
                                                    <option value="{{ $tk->id }}"
                                                        {{ old('id_toko', $prm->id_toko) == $tk->id ? 'selected' : '' }}>
                                                        {{ $tk->nama_toko }}
                                                    </option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label for="minimal" class="form-control-label">Minimal</label>
                                    <input type="number" id="minimal" min='1' name="minimal"
                                        value="{{ old('minimal', $prm->minimal) }}" class="form-control">
                                </div>

                                <div class="col-3">
                                    <label for="jumlah" class="form-control-label">Jumlah</label>
                                    <input type="number" id="jumlah" min='0' name="jumlah"
                                        value="{{ old('jumlah', $prm->jumlah) }}" class="form-control">
                                    <small id="jumlah-error" style="color: red; display: none;">Jumlah harus
                                        kelipatan dari minimal</small>
                                </div>

                                <div class="col-3">
                                    <label for="diskon" class="form-control-label">diskon</label>
                                    <input type="number" id="diskon" min='0' name="diskon"
                                        value="{{ old('diskon', $prm->diskon) }}" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label for="dari" class="form-control-label">Dari</label>
                                    <input type="datetime-local" id="dari" min='0' name="dari"
                                        value="{{ old('dari', $prm->dari) }}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label for="sampai" class="form-control-label">Sampai</label>
                                    <input type="datetime-local" id="sampai" min='0' name="sampai"
                                        value="{{ old('sampai', $prm->sampai) }}" class="form-control">
                                </div>
                            </div><br>
                            <button type="submit" style="float: right" id="save-btn" class="btn btn-primary">
                                <span id="save-btn-text"><i class="fa fa-save"></i> Simpan</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('asset_js')
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
@endsection

@section('js')
    <script>
        let title = 'Promo';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getpromo') }}', {
                    page: page,
                    limit: limit,
                    ascending: ascending,
                    search: search,
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let handleDataArray = await Promise.all(
                    getDataRest.data.data.map(async item => await handleData(item))
                );
                await setListData(handleDataArray, getDataRest.data.pagination);
            } else {
                errorMessage = getDataRest?.data?.message;
                let errorRow = `
                            <tr class="text-dark">
                                <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                            </tr>`;
                $('#listData').html(errorRow);
                $('#countPage').text("0 - 0");
                $('#totalPage').text("0");
            }
        }

        async function handleData(data) {
            let status = '';
            let edit_button = '';
            if (data.status == 'Sukses') {
                status =
                    `<span class="badge badge-success"><i class="fa fa-circle-check mr-1"></i>${data.status}</span>`;
                edit_button = `<span class="badge badge-success"><i class="fa fa-circle-check"></i></span>`;
            } else if (data.status == 'On Going') {
                status = `<span class="badge badge-warning"><i class="fa fa-spinner mr-1"></i>${data.status}</span>`;
                edit_button = `
                <button class="p-1 btn edit-data action_button"
                    data-toggle="modal" data-target="#mediumModal-${data.id}"
                    data-id='${data.id}'>
                    <span class="text-dark" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Edit ${title}: ${data.nama_barang}">Edit</span>
                    <div class="icon text-warning" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Edit ${title}: ${data.nama_barang}">
                        <i class="fa fa-edit"></i>
                    </div>
                </button>`;
            } else if (data.status == 'Antrean') {
                status =
                    `<span class="badge badge-info"><i class="fa fa-circle-half-stroke mr-1"></i>${data.status}</span>`;
                edit_button = '-';
            } else {
                status =
                    `<span class="badge badge-secondary"><i class="fa fa-circle-check mr-1"></i>Tidak Diketahui</span>`;
                edit_button = '-';
            }

            return {
                id: data?.id ?? '-',
                nama_barang: data?.nama_barang ?? '-',
                nama_toko: data?.nama_toko ?? '-',
                diskon: data?.diskon ?? '-',
                jumlah: data?.jumlah ?? '-',
                terjual: data?.terjual ?? '-',
                dari: data?.dari ?? '-',
                sampai: data?.sampai ?? '-',
                status,
                edit_button,
            };
        }

        async function setListData(dataList, pagination) {
            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let getDataTable = '';
            let classCol = 'align-center text-dark text-wrap';
            dataList.forEach((element, index) => {
                getDataTable += `
                    <tr class="text-dark">
                        <td class="${classCol} text-center">${display_from + index}.</td>
                        <td class="${classCol}">${element.nama_barang}</td>
                        <td class="${classCol}">${element.nama_toko}</td>
                        <td class="${classCol}">${element.diskon} %</td>
                        <td class="${classCol}">${element.jumlah}</td>
                        <td class="${classCol}">${element.terjual}</td>
                        <td class="${classCol}">${element.dari}</td>
                        <td class="${classCol}">${element.sampai}</td>
                        <td class="${classCol}">${element.status}</td>
                        <td class="${classCol}">
                            <div class="d-flex justify-content-center w-100">
                                <div class="hovering p-1">
                                    ${element.edit_button}
                                </div>
                            </div>
                        </td>
                    </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#toko", {
                placeholder: "Cari Toko",
                allowClear: true, // Menambahkan tombol clear jika Anda membutuhkannya
                create: false // Agar user hanya bisa memilih dari opsi yang ada, bukan membuat opsi baru
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#barang", {
                placeholder: "Cari Barang",
                allowClear: true, // Menambahkan tombol clear jika Anda membutuhkannya
                create: false // Agar user hanya bisa memilih dari opsi yang ada, bukan membuat opsi baru
            });
        });

        document.getElementById('dari').addEventListener('focus', function() {
            this.showPicker(); // Membuka picker tanggal saat input difokuskan
        });
        document.getElementById('sampai').addEventListener('focus', function() {
            this.showPicker(); // Membuka picker tanggal saat input difokuskan
        });

        let typingTimer;
        const doneTypingInterval = 500; // Waktu jeda setelah selesai mengetik (ms)

        document.getElementById("jumlah").addEventListener("input", function() {
            clearTimeout(typingTimer);
            const jumlahInput = this;

            typingTimer = setTimeout(() => {
                const minimal = parseInt(document.getElementById("minimal").value);
                const jumlah = parseInt(jumlahInput.value);
                const errorMessage = document.getElementById("jumlah-error");

                // Pastikan minimal ada nilainya dan jumlah bukan kelipatan dari minimal
                if (minimal && jumlah % minimal !== 0) {
                    errorMessage.style.display = "block";
                    errorMessage.textContent = `Jumlah harus kelipatan dari ${minimal}`;
                    jumlahInput.value = ""; // Mengosongkan input jumlah jika tidak valid
                } else {
                    errorMessage.style.display = "none";
                }
            }, doneTypingInterval);
        });

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
        }
    </script>
@endsection
