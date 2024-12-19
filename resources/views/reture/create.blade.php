@extends('layouts.main')

@section('title')
    Tambah Data Reture
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <a href="{{ url()->previous() }}" class="btn btn-danger">
                                <i class="ti-plus menu-icon"></i> Kembali
                            </a>
                        </div>
                        <x-adminlte-alerts />
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <form action="{{ route('reture.store') }}" method="post" class="" id="retureForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="nama_barang" class=" form-control-label">Scan QrCode Barang<span
                                                    style="color: red">*</span></label>
                                            <div class="input-group mb-6">
                                                <input id="search-data" type="text" class="form-control"
                                                    placeholder="Masukkan / scan Qr Code Barang"
                                                    aria-label="Recipient's username" aria-describedby="basic-addon2"
                                                    required>
                                                <div class="input-group-append">
                                                    <button id="btn-search-data" class="btn btn-primary" type="button"><i
                                                            class="fa fa-magnifying-glass mr-2"></i>Cari</button>
                                                </div>
                                            </div><br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="nama_toko" class="form-control-label">Nama Toko</label>
                                                <input type="text" name="nama_toko" id="nama_toko" class="form-control"
                                                    readonly required>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="id_transaksi" class="form-control-label">ID Transaksi</label>
                                                <input type="text" name="id_transaksi" id="id_transaksi"
                                                    class="form-control" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="tipe_transaksi" class="form-control-label">Tipe
                                                    Transaksi</label>
                                                <input type="text" name="tipe_transaksi" id="tipe_transaksi"
                                                    class="form-control" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="nama_member" class="form-control-label">Nama Member</label>
                                                <input type="text" name="nama_member" id="nama_member"
                                                    class="form-control" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="harga_jual" class="form-control-label">Harga Jual</label>
                                                <input type="text" name="harga_jual" id="harga_jual" class="form-control"
                                                    readonly required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nama_barang" class="form-control-label">Nama Barang</label>
                                                <input type="text" name="nama_barang" id="nama_barang"
                                                    class="form-control" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="qty" class="form-control-label">Qty</label>
                                                <input type="text" name="qty" id="qty" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-dot-circle-o"></i> Simpan
                                        </button>
                                    </div>
                                </form>
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
        let customFilter = {}

        async function getData(customFilter = {}) {
            let filterParams = {};

            if (customFilter['qrcode']) {
                filterParams.qrcode = customFilter['qrcode'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getreture') }}', { 
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status == 200) {
                let data = getDataRest.data.data;
                await setData(data);
            } else {
                let errorMessage = getDataRest?.data?.message || 'Data gagal dimuat';
                console.error(errorMessage);
            }
        }

        async function setData(data) {
            $('#nama_toko').val(data.nama_toko || '');
            $('#id_transaksi').val(data.id_transaksi || '');
            $('#tipe_transaksi').val(data.tipe_transaksi || '');
            $('#nama_member').val(data.nama_member || 'Guest');
            $('#harga_jual').val(data.harga_jual || 0);
            $('#nama_barang').val(data.nama_barang || '');
            $('#qty').val(data.qty_beli || 0);
        }

        async function searchData() {
            $('#btn-search-data').on('click', async () => {
                const qrcodeValue = $('#search-data').val();
                if (qrcodeValue.trim() === "") {
                    notificationAlert('info', 'Pemberitahuan', 'Masukkan QRCode terlebih dahulu.');
                    return;
                }
                customFilter = {
                    qrcode: qrcodeValue
                };
                await getData(customFilter);
            });
        }

        async function initPageLoad() {
            await searchData();
        }
    </script>
@endsection
