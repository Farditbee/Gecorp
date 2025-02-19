<style>
    /* Container utama */
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
        line-height: 1.2;
        margin-bottom: 10px;
    }

    .receipt-info .label {
        font-weight: bold;
        display: inline-block;
        width: 60px;
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
        padding: 2px;
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

    /* Media query untuk cetak */
    @media print {
        body {
            margin: 0;
            padding: 0;
        }

        .receipt-container {
            max-width: none;
            /* Hilangkan batasan lebar */
            width: 80mm;
            /* Lebar sesuai ukuran kertas */
            padding: 5px;
            border: none;
            /* Hilangkan border */
            background-color: #fff;
            /* Pastikan latar belakang tetap putih */
        }

        .receipt-header h1 {
            font-size: 16px;
        }

        .receipt-header p {
            font-size: 10px;
        }

        .receipt-info {
            font-size: 10px;
        }

        .receipt-table th,
        .receipt-table td {
            font-size: 10px;
            padding: 1px;
        }

        .receipt-footer {
            font-size: 9px;
        }

        /* Hilangkan margin dan padding default browser */
        @page {
            size: auto;
            margin: 0;
        }

        .page-break {
            display: none;
            /* Tidak terlihat di layar */
        }

        @media print {
            .page-break {
                display: block;
                page-break-after: always;
                /* Hentikan cetak setelah elemen ini */
            }
        }
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
            $noNotaFormatted =
                substr($kasir->no_nota, 0, 6) . '-' . substr($kasir->no_nota, 6, 6) . '-' . substr($kasir->no_nota, 12);
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dtks->barang->nama_barang }} ({{ $dtks->qty }}pcs)
                        @.{{ number_format($dtks->harga, 0, '.', '.') }}</td>
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
                    {{ number_format($kasir->total_nilai - $kasir->total_diskon, 0, '.', '.') }}</th>
            </tr>
            <tr>
                <td colspan="3" style="text-align:left">Dibayar</td>
                <td class="price">
                    {{ number_format($kasir->jml_bayar, 0, '.', '.') }}</td>
            </tr>
            @if ($kasir->kembalian != 0)
                <tr>
                    <td colspan="3" style="text-align:left">Kembalian</td>
                    <td class="price">
                        {{ number_format($kasir->kembalian, 0, '.', '.') }}</td>
                </tr>
            @endif
            @if ($kasir->kasbon != null)
                <tr>
                    <td colspan="3" style="text-align:left">Sisa Pembayaran
                    </td>
                    <td class="price">
                        {{ number_format($kasir->kasbon->utang, 0, '.', '.') }}
                    </td>
                </tr>
            @endif
        </tfoot>
    </table>

    <div class="receipt-footer">
        <p>Terima Kasih</p>
    </div>
    <div class="page-break"></div>
</div>

<script>
    window.onload = function() {
        const container = document.querySelector('.receipt-container');
        const footer = document.querySelector('.receipt-footer');

        // Pastikan tinggi container sesuai dengan konten
        if (container.scrollHeight > 210) {
            footer.style.pageBreakAfter = 'always';
        }

        // Otomatis mencetak setelah halaman dimuat
        window.print();
    };
</script>
