<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('flat-able-lite/dist/assets/css/plugins/prism-coy.css') }}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('flat-able-lite/dist/assets/css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
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
