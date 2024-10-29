<title>Data Transaksi Kasir - Gecorp</title>
@extends('layouts.main')
@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Tambah</button>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Nota</th>
                                        <th>Tgl Transaksi</th>
                                        <th>Member</th>
                                        <th>Nama Toko</th>
                                        <th>Item</th>
                                        <th>Nilai</th>
                                        <th>Payment</th>
                                        <th>Kasir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data Kasir -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Transaksi -->
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title h4" id="modalLabel">Data Transaksi</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('master.kasir.store') }}" method="post">
                            @csrf
                            <!-- Member Selection -->
                            <div class="form-group">
                                <label for="id_member">Member</label>
                                <select name="id_member" id="id_member" class="form-control">
                                    <option value="" selected>~~ Pilih Member ~~</option>
                                    <option value="Guest">Guest</option>
                                    @foreach ($member as $mbr)
                                        <option value="{{ $mbr->id }}" data-level-info='@json($mbr->level_info)'>{{ $mbr->nama_member }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Barang Selection -->
                            <div class="form-group">
                                <label for="selector">Nama Barang</label>
                                <select name="id_barang" id="selector" class="form-control" disabled>
                                    <option value="">~Silahkan Pilih Barang~</option>
                                    @foreach ($barang as $brg)
                                        <option value="{{ $brg->id_barang }}" data-jenis-barang="{{ $brg->barang->id_jenis_barang }}" data-level-harga='@json($brg->barang->level_harga)'>{{ $brg->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Harga Selection -->
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <select name="harga" id="harga" class="form-control" disabled>
                                    <option value="">~Pilih Harga Dahulu~</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const memberSelect = document.getElementById('id_member');
    const barangSelect = document.getElementById('selector');
    const hargaSelect = document.getElementById('harga');

    // Disabling fields initially
    barangSelect.disabled = true;
    hargaSelect.disabled = true;

    memberSelect.addEventListener('change', function () {
        barangSelect.disabled = !this.value;
        hargaSelect.disabled = true;
        hargaSelect.innerHTML = '<option value="">~Pilih harga Dahulu~</option>';
    });

    barangSelect.addEventListener('change', function () {
        const selectedBarang = this.options[this.selectedIndex];
        const memberId = memberSelect.value;
        const barangId = selectedBarang.value;

        console.log('id_barang', barangId);

        if (memberId && barangId) {
            // Perform AJAX request to get filtered harga
            fetch(`/admin/kasir/get-filtered-harga?id_member=${memberId}&id_barang=${barangId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Filtered Harga options:", data.filteredHarga);

                    // Reset harga options
                    hargaSelect.innerHTML = '<option value="">~Pilih harga Dahulu~</option>';

                    // Check if filteredHarga is an array or a single value
                    if (Array.isArray(data.filteredHarga)) {
                        data.filteredHarga.forEach(harga => {
                            if (harga) {
                                hargaSelect.innerHTML += `<option value="${harga}">${harga}</option>`;
                            }
                        });
                    } else if (data.filteredHarga) {
                        // Single value case
                        hargaSelect.innerHTML += `<option value="${data.filteredHarga}">${data.filteredHarga}</option>`;
                    }

                    // Enable hargaSelect if there are options to show
                    hargaSelect.disabled = !hargaSelect.querySelectorAll('option[value]').length;
                })
                .catch(error => console.error('Error fetching filtered harga:', error));
        }
    });
});
</script>
@endsection