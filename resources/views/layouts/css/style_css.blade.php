    <link rel="icon" href="{{ asset('flat-able-lite/dist/assets/images/favicon.ico') }}" type="image/x-icon">

    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('flat-able-lite/dist/assets/css/plugins/prism-coy.css') }}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('flat-able-lite/dist/assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}

    <style>
        /* Mengurangi padding dan mengatur jarak antar baris */
        #jsTable thead th {
            font-weight: bold;
            /* Font tebal untuk penekanan */
            text-transform: uppercase;
            /* (Opsional) Semua huruf kapital */
            padding: 5px;
            /* Sedikit padding untuk thead */
            vertical-align: middle;
            line-height: 3;
            font-size: 15px;
        }

        #jsTable tbody td {
            padding: 5px;
            /* Sesuaikan padding untuk jarak antar sel */
            line-height: 0.1;
            /* Sesuaikan tinggi baris */
            vertical-align: middle;
            font-size: 14px;
        }

        .table.table-striped tbody tr:hover {
            background-color: #c3d6e6d3;
            /* Warna background seluruh baris saat di-hover */
            transition: background-color 0.3s ease;
            transform: scale(1.01);
            transform-origin: center;
        }

        .info-wrapper {
            max-width: 100%;
            /* Lebar fleksibel untuk kolom */
            margin-bottom: 15px;
            /* Spasi antara informasi dan tabel */
        }

        .info-row {
            display: flex;
            padding: 4px 0;
            /* Spasi antar baris */
        }

        .label {
            width: 150px;
            /* Atur lebar label tetap untuk meratakan titik dua */
            margin: 0;
            font-weight: bold;
            /* Opsional: untuk membedakan label dari nilai */
        }

        .value {
            margin: 0;
            text-align: left;
            /* Pastikan teks rata kiri */
        }

        /* Atur lebar khusus untuk kolom tertentu */
    .table-responsive th:nth-child(2), .table-responsive td:nth-child(2) { /* Nama Barang */
        max-width: 150px;
    }
    .table-responsive th:nth-child(4), .table-responsive td:nth-child(4) { /* Harga */
        max-width: 100px;
    }

    .table-responsive-js table {
        table-layout: fixed;
        width: 100%;
    }
    /* Atur lebar kolom agar otomatis sesuai konten */
    .table-responsive-js th, .table-responsive-js td {
        word-wrap: break-word;
        white-space: normal;
        padding: 5px; /* mengurangi jarak antar kolom */
    }
    .narrow-column {
        width: 7%; /* atau atur ke lebar sesuai keinginan, misalnya 5% atau 50px */
    }
    .wide-column {
        width: 60%; /* Lebih luas untuk kolom Nama Barang */
        white-space: nowrap; /* Menjaga agar konten tetap dalam satu baris, jika memungkinkan */
    }
    .price-column {
        width: auto; /* Biarkan kolom harga mengikuti ukuran kontennya */
        text-align: right; /* Mengatur teks di sisi kanan untuk tampilan harga */
    }
    </style>



    {{-- {{ asset('ElaAdmin-master/assets/css/lib/chosen/chosen.min.css') }}" --}}
