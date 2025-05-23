@extends('layouts.main')

@section('title')
    Promo
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
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-xl-2 col-lg-2 mb-2">
                                    <button class="btn btn-primary mb-2 mb-lg-0 text-white add-data w-100"
                                        data-container="body" data-toggle="tooltip" data-placement="top"
                                        title="Tambah Promo">
                                        <i class="fa fa-plus-circle"></i> Tambah
                                    </button>
                                </div>
                                <div class="col-12 col-xl-10 col-lg-10 mb-2">
                                    <div class="row justify-content-end">
                                        <div class="col-4 col-xl-2 col-lg-2">
                                            <select name="limitPage" id="limitPage" class="form-control mr-2 mb-2 mb-lg-0">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                            </select>
                                        </div>
                                        <div class="col-8 col-xl-4 col-lg-4 justify-content-end">
                                            <input id="tb-search" class="tb-search form-control mb-2 mb-lg-0" type="search"
                                                name="search" placeholder="Cari Data" aria-label="search">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped m-0">
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

    <div id="modal-form" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lgs">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="modal-title"></h5>
                    <button type="button" class="btn-close reset-all close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-xmark"></i></button>
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
                            <span><strong class="fw-bold"></strong> Minimal : Minimal pembelian item agar promo nya
                                aktif</span>
                        </div>
                        <div class="text-bold d-flex align-items-center mb-2">
                            <em class="fa fa-circle mx-1"></em>
                            <span><strong class="fw-bold"></strong> Jumlah : Total barang yang dipromokan, dan barang
                                tersebut harus berkelipatan sebanyak item Minimal</span>
                        </div>
                        <div class="text-bold d-flex align-items-center">
                            <em class="fa fa-circle mx-1"></em>
                            <span><strong class="fw-bold"></strong> Diskon : Barang yang di diskon adalah hanya per
                                item</span>
                        </div>
                    </div>
                </div>
                <form id="form">
                    <div class="modal-body mb">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="toko" class="form-control-label">Nama toko</label>
                                    <select name="toko" id="f_toko" class="form-select select2">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="barang" class="form-control-label">Nama Barang</label>
                                    <select name="barang" id="f_barang" class="form-select select2">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="minimal" class="form-control-label">Minimal</label>
                                    <input class="form-control" type="number" min='1' name="minimal"
                                        id="minimal">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="id_supplier" class="form-control-label">Jumlah</label>
                                    <input class="form-control" type="number" min='0' name="jumlah"
                                        id="jumlah">
                                    <small id="jumlah-error" style="color: red; display: none;">Jumlah harus kelipatan
                                        dari minimal</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="id_supplier" class="form-control-label">Diskon <i
                                            class="fa fa-percent"></i></label>
                                    <input class="form-control" type="number" min='0' max='100'
                                        name="diskon" id="diskon">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="id_supplier" class="form-control-label">Dari</label>
                                    <input class="form-control" type="datetime-local" name="dari" id="dari">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="id_supplier" class="form-control-label">Sampai</label>
                                    <input class="form-control" type="datetime-local" name="sampai" id="sampai">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" style="float: right" id="save-btn" class="btn btn-primary mr-3 mb-3">
                        <span id="save-btn-text"><i class="fa fa-save"></i> Simpan</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('asset_js')
    <script src="{{ asset('js/pagination.js') }}"></script>
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
        let isActionForm = "store";

        let selectOptions = [{
            id: '#f_toko',
            isFilter: {
                id_toko: '{{ auth()->user()->id_toko }}',
                is_admin: true,
            },
            isUrl: '{{ route('master.toko') }}',
            placeholder: 'Pilih Toko',
            isModal: '#modal-form'
        }, {
            id: '#f_barang',
            isFilter: {
                id_toko: '{{ auth()->user()->id_toko }}',
            },
            isUrl: '{{ route('master.barang') }}',
            placeholder: 'Pilih Barang',
            isModal: '#modal-form'
        }];

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
            let elementData = JSON.stringify(data);
            let status = '';
            let edit_button = '';
            let status_button = '';
            if (data.status == 'Sukses') {
                status =
                    `<span class="badge badge-success"><i class="fa fa-circle-check mr-1"></i>${data.status}</span>`;
                edit_button =
                    `<span class="badge badge-success"><i class="fa fa-circle-check mr-1"></i>Promo Selesai</span>`;
            } else if (data.status == 'On Going') {
                status = `<span class="badge badge-warning"><i class="fa fa-spinner mr-1"></i>${data.status}</span>`;
                edit_button = `
                <button class="p-1 btn edit-data action_button"
                    data-toggle="modal" data-target="#mediumModal-${data.id}"
                    data='${elementData}'
                    data-id='${data.id}'
                    data-name='${data.nama_barang} / ${data.nama_toko}'>
                    <span class="text-dark" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Edit ${title}: ${data.nama_barang}">Edit</span>
                    <div class="icon text-warning" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Edit ${title}: ${data.nama_barang}">
                        <i class="fa fa-edit"></i>
                    </div>
                </button>`;
                status_button = `
                <button class="p-1 btn status-data action_button"
                    data-id='${data.id}'
                    data-name='${data.nama_barang} / ${data.nama_toko}'>
                    <span class="text-dark" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Ubah Status ${title}: ${data.nama_barang}">Selesai</span>
                    <div class="icon text-success" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Selesaikan ${title}: ${data.nama_barang}">
                        <i class="fa fa-circle-check"></i>
                    </div>
                </button>`;
            } else if (data.status == 'Antrean') {
                status =
                    `<span class="badge badge-info"><i class="fa fa-circle-half-stroke mr-1"></i>${data.status}</span>`;
                edit_button = '-';
                status_button = `
                <button class="p-1 btn status-data action_button"
                    data-id='${data.id}'
                    data-name='${data.nama_barang} / ${data.nama_toko}'>
                    <span class="text-dark" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Selesaikan ${title}: ${data.nama_barang}">Selesai</span>
                    <div class="icon text-success" data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Ubah Status ${title}: ${data.nama_barang}">
                        <i class="fa fa-circle-check"></i>
                    </div>
                </button>`;
            } else {
                status =
                    `<span class="badge badge-secondary"><i class="fa fa-circle-check mr-1"></i>Tidak Diketahui</span>`;
                edit_button = '-';
                status_button = ''
            }

            let action_buttons = '';
            if (edit_button || status_button) {
                action_buttons = `
                <div class="d-flex justify-content-center">
                    ${edit_button ? `<div class="hovering p-1">${edit_button}</div>` : ''}
                    ${status_button ? `<div class="hovering p-1">${status_button}</div>` : ''}
                </div>`;
            } else {
                action_buttons = `
                <span class="badge badge-danger">Tidak Ada Aksi</span>`;
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
                action_buttons,
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
                        <td class="${classCol}">${element.action_buttons}</td>
                    </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

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

        async function editData() {
            $(document).on("click", ".edit-data", async function() {
                loadingPage(false);

                let name = $(this).attr("data-name");
                let data = $(this).attr("data");
                let modalTitle = `Form Edit ${title} ${name}`;
                isActionForm = "update";
                let element = JSON.parse(data);
                let id = $(this).attr("data-id");

                $("#modal-title").html(modalTitle);
                $("#modal-form").modal("show");

                $("form").find("input, select, textarea").val("").prop("checked", false).trigger("change");

                if (element.id_toko) {
                    let tokoOption = new Option(element.nama_toko, element.id_toko, true, true);
                    $('#f_toko').append(tokoOption).trigger('change');
                }

                if (element.id_barang) {
                    let barangOption = new Option(element.nama_barang, element.id_barang, true, true);
                    $('#f_barang').append(barangOption).trigger('change');
                }

                $('#minimal').val(element.minimal);
                $('#jumlah').val(element.jumlah);
                $('#diskon').val(element.diskon);
                $('#dari').val(element.dari);
                $('#sampai').val(element.sampai);

                $("#form").data("action-url", '{{ route('master.promo.update') }}');
                $("#form").data("id_user", id);
            });
        }

        async function addData() {
            $(document).on("click", ".add-data", function() {
                $("#modal-title").html(`Form Tambah Promo`);
                $("#modal-form").modal("show");
                isActionForm = "store";
                $("form").find("input, select, textarea").val("").prop("checked", false).trigger("change");
                $("#form").data("action-url", '{{ route('master.promo.store') }}');
            });
        }

        async function submitForm() {
            $(document).off("submit").on("submit", "#form", async function(e) {
                e.preventDefault();
                loadingPage(true);

                let actionUrl = $("#form").data("action-url");
                let formData = {
                    id_toko: $('#f_toko').val(),
                    id_barang: $('#f_barang').val(),
                    minimal: $('#minimal').val(),
                    jumlah: $('#jumlah').val(),
                    diskon: $('#diskon').val(),
                    dari: $('#dari').val(),
                    sampai: $('#sampai').val(),
                };

                let id_user = $("#form").data("id_user");
                if (id_user) {
                    formData.id = id_user;
                }

                if (Object.keys(formData).length === 0) {
                    loadingPage(false);
                    notificationAlert('info', 'Pemberitahuan', 'Tidak ada perubahan untuk diperbarui.');
                    return;
                }

                let method = isActionForm === "store" ? 'POST' : 'PUT';
                try {
                    let postData = await renderAPI(method, actionUrl, formData);

                    loadingPage(false);
                    if (postData.status >= 200 && postData.status < 300) {
                        notificationAlert('success', 'Pemberitahuan', postData.data.message || 'Berhasil');
                        setTimeout(async function() {
                            await getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                        $("#modal-form").modal("hide");
                    } else {
                        notificationAlert('info', 'Pemberitahuan', postData.data.message ||
                            'Terjadi kesalahan');
                    }
                } catch (error) {
                    loadingPage(false);
                    let resp = error.response || {};
                    notificationAlert('error', 'Kesalahan', resp.message || 'Terjadi kesalahan');
                }
            });
        }

        async function updateStatus() {
            $(document).off('click', '.status-data').on('click', '.status-data', async function(event) {
                event.preventDefault();
                swal({
                    title: `Selesaikan ${title}: ${$(this).attr("data-name")}`,
                    text: "Perubahan Status tidak dapat diubah kembali!",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ubah Status!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(async (result) => {

                    let formData = {
                        id: $(this).attr("data-id"),
                    };
                    let postData = await renderAPI('PUT',
                            '{{ route('master.promo.update-status') }}', formData)
                        .then(function(response) {
                            return response;
                        })
                        .catch(function(error) {
                            let resp = error.response;
                            notificationAlert('error', 'Pemberitahuan', resp.data
                                .message);
                            return resp;
                        });
                    if (postData.status === 200) {
                        let rest = postData.data;
                        $('#modal-form').modal('hide');
                        notificationAlert('success', 'Berhasil', rest.message);
                        setTimeout(() => {
                            getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                    }
                }).catch(swal.noop);
            });
        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await selectData(selectOptions);
            await searchList();
            await addData();
            await editData();
            await updateStatus();
            await submitForm();
        }
    </script>
@endsection
