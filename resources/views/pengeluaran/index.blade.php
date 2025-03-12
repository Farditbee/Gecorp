@extends('layouts.main')

@section('title')
    Pengeluaran
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
                                <button class="mr-2 btn btn-primary" data-toggle="modal" data-target="#modalTambahData"
                                    title="Tambah Data Pengeluaran">
                                    <i class="fa fa-plus-circle"></i> Tambah
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
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Nama Toko</th>
                                                <th class="text-wrap align-top">Nama Pengeluaran</th>
                                                <th class="text-wrap align-top">Jenis</th>
                                                <th class="text-wrap align-top">Nilai</th>
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

    <div class="modal fade" id="modalTambahData" tabindex="-1" role="dialog" aria-labelledby="modalTambahDataLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDataLabel">Tambah Data Pengeluaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTambahData">
                        <div class="form-group">
                            <label for="nama_pengeluaran">Nama Pengeluaran <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="nama_pengeluaran" name="nama_pengeluaran"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="jenis">Jenis Pengeluaran <sup class="text-dark">**</sup></label>
                            <select class="form-control" id="jenis" name="jenis">
                                @foreach ($jenis_pengeluaran as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jenis_baru">Jenis Pengeluaran Baru <sup class="text-dark">**</sup></label>
                            <input type="text" class="form-control" id="jenis_baru" name="jenis_baru">
                        </div>
                        <div class="form-group">
                            <label for="nilai">Nilai <sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" id="nilai" name="nilai" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
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
        let title = 'Pengeluaran';
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
                '{{ route('master.getpengeluaran') }}', {
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
                await setListData(handleDataArray, getDataRest.data.pagination);
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
            return {
                id: data?.id ?? '-',
                nama_toko: data?.nama_toko ?? '-',
                nama_pengeluaran: data?.nama_pengeluaran ?? '-',
                nama_jenis: data?.nama_jenis ?? '-',
                nilai: data?.nilai ?? '-',
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
                        <td class="${classCol}">${element.nama_toko}</td>
                        <td class="${classCol}">${element.nama_pengeluaran}</td>
                        <td class="${classCol}">${element.nama_jenis}</td>
                        <td class="${classCol}">${element.nilai}</td>
                    </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        function handleInput() {
            const jenisSelect = document.getElementById("jenis");
            const jenisBaruInput = document.getElementById("jenis_baru");

            function toggleInputs() {
                if (jenisSelect.value) {
                    jenisBaruInput.disabled = true;
                    jenisBaruInput.value = "";
                } else {
                    jenisBaruInput.disabled = false;
                }
            }

            function toggleSelect() {
                if (jenisBaruInput.value.trim() !== "") {
                    jenisSelect.disabled = true;
                    jenisSelect.value = "";
                } else {
                    jenisSelect.disabled = false;
                }
            }

            jenisSelect.addEventListener("change", toggleInputs);
            jenisBaruInput.addEventListener("input", toggleSelect);
        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await selectList('jenis');
            await handleInput();
        }
    </script>
@endsection
