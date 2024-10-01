<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('flat-able-lite/dist/assets/css/plugins/prism-coy.css') }}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('flat-able-lite/dist/assets/css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    /* Atur tingkat kegelapan backdrop modal */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.8); /* Semakin tinggi nilai terakhir (0.8), semakin gelap backdrop */
}


    th.asc::after {
        content: " ▲";
    }

    th.desc::after {
        content: " ▼";
    }

    .fixed-badge {
    display: inline-block;
    width: 80px; /* Tentukan ukuran yang sesuai */
    text-align: center;
    padding: 0.5em 0; /* Sesuaikan padding agar badge terlihat rapi */
    }

    .table.table-striped tbody tr:hover {
    background-color: #c3d6e6d3; /* Warna background seluruh baris saat di-hover */
    transition: background-color 0.3s ease; /* Transisi halus */
}

/* Untuk header */
.pcoded-navbar.theme-horizontal {
    display: flex;
    justify-content: center; /* Posisi navbar di tengah */
    height: 56px;
    width: 100%; /* Lebar navbar mengikuti layar */
    z-index: 1023;
    position: fixed;
    top: 56px;
    background-color: #ffffff; /* Warna background sesuai header */
    margin-top: 0;
}

.navbar-wrapper.container {
    display: flex;
    justify-content: center; /* Menyusun konten di tengah */
    max-width: 100%; /* Pastikan lebar maksimal */
    padding: 0 15px;
}

.nav.pcoded-inner-navbar {
    display: flex;
    justify-content: center; /* Menempatkan item navbar di tengah */
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav.pcoded-inner-navbar li.nav-item {
    margin-right: 20px; /* Jarak antara item navbar */
}

.nav.pcoded-inner-navbar li.nav-item:last-child {
    margin-right: 0; /* Menghapus margin kanan dari item terakhir */
}

.nav-item .dropdown-menu {
    background-color: #ffffff; /* Sesuaikan warna dropdown dengan navbar */
    border: none;
    margin-top: 10px; /* Memberi jarak sedikit dari navbar */
}

.nav-item a.nav-link {
    color: #2c3e50; /* Warna teks di navbar */
    padding: 10px 15px;
    text-align: center;
    display: flex;
    align-items: center;
}

.nav-item a.nav-link:hover {
    background-color: #8694a1; /* Warna hover untuk link navbar */
    border-radius: 5px;
}

.nav-item .pcoded-micon {
    margin-right: 8px;
}

.dropdown-menu.profile-notification {
    text-align: left; /* Teks dalam dropdown tetap rata kiri */
    padding: 10px;
}

.nav-item .dropdown-menu ul.pro-body {
    padding: 0;
    margin: 0;
}

.nav-item .dropdown-menu ul.pro-body li {
    list-style: none;
}

.nav-item .dropdown-menu ul.pro-body li a {
    color: #615d5d; /* Warna teks di dropdown */
    padding: 10px 15px;
    display: block;
}

.nav-item .dropdown-menu ul.pro-body li a:hover {
    background-color: #ffffff; /* Warna hover untuk item dropdown */
}

/* Untuk layar yang lebih kecil dari 768px (ukuran umum untuk mobile devices) */
@media (max-width: 768px) {
    .pcoded-navbar {
        display: none; /* Sembunyikan menu di layar kecil */
    }
    .hamburger-menu {
        display: block; /* Tampilkan tombol hamburger */
        cursor: pointer;
        font-size: 24px;
        color: black; /* Ubah warna sesuai keinginan */
    }
    .navbar-wrapper.active {
        display: block; /* Tampilkan menu jika aktif */
        position: absolute;
        top: 56px;
        width: 100%;
        background-color: #000; /* Sesuaikan warna background */
        z-index: 999;
    }

}
</style>

<style>
    /* Mengurangi padding dan mengatur jarak antar baris */
    #jsTable thead th {
        font-weight: bold;   /* Font tebal untuk penekanan */
        text-transform: uppercase; /* (Opsional) Semua huruf kapital */
        padding: 5px;   /* Sedikit padding untuk thead */
        vertical-align: middle;
        line-height: 3;
        font-size: 15px;
    }
    #jsTable tbody td {
        padding: 5px; /* Sesuaikan padding untuk jarak antar sel */
        line-height: 1;  /* Sesuaikan tinggi baris */
        vertical-align: middle;
    }
</style>

    {{-- {{ asset('ElaAdmin-master/assets/css/lib/chosen/chosen.min.css') }}" --}}
