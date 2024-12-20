@extends('layouts.main')

@section('title')
    Edit Data Barang
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
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
                                <form action="{{ route('master.barang.update', $barang->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nama_barang" class="form-control-label">Nama Barang<span
                                                        style="color: red">*</span></label>
                                                <input type="text" id="nama_barang" name="nama_barang"
                                                    value="{{ old('nama_barang', $barang->nama_barang) }}"
                                                    placeholder="Contoh : Bearbarang" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_jenis_barang" class="form-control-label">Jenis Barang</label>
                                                <select name="id_jenis_barang" id="s_jenis_barang" class="form-control"
                                                    tabindex="1">
                                                    <option value="">~Pilih~</option>
                                                    @foreach ($jenis as $jn)
                                                        <option value="{{ $jn->id }}"
                                                            {{ old('id_jenis_barang', $barang->id_jenis_barang) == $jn->id ? 'selected' : '' }}>
                                                            {{ $jn->nama_jenis_barang }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_brand_barang" class="form-control-label">Brand Barang</label>
                                                <select name="id_brand_barang" id="s_brand_barang" class="form-control"
                                                    tabindex="1">
                                                    <option value="">~Pilih~</option>
                                                    @foreach ($brand as $br)
                                                        <option value="{{ $br->id }}"
                                                            {{ old('id_brand_barang', $barang->id_brand_barang) == $br->id ? 'selected' : '' }}>
                                                            {{ $br->nama_brand }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="flexSwitchCheckDefault" class="form-control-label">Garansi</label>
                                                <div class="form-check form-switch">
                                                    <!-- Hidden input untuk nilai "No" jika switch dimatikan -->
                                                    <input type="hidden" name="garansi" value="No">
                                                    <!-- Switch input -->
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        role="switch"
                                                        id="flexSwitchCheckDefault"
                                                        name="garansi"
                                                        value="Yes"
                                                        {{ $barang['garansi'] == 'Yes' ? 'checked' : '' }}
                                                        onchange="updateSwitchStatus(this)">
                                                    <!-- Label status -->
                                                    <span id="switchStatus">{{ $barang['garansi'] == 'Yes' ? 'Ada' : 'Tidak Ada' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions form-group">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Simpan</button>
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
        function updateSwitchStatus(checkbox) {
            const statusSpan = document.getElementById('switchStatus');
            if (checkbox.checked) {
                statusSpan.textContent = 'Ada';
            } else {
                statusSpan.textContent = 'Tidak Ada';
            }
        }

        async function initPageLoad() {
            await selectList(['s_jenis_barang', 's_brand_barang']);
        }
    </script>
@endsection
