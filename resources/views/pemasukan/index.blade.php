@extends('layouts.main')

@section('title')
    Pemasukan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
    <style>
        #daterange[readonly] {
            background-color: white !important;
            cursor: pointer !important;
            color: inherit !important;
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
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex mb-2 mb-lg-0">
                                <button class="btn btn-primary mb-2 mb-lg-0 text-white add-data mr-1" data-container="body"
                                    data-toggle="tooltip" data-placement="top" title="Tambah Pemasukan">
                                    <i class="fa fa-plus-circle"></i> Tambah
                                </button>
                                <button class="btn-dynamic btn btn-outline-primary ml-1" type="button"
                                    data-toggle="collapse" data-target="#filter-collapse" aria-expanded="false"
                                    aria-controls="filter-collapse">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
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
                            <div class="collapse mt-2" id="filter-collapse">
                                <form id="custom-filter" class="row g-2 align-items-center mx-2">
                                    <div class="col-12 col-md-6 col-lg-2 mb-2">
                                        <input class="form-control" type="text" id="daterange" name="daterange"
                                            placeholder="Pilih rentang tanggal">
                                    </div>
                                    @if (auth()->user()->id_toko == 1)
                                        <div class="col-12 col-md-6 col-lg-2 mb-2">
                                            <select class="form-control select2" id="toko" name="toko"></select>
                                        </div>
                                    @endif
                                    <div class="col-12 col-md-6 col-lg-2 mb-2">
                                        <select class="form-control select2" id="jenis" name="jenis"></select>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-2 mb-2">
                                        <select class="form-select select2" id="f_is_pinjam" name="f_is_pinjam">
                                            <option value="" selected disabled></option>
                                            <option value="1">Pinjaman</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-2 mb-2">
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-2 mb-2 d-flex justify-content-end align-items-start">
                                        <button form="custom-filter" class="btn btn-info mr-2" id="tb-filter"
                                            type="submit">
                                            <i class="fa fa-magnifying-glass mr-2"></i>Cari
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="tb-reset">
                                            <i class="fa fa-rotate mr-2"></i>Reset
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Tanggal</th>
                                                <th class="text-wrap align-top">Nama Toko</th>
                                                <th class="text-wrap align-top">Nama Pemasukan</th>
                                                <th class="text-wrap align-top">Status</th>
                                                <th class="text-wrap align-top">Jenis/Ket</th>
                                                <th class="text-right text-wrap align-top">Nilai</th>
                                                <th class="text-right text-wrap align-top"><span
                                                        class="mr-2">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData">
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
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

    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form-label"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Data Pemasukan</h5>
                    <button type="button" class="btn-close reset-all close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="fa fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahData">
                        <div class="row d-flex align-items-center">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="nama_pemasukan">Nama Pemasukan <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control" id="nama_pemasukan" name="nama_pemasukan"
                                        placeholder="Masukkan nama pemasukan" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal <sup class="text-danger">*</sup></label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                                        placeholder="Masukkan tanggal" required>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex align-items-center">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="nilai">Nilai (Rp) <sup class="text-danger">*</sup></label>
                                    <input type="number" class="form-control" id="nilai" name="nilai"
                                        placeholder="Masukkan nilai" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-switch mx-4 h-100 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="is_pinjam" name="is_pinjam">
                                    <label class="form-check-label ms-2" for="is_pinjam">Pinjaman?</label>
                                </div>
                            </div>
                        </div>
                        <div id="jenisPemasukanContainer">
                            <div class="form-group">
                                <label for="id_jenis_pemasukan">Jenis Pemasukan <sup class="text-danger">**</sup></label>
                                <select class="form-control select2" id="id_jenis_pemasukan" name="id_jenis_pemasukan">
                                </select>
                            </div>
                            <div class="text-center font-weight-bold">Atau</div>
                            <div class="form-group">
                                <label for="nama_jenis">Jenis Pemasukan Baru <sup class="text-danger">**</sup></label>
                                <input type="text" class="form-control" id="nama_jenis" name="nama_jenis"
                                    placeholder="Masukkan jenis baru">
                            </div>
                        </div>
                        <div class="form-group d-none" id="keteranganPinjamanContainer">
                            <label for="ket_pinjam">Keterangan Pinjaman <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="ket_pinjam" name="ket_pinjam"
                                placeholder="Masukkan keterangan pinjaman">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-circle-xmark mr-1"></i>Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan" form="formTambahData"><i
                            class="fa fa-save mr-1"></i>Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Nilai</h5>
                    <button type="button" class="btn-close reset-all close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="fa fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-nilai">Jumlah Bayar <sup>(Rp)</sup> <sup class="text-danger">*</sup></label>
                        <input type="number" class="form-control" id="edit-nilai"
                            placeholder="Masukkan jumlah yang dibayarkan">
                    </div>
                    <div class="card shadow-sm mb-3 border-0">
                        <div class="card-body p-3">
                            <h5 class="card-title text-primary border-bottom pb-2 mb-3">Riwayat Pembayaran</h5>
                            <div class="mt-3">
                                <div id="tableEditData"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-circle-xmark mr-1"></i>Tutup</button>
                    <button type="button" class="btn btn-primary" id="save-edit"><i
                            class="fa fa-save mr-1"></i>Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Nilai</h5>
                    <button type="button" class="btn-close reset-all close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="fa fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <div id="detailDataContainer"></div>
                    <div class="card shadow-sm mb-3 border-0">
                        <div class="card-body p-3">
                            <h5 class="card-title text-primary border-bottom pb-2 mb-3">Riwayat Pembayaran</h5>
                            <div class="mt-3">
                                <div id="tableDetailData"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-circle-xmark mr-1"></i>Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('asset_js')
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Pemasukan';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};
        let selectOptions = [{
                id: '#toko',
                isUrl: '{{ route('master.toko') }}',
                placeholder: 'Pilih Nama Toko',
            }, {
                id: '#jenis',
                isUrl: '{{ route('master.jenismasuk') }}',
                placeholder: 'Pilih Jenis Pemasukan',
            },
            {
                id: '#id_jenis_pemasukan',
                isUrl: '{{ route('master.jenismasuk') }}',
                placeholder: 'Pilih Jenis Pemasukan',
                isModal: '#modal-form'
            }
        ];

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            if (customFilter['startDate'] && customFilter['endDate']) {
                filterParams.startDate = customFilter['startDate'];
                filterParams.endDate = customFilter['endDate'];
            }

            if (customFilter['toko']) {
                filterParams.toko = customFilter['toko'];
            }

            if (customFilter['jenis']) {
                filterParams.jenis = customFilter['jenis'];
            }

            if (customFilter['is_pinjam']) {
                filterParams.is_pinjam = customFilter['is_pinjam'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getpemasukan') }}', {
                    page: page,
                    limit: limit,
                    ascending: ascending,
                    search: search,
                    id_toko: {{ auth()->user()->id_toko }},
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
                await setListData(handleDataArray, getDataRest.data.pagination, getDataRest.data.total_nilai);
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
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
            let elementData = encodeURIComponent(JSON.stringify(data));

            let action_buttons = '';

            let delete_button = `
                <a class="p-1 btn delete-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus ${title}" data="${elementData}">
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash"></i>
                    </div>
                </a>`;

            let detail_button = (data.is_pinjam == 1 || data.is_pinjam == 2) ? `
                <a class="p-1 btn detail-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Detail ${title}" data="${elementData}">
                    <span class="text-dark">Detail</span>
                    <div class="icon text-info">
                        <i class="fa fa-book"></i>
                    </div>
                </a>` : '';

            let edit_button = (data.is_pinjam == 1) ? `
                <a class="p-1 btn edit-data action_button"
                    title="Edit ${title}" data="${elementData}">
                    <span class="text-dark">Edit</span>
                    <div class="icon text-warning">
                        <i class="fa fa-edit"></i>
                    </div>
                </a>` : '';

            if (delete_button || edit_button || detail_button) {
                action_buttons = `
                <div class="d-flex justify-content-end">
                    ${detail_button ? `<div class="hovering p-1">${detail_button}</div>` : ''}
                    ${edit_button ? `<div class="hovering p-1">${edit_button}</div>` : ''}
                    ${delete_button ? `<div class="hovering p-1">${delete_button}</div>` : ''}
                </div>`;
            } else {
                action_buttons = `
                <span class="badge badge-secondary">Tidak Ada Aksi</span>`;
            }

            let pinjaman_badge = (data.is_pinjam == 1) ?
                `<span class="custom-badge badge badge-danger"><i class="fa fa-exclamation-triangle"></i> Hutang In</span>` :
                (data.is_pinjam == 2) ?
                `<span class="custom-badge badge badge-info"><i class="fa fa-info-circle"></i> Hutang Out</span>` :
                (data.id_toko == 1) ?
                `<span class="custom-badge badge badge-info"><i class="fa fa-info-circle"></i> Kas Besar Out</span>` :
                `<span class="custom-badge badge badge-info"><i c lass="fa fa-info-circle"></i> Kas Kecil Out</span>`;

            return {
                id: data?.id ?? '-',
                tanggal: data?.tanggal ?? '-',
                nama_toko: data?.nama_toko ?? '-',
                is_pinjam: pinjaman_badge,
                nama_pemasukan: data?.nama_pemasukan ?? '-',
                nama_jenis: (data?.nama_jenis && data.nama_jenis !== '-') ? data.nama_jenis : (data?.ket_pinjam ?? '-'),
                nilai: data?.nilai ?? '-',
                action_buttons,
            };
        }

        async function setListData(dataList, pagination, total) {
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
                    <td class="${classCol}">${element.tanggal}</td>
                    <td class="${classCol}">${element.nama_toko}</td>
                    <td class="${classCol}">${element.nama_pemasukan}</td>
                    <td class="${classCol}">${element.is_pinjam}</td>
                    <td class="${classCol}">${element.nama_jenis}</td>
                    <td class="${classCol} text-right">${element.nilai}</td>
                    <td class="${classCol}">${element.action_buttons}</td>
                </tr>`;
            });

            let totalRow = `
            <tr class="bg-primary">
                <td class="${classCol}" colspan="5"></td>
                <td class="${classCol}" style="font-size: 1rem;"><strong class="text-white fw-bold">Total</strong></td>
                <td class="${classCol} text-right"><strong class="text-white" id="totalData">${total}</strong></td>
                <td class="${classCol}"></td>
            </tr>`;

            $('#listData').html(getDataTable);
            $('#listData').closest('table').find('tfoot').html(totalRow);

            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        function handleInput() {
            const jenisSelect = $("#id_jenis_pemasukan");
            const jenisBaruInput = document.getElementById("nama_jenis");
            const isPinjamanCheckbox = document.getElementById("is_pinjam");
            const keteranganPinjamanContainer = document.getElementById("keteranganPinjamanContainer");
            const jenisPemasukanContainer = document.getElementById("jenisPemasukanContainer");

            function toggleInputs() {
                if (jenisSelect.val()) {
                    jenisBaruInput.disabled = true;
                    jenisBaruInput.value = "";
                } else {
                    jenisBaruInput.disabled = false;
                }
            }

            function toggleSelect() {
                if (jenisBaruInput.value.trim() !== "") {
                    jenisSelect.prop("disabled", true).val(null).trigger("change");
                } else {
                    jenisSelect.prop("disabled", false);
                }
            }

            function togglePinjamanFields() {
                if (isPinjamanCheckbox.checked) {
                    keteranganPinjamanContainer.classList.remove("d-none");
                    jenisPemasukanContainer.classList.add("d-none");
                } else {
                    keteranganPinjamanContainer.classList.add("d-none");
                    jenisPemasukanContainer.classList.remove("d-none");
                }
            }

            jenisSelect.on("change", toggleInputs);
            jenisBaruInput.addEventListener("input", toggleSelect);
            isPinjamanCheckbox.addEventListener("change", togglePinjamanFields);

            $(document).ready(function() {
                $('#f_is_pinjam').select2({
                    placeholder: "Pilih Status Pinjaman",
                    allowClear: true,
                    minimumResultsForSearch: -1
                });
            });
        }

        async function addData() {
            $(document).on("click", ".add-data", function() {
                $("#modal-title").html(`Form Tambah Pemasukan`);
                $("#modal-form").modal("show");
                $("form").find("input, select, textarea").val("").prop("checked", false).trigger("change");
                $("#formTambahData").data("action-url", '{{ route('master.pemasukan.store') }}');
            });
        }

        async function submitForm() {
            $(document).off("submit").on("submit", "#formTambahData", async function(e) {
                e.preventDefault();
                loadingPage(true);

                let actionUrl = $("#formTambahData").data("action-url");

                let isPinjaman = $("#is_pinjam").is(':checked') ? 1 : '';
                let formData = {
                    id_toko: '{{ auth()->user()->id_toko }}',
                    nama_pemasukan: $('#nama_pemasukan').val(),
                    nilai: $('#nilai').val(),
                    tanggal: $('#tanggal').val(),
                };

                if (isPinjaman) {
                    formData.is_pinjam = 1;
                    formData.ket_pinjam = $('#ket_pinjam').val();
                } else {
                    formData.id_jenis_pemasukan = $('#id_jenis_pemasukan').val();
                    formData.nama_jenis = $('#nama_jenis').val();
                }

                try {
                    let postData = await renderAPI("POST", actionUrl, formData);

                    loadingPage(false);
                    if (postData.status >= 200 && postData.status < 300) {
                        notificationAlert("success", "Pemberitahuan", postData.data.message || "Berhasil");
                        setTimeout(async function() {
                            await getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                        setTimeout(() => {
                            $("#modal-form").modal("hide");
                        }, 500);
                    } else {
                        notificationAlert("info", "Pemberitahuan", postData.data.message ||
                            "Terjadi kesalahan");
                    }
                } catch (error) {
                    loadingPage(false);
                    let resp = error.response || {};
                    notificationAlert("error", "Kesalahan", resp.message || "Terjadi kesalahan");
                }
            });
        }

        async function getDetailData(id, selector) {
            $(selector).html('');

            let getDataRest = await renderAPI(
                'GET',
                `/admin/pemasukan/detail/${id}`, {}
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                return error.response;
            });

            if (getDataRest.status === 200) {
                let data = getDataRest.data.data;
                let tableList = `
                    <div class="table-responsive table-scroll-wrapper">
                        <table class="table table-striped m-0">
                            <thead>
                                <tr class="tb-head">
                                    <th class="text-center text-wrap align-top">No</th>
                                    <th class="text-wrap align-top">Tanggal Bayar</th>
                                    <th class="text-right text-wrap align-top">Nilai</th>
                                </tr>
                            </thead>
                            <tbody id="detailData-${selector}"></tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                `;

                $(`#${selector}`).html(tableList);

                let getDataTable = '';
                let classCol = 'align-center text-dark text-wrap';

                if (data.detail_pembayaran.length > 0) {
                    data.detail_pembayaran.forEach((element, index) => {
                        getDataTable += `
                            <tr class="text-dark">
                                <td class="${classCol} text-center">${index + 1}.</td>
                                <td class="${classCol}">${element.tanggal}</td>
                                <td class="${classCol} text-right">${element.nilai}</td>
                            </tr>`;
                    });
                } else {
                    getDataTable += `
                        <tr class="text-dark">
                            <td class="${classCol} text-center" colspan="3"><i class="fa fa-circle-info mr-1"></i>Belum ada pembayaran</td>
                        </tr>`;
                }

                let totalRow = `
                <tr class="bg-success">
                    <td class="${classCol}" colspan="1"></td>
                    <td class="${classCol}" style="font-size: 1rem;"><strong class="text-white fw-bold">Total Pembayaran</strong></td>
                    <td class="${classCol} text-right"><strong class="text-white" id="totalDetailData">${data.total_pembayaran}</strong></td>
                </tr>
                <tr class="bg-danger">
                    <td class="${classCol}" colspan="1"></td>
                    <td class="${classCol}" style="font-size: 1rem;"><strong class="text-white fw-bold">Sisa Pinjaman</strong></td>
                    <td class="${classCol} text-right"><strong class="text-white" id="sisaDetailData">${data.sisa_pinjaman}</strong></td>
                </tr>`;

                $(`#${selector}`).find(`#detailData-${selector}`).html('');
                $(`#${selector}`).find(`#detailData-${selector}`).append(getDataTable);

                $(`#${selector}`).find('tfoot').html('');
                $(`#${selector}`).find('tfoot').append(totalRow);

                return data;
            } else {
                return;
            }
        }

        async function deleteData() {
            $(document).on("click", ".delete-data", async function() {
                let rawData = $(this).attr("data");
                let data = JSON.parse(decodeURIComponent(rawData));

                swal({
                    title: `Hapus ${title}`,
                    text: "Apakah anda yakin?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batal!",
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    confirmButtonClass: "btn btn-danger",
                    cancelButtonClass: "btn btn-secondary",
                }).then(async (result) => {
                    let postDataRest = await renderAPI(
                        'DELETE',
                        `/admin/pemasukan/delete/${data.id}`, {}
                    ).then(function(response) {
                        return response;
                    }).catch(function(error) {
                        let resp = error.response;
                        return resp;
                    });

                    if (postDataRest.status == 200) {
                        setTimeout(function() {
                            getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                        notificationAlert('success', 'Pemberitahuan', postDataRest.data
                            .message);
                    }
                }).catch(swal.noop);
            })
        }

        async function filterList() {
            let dateRangePickerList = initializeDateRangePicker();

            document.getElementById('custom-filter').addEventListener('submit', async function(e) {
                e.preventDefault();
                let startDate = dateRangePickerList.data('daterangepicker').startDate;
                let endDate = dateRangePickerList.data('daterangepicker').endDate;

                if (!startDate || !endDate) {
                    startDate = null;
                    endDate = null;
                } else {
                    startDate = startDate.startOf('day').format('YYYY-MM-DD HH:mm:ss');
                    endDate = endDate.endOf('day').format('YYYY-MM-DD HH:mm:ss');
                }

                customFilter = {
                    startDate: $("#daterange").val() != '' ? startDate : '',
                    endDate: $("#daterange").val() != '' ? endDate : '',
                    toko: $("#toko").val() || '',
                    jenis: $("#jenis").val() || '',
                    is_pinjam: $("#f_is_pinjam").val() || '',
                };

                defaultSearch = $('.tb-search').val();
                defaultLimitPage = $("#limitPage").val();
                currentPage = 1;

                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });

            document.getElementById('tb-reset').addEventListener('click', async function() {
                $('#custom-filter select').val(null).trigger('change');
                customFilter = {};
                defaultSearch = $('.tb-search').val();
                defaultLimitPage = $("#limitPage").val();
                currentPage = 1;
                await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch,
                    customFilter);
            });
        }

        async function detailData() {
            $(document).off("click", ".detail-data").on("click", ".detail-data", async function() {
                let rawData = $(this).attr("data");
                let data = JSON.parse(decodeURIComponent(rawData));

                $("#detailModalLabel").html(`<i class="fa fa-book mr-2"></i>Detail Data`);
                $("#detailModal").modal("show");

                let dataList = await getDetailData(data.id, 'tableDetailData');
                renderDetailData(dataList.pemasukan);
            });
        }

        async function editData() {
            $(document).off("click", ".edit-data").on("click", ".edit-data", async function() {
                let rawData = $(this).attr("data");
                let data = JSON.parse(decodeURIComponent(rawData));

                $("#editModalLabel").html(
                    `<i class="fa fa-edit mr-2"></i>${data.ket_pinjam ?? '-'}`);
                $("#save-edit").attr("data-id", data.id);
                $("#editModal").modal("show");

                let dataList = await getDetailData(data.id, 'tableEditData');

                let sisaPinjaman = dataList.sisa_pinjaman.replace(/[^\d]/g, "");
                let sisaPinjamanNum = parseInt(sisaPinjaman, 10) || 0;

                $("#edit-nilai").attr({
                    "min": 0,
                    "max": sisaPinjamanNum,
                    "type": "number"
                }).val(sisaPinjamanNum);
            });

            $(document).on("input", "#edit-nilai", function() {
                let maxValue = parseInt($(this).attr("max"), 10);
                let minValue = parseInt($(this).attr("min"), 10);
                let currentValue = parseInt($(this).val(), 10) || 0;

                if (currentValue < minValue) {
                    $(this).val(minValue);
                }

                if (currentValue > maxValue) {
                    $(this).val(maxValue);
                }
            });

            $(document).on("click", "#save-edit", async function() {
                let id = $(this).attr("data-id");
                let newValue = parseInt($("#edit-nilai").val(), 10) || 0;
                let maxValue = parseInt($("#edit-nilai").attr("max"), 10);

                if (newValue < 1 || newValue > maxValue) {
                    notificationAlert("info", "Pemberitahuan", `Nilai harus antara 1 dan ${maxValue}`);
                    return;
                }

                let formData = {
                    nilai: newValue
                };

                try {
                    let postData = await renderAPI("PUT", `/admin/pemasukan/update/${id}`, formData);

                    loadingPage(false);
                    if (postData.status >= 200 && postData.status < 300) {
                        notificationAlert("success", "Pemberitahuan", postData.data.message || "Berhasil");
                        setTimeout(async function() {
                            await getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                        setTimeout(() => {
                            $("#editModal").modal("hide");
                        }, 500);
                    } else {
                        notificationAlert("info", "Pemberitahuan", postData.data.message ||
                            "Terjadi kesalahan");
                    }
                } catch (error) {
                    loadingPage(false);
                    let resp = error.response || {};
                    notificationAlert("error", "Kesalahan", resp.data.message || "Terjadi kesalahan");
                }
            });
        }

        function renderDetailData(data) {
            const html = `
                <div class="card shadow-sm mb-3 border-0">
                    <div class="card-body p-3">
                        <h5 class="card-title text-primary border-bottom pb-2 mb-3">Detail Pinjaman</h5>
                        <div class="d-flex justify-content-between">
                            <strong>Nama Toko:</strong>
                            <span>${data.nama_toko}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>Pinjaman:</strong>
                            <span>${data.nama_pemasukan}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>Nilai:</strong>
                            <span>${data.nilai}</span>
                        </div>
                        ${data.is_pinjam ? `
                                                                            <div class="d-flex justify-content-between">
                                                                                <strong>Keterangan:</strong>
                                                                                <span>${data.ket_pinjam}</span>
                                                                            </div>
                                                                            ` : ''}
                        <div class="d-flex justify-content-between border-top pt-2 mt-3">
                            <strong>Tanggal Pemasukan:</strong>
                            <span>${data.tanggal}</span>
                        </div>
                    </div>
                </div>
            `;

            $("#detailDataContainer").html(html);
        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await setDynamicButton();
            await selectData(selectOptions);
            await searchList();
            await handleInput();
            await filterList();
            await addData();
            await submitForm();
            await deleteData();
            await editData();
            await detailData();
        }
    </script>
@endsection
