<style>/* Container utama */
    .receipt-container {
        max-width: 300px;
        margin: 0 auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: Arial, sans-serif;
        background-color: #fff;
    }

    /* Judul toko */
    .receipt-header {
        text-align: center;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .receipt-header h1 {
        font-size: 18px;
        margin: 5px 0;
    }

    .receipt-header p {
        font-size: 12px;
        color: #555;
    }

    /* Informasi transaksi */
    .receipt-info {
        font-size: 12px;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .receipt-info .label {
        font-weight: bold;
        display: inline-block;
        width: 90px;
    }

    /* Tabel transaksi */
    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .receipt-table th,
    .receipt-table td {
        padding: 5px;
        border-bottom: 1px dotted #ccc;
        text-align: left;
    }

    .receipt-table th {
        font-weight: bold;
    }

    .receipt-table .price {
        text-align: right;
    }

    /* Total dan pembayaran */
    .receipt-summary {
        font-size: 12px;
        font-weight: bold;
        margin-top: 5px;
    }

    .receipt-summary .label {
        float: left;
    }

    .receipt-summary .value {
        float: right;
    }

    /* Pesan penutup */
    .receipt-footer {
        text-align: center;
        font-size: 11px;
        margin-top: 10px;
        color: #777;
    }

    /* Reset float */
    .clearfix::after {
        content: "";
        display: block;
        clear: both;
    }
    </style>

<div class="receipt-container">
    <div class="receipt-header">
        <h1>{{ $kasir->toko->nama_toko }}</h1>
        <p>{{ $kasir->toko->alamat }}</p>
    </div>

    <div class="receipt-info">
        <p><span class="label">No Nota</span> : @php
            // Mendapatkan nilai no_nota dari database
            $noNotaFormatted = substr($kasir->no_nota, 0, 6) . '-' . substr($kasir->no_nota, 6, 6) . '-' . substr($kasir->no_nota, 12);
        @endphp
        {{ $noNotaFormatted }}</p>
        <p><span class="label">Tanggal</span> : {{ $kasir->created_at->format('d-m-Y H:i:s') }}</p>
        <p><span class="label">Member</span> : {{ $kasir->id_member == 0 ? 'Guest' : $kasir->member->nama_member }}</p>
        <p><span class="label">Kasir</span> : {{ $kasir->users->nama }}</p>
    </div>
<hr>
    <table class="receipt-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Barang</th>
                <th class="price">Potongan</th>
                <th class="price">Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail_kasir->where('id_kasir', $kasir->id) as $dtks)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ $dtks->barang->nama_barang }} ({{ $dtks->qty }}pcs) @.{{ number_format($dtks->harga, 0, '.', '.') }}</td>
                <td class="price">-{{ number_format((float) $dtks->diskon, 0, '.', '.') }}</td>
                <td class="price">{{ number_format($dtks->total_harga, 0, '.', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:left">Total Harga</td>
                <td class="price">
                    {{ number_format($kasir->total_nilai, 0, '.', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left">Total Potongan</td>
                <td class="price">
                    {{ number_format($kasir->total_diskon, 0, '.', '.') }}</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <th scope="col" colspan="3" style="text-align:left">Total Bayar</th>
                <th scope="col" class="price">
                    {{ number_format(($kasir->total_nilai - $kasir->total_diskon), 0, '.', '.') }}</th>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left">Dibayar</td>
                <td class="price">
                    {{ number_format($kasir->jml_bayar, 0, '.', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left">Kembalian</td>
                <td class="price">
                    {{ number_format($kasir->kembalian, 0, '.', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="receipt-footer">
        <p>Terima Kasih</p>
    </div>
</div>

