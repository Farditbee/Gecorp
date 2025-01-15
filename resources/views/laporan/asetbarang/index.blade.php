@extends('layouts.main')

@section('title')
    Data Barang
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterange-picker.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-wrap align-top">Area/Nama Toko</th>
                                                <th class="text-wrap align-top">Jumlah Item</th>
                                                <th class="text-wrap align-top">Total Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-right">Total</th>
                                                <th id="totalHarga" class="align-center text-dark">0</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/daterange-picker.js') }}"></script>
    <script src="{{ asset('js/daterange-custom.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Aset Barang';
        let customFilter = {};

        async function getListData(customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('dashboard.asset') }}', {
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
            return {
                id: data?.id ?? '-',
                nama_toko: data?.nama_toko ?? '-',
                total_qty: data?.total_qty ?? 0,
                total_harga: data?.total_harga ?? 0,
            };
        }

        async function setListData(dataList, pagination) {
            let totalHarga = 0;

            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let getDataTable = '';
            let classCol = 'align-center text-dark text-wrap';
            dataList.forEach((element, index) => {
                getDataTable += `
                    <tr class="text-dark">
                        <td class="${classCol}">${element.nama_toko}</td>
                        <td class="${classCol}">${element.total_qty}</td>
                        <td class="${classCol}">${element.total_harga}</td>
                    </tr>`;
                totalHarga += parseFloat(element.total_harga);
            });

            $('#listData').html(getDataTable);
            $('#totalHarga').text(formatRupiah(totalHarga));
            $('[data-toggle="tooltip"]').tooltip();
        }

        async function initPageLoad() {
            await getListData(customFilter);
        }
    </script>
@endsection
