@extends('layouts.main')

@section('title')
    Data Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
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
                                @if (Auth::user()->id_level == 1)
                                    <a href="{{ route('master.barang.create') }}" class="mr-2 btn btn-primary">
                                        <i class="fa fa-circle-plus"></i> Tambah
                                    </a>
                                @else
                                    <a href="{{ route('master.barang.create') }}" class="mr-2 btn btn-secondary disabled">
                                        <i class="fa fa-circle-plus"></i> Tambah
                                    </a>
                                @endif
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
                                                <th class="text-wrap align-top">Kode Barcode</th>
                                                <th class="text-wrap align-top">Barcode</th>
                                                <th class="text-wrap align-top">Nama Barang</th>
                                                <th class="text-wrap align-top">Jenis Barang</th>
                                                <th class="text-wrap align-top">Brand Barang</th>
                                                <th class="text-wrap align-top">Garansi</th>
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
@endsection

@section('asset_js')
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Barang';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            let filterParams = {};

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getbarangs') }}', {
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
            let gambar_barcode = data?.barcode_path && data.barcode_path !== "" ?
                `<img src="{{ asset('storage') }}/${data.barcode_path}" width="200" class="barcode-img" alt="Barcode">` :
                `<span class="badge badge-danger">Tidak Ada Gambar</span>`;

            let edit_button = `
                <a href='barang/edit/${data.id}' class="p-1 btn edit-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Edit ${title}: ${data.nama_barang}"
                    data-id='${data.id}'>
                    <span class="text-dark">Edit</span>
                    <div class="icon text-warning">
                        <i class="fa fa-edit"></i>
                    </div>
                </a>`;

            let download_button = data?.barcode_path && data.barcode_path !== "" ?
                `<a href="{{ asset('storage') }}/${data.barcode_path}" download class="p-1 btn download-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Unduh ${title}: ${data.nama_barang}"
                    data-id='${data.id}'>
                    <span class="text-dark">Unduh</span>
                    <div class="icon text-success">
                        <i class="fa fa-download"></i>
                    </div>
                </a>` :
                ``;

            let delete_button = `
                <a class="p-1 btn hapus-data action_button"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus ${title}: ${data.nama_barang}"
                    data-id='${data.id}'
                    data-name='${data.nama_barang}'>
                    <span class="text-dark">Hapus</span>
                    <div class="icon text-danger">
                        <i class="fa fa-trash"></i>
                    </div>
                </a>`;

            return {
                id: data?.id ?? '-',
                nama_barang: data?.nama_barang && data.nama_barang !== "" ? data.nama_barang :
                    '<span class="badge badge-danger">Tidak Ada Data</span>',
                barcode: data?.barcode && data.barcode !== "" ? data.barcode :
                    '<span class="badge badge-danger">Tidak Ada Data</span>',
                nama_jenis_barang: data?.nama_jenis_barang && data.nama_jenis_barang !== "" ? data.nama_jenis_barang :
                    '<span class="badge badge-danger">Tidak Ada Data</span>',
                nama_brand: data?.nama_brand && data.nama_brand !== "" ? data.nama_brand :
                    '<span class="badge badge-danger">Tidak Ada Data</span>',
                garansi: data?.garansi && data.garansi !== "" ? data.garansi :
                    '<span class="badge badge-danger">Tidak Ada Data</span>',
                gambar_barcode,
                edit_button,
                download_button,
                delete_button,
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
                        <td class="${classCol}">${element.barcode}</td>
                        <td class="${classCol}">${element.gambar_barcode}</td>
                        <td class="${classCol}">${element.nama_barang}</td>
                        <td class="${classCol}">${element.nama_jenis_barang}</td>
                        <td class="${classCol}">${element.nama_brand}</td>
                        <td class="${classCol}">${element.garansi}</td>
                        <td class="${classCol}">
                            <div class="d-flex justify-content-center w-100">
                                <div class="hovering p-1">
                                    ${element.edit_button}
                                </div>
                                <div class="hovering p-1">
                                    ${element.download_button}
                                </div>
                                <div class="hovering p-1">
                                    ${element.delete_button}
                                </div>
                            </div>
                        </td>
                    </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            renderPagination();
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                isActionForm = "destroy";
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus ${title} ${name}`,
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
                        `barang/delete/${id}`
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

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await deleteData();
        }
    </script>
@endsection
