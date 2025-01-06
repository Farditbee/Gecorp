    <link rel="stylesheet" href="{{ asset('flat-able-lite/dist/assets/css/plugins/prism-coy.css') }}">
    <link rel="stylesheet" href="{{ asset('flat-able-lite/dist/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <style>
        .form-check-input {
            width: 50px;
            height: 25px;
            position: relative;
            appearance: none;
            background-color: #c33939;
            border-radius: 25px;
            outline: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-check-input::before {
            content: '';
            position: absolute;
            width: 21px;
            height: 21px;
            top: 2px;
            left: 2px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .form-check-input:checked {
            background-color: #47c339;
        }

        .form-check-input:checked::before {
            transform: translateX(25px);
        }

        .form-check-label {
            margin-left: 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>

    <style>
        .b-brand b {
            font-family: 'Orbitron', sans-serif;
            font-size: 30px;
            color: white;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(5, 5, 5, 0.5);
        }

        .dropdown .dropdown-menu {
            display: none;
            /* Hanya muncul saat di-hover */
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

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
            line-height: 1.2;
            /* Sesuaikan tinggi baris agar cukup untuk teks panjang */
            vertical-align: middle;
            font-size: 14px;
            word-wrap: break-word;
            /* Pecah kata panjang */
            word-break: break-word;
            /* Tambahkan dukungan pemecahan kata */
            white-space: normal;
            /* Izinkan teks membuat baris baru */
            overflow-wrap: break-word;
            /* Pecah teks jika terlalu panjang */
            max-width: 150px;
            /* Atur lebar maksimum kolom */
        }

        /* Efek hover untuk baris */
        .table.table-striped tbody tr:hover {
            background-color: #99a8b3d3;
            /* Warna background seluruh baris saat di-hover */
            transition: background-color 0.3s ease;
            transform: scale(1.008);
            transform-origin: center;
        }

        .table-striped thead {
            background-color: #dcf6df;
            color: #1900ff;
            border-bottom: 2px solid #0056b3;
            /* Garis bawah */
        }

        /* Informasi tambahan di luar tabel */
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
        .table-responsive th:nth-child(2),
        .table-responsive td:nth-child(2) {
            /* Nama Barang */
            max-width: 150px;
        }

        .table-responsive th:nth-child(4),
        .table-responsive td:nth-child(4) {
            /* Harga */
            max-width: 100px;
        }

        .table-responsive-js table {
            table-layout: fixed;
            /* Pastikan tabel memiliki lebar tetap */
            width: 100%;
        }

        /* Atur lebar kolom agar otomatis sesuai konten */
        .table-responsive-js th,
        .table-responsive-js td {
            word-wrap: break-word;
            /* Izinkan teks panjang dipotong */
            white-space: normal;
            /* Izinkan teks membuat baris baru */
            padding: 5px;
            /* Mengurangi jarak antar kolom */
            overflow-wrap: break-word;
            /* Tambahan untuk browser modern */
        }

        .narrow-column {
            width: 7%;
            /* atau atur ke lebar sesuai keinginan, misalnya 5% atau 50px */
        }

        .wide-column {
            width: 40%;
            /* Lebih luas untuk kolom Nama Barang */
            white-space: nowrap;
            /* Menjaga agar konten tetap dalam satu baris, jika memungkinkan */
        }

        .price-column {
            width: auto;
            /* Biarkan kolom harga mengikuti ukuran kontennya */
            text-align: right;
            /* Mengatur teks di sisi kanan untuk tampilan harga */
        }
    </style>



    {{-- {{ asset('ElaAdmin-master/assets/css/lib/chosen/chosen.min.css') }}" --}}
