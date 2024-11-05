<div class="col-md-5" style="background-color: rgb(250, 250, 250)">
    <div class="card text-center" style="background-color: rgb(250, 250, 250)">
        <div class="card-body">
            {{-- <h5 class="card-title">{{ $ksr->toko->nama_toko }}</h5> --}}
            <h5 class="card-subtitle">{{ $ksr->toko->nama_toko }}</h5>
            <p class="card-text">{{ $ksr->toko->alamat }}</p>
        </div>
    </div>
    <div class="info-wrapper">
        <div class="info-wrapper">
            <div class="info-row">
                <p class="label">No Nota</p>
                <p class="value">: {{ $ksr->no_nota }}</p>
            </div>
            <div class="info-row">
                <p class="label">Tgl Transaksi</p>
                <p class="value">:
                    {{ $ksr->tgl_transaksi ? \DateTime::createFromFormat('Y-m-d', $ksr->tgl_transaksi)->format('d-m-Y') : '' }}
                </p>
            </div>
            <div class="info-row">
                <p class="label">Member</p>
                <p class="value">: {{ $ksr->member->nama_member }}</p>
            </div>
            <div class="info-row">
                <p class="label">Kasir</p>
                <p class="value">: {{ $ksr->users->nama }}</p>
            </div>
        </div>
    </div>
    <div class="table-responsive-js">
        <table class="table-borderless" id="jsTable-{{ $ksr->id }}">
            <tbody>
                <!-- Filter hanya data detail yang sesuai dengan kasir -->
                @foreach ($detail_kasir->where('id_kasir', $ksr->id) as $dtks)
                    <tr>
                        <td class="narrow-column">{{ $loop->iteration }}.</td>
                        <td class="wide-column">({{ $dtks->barang->nama_barang }})
                            {{ $dtks->qty }}pcs
                            @.{{ number_format($dtks->harga, 0, '.', '.') }}</td>
                        <td class="price-column">
                            {{ number_format($dtks->total_harga, 0, '.', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col" colspan="2" style="text-align:left">Total
                    </th>
                    <th scope="col" class="price-column">
                        {{ number_format($ksr->total_nilai, 0, '.', '.') }}</th>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left">Dibayar</td>
                    <td class="price-column">
                        {{ number_format($ksr->jml_bayar, 0, '.', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left">Kembalian</td>
                    <td class="price-column">
                        {{ number_format($ksr->kembalian, 0, '.', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <p class="card-text" style="text-align: center">Terima Kasih</p>
    <button type="button" class="btn btn-primary btn-sm"
        onclick="cetakStruk({{ $ksr->id }})">Cetak Struk</button>
</div>
