<title>Dashboard - Gecorp</title>Dashboard - Gecorp
@extends('layouts.main')

@section('content')

<style>
    .pcoded-main-container {
    transform: scale(1.5); /* Ubah nilai sesuai kebutuhan */
    transform-origin: center; /* Pastikan transform dimulai dari sudut kiri atas */
}

/* Jika layar lebih kecil dari 1200px */
@media (max-width: 1200px) {
    .pcoded-main-container {
        transform: scale(1.1); /* Skala dikurangi */
    }
}

/* Jika layar lebih kecil dari 992px (tablet) */
@media (max-width: 992px) {
    .pcoded-main-container {
        transform: scale(1); /* Kembali ke ukuran normal */
    }
}

/* Jika layar lebih kecil dari 768px (smartphone) */
@media (max-width: 768px) {
    .pcoded-main-container {
        transform: scale(0.9); /* Skala lebih kecil untuk layar kecil */
    }
}

/* Jika layar lebih kecil dari 576px (smartphone kecil) */
@media (max-width: 576px) {
    .pcoded-main-container {
        transform: scale(0.8);
    }
}
</style>

<br>
<br>
<br>
<br>
<br>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper container">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="page-header-title">
                                            <h5 class="m-b-10">Dashboard</h5>
                                        </div>
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{ route('master.index')}}"><i class="feather icon-home"></i></a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('master.index')}}">Dashboard</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- [ Main Content ] start -->
                        <div class="row">
                            <!-- table card-1 start -->
                            <div class="col-md-12 col-xl-4">
                                <div class="card flat-card">
                                    <div class="row-table">
                                        <div class="col-sm-6 card-body br">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-eye text-c-green mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>10k</h5>
                                                    <span>Visitors</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-music text-c-red mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>100%</h5>
                                                    <span>Volume</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-table">
                                        <div class="col-sm-6 card-body br">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-file-text text-c-blue mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>2000 +</h5>
                                                    <span>Files</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-mail text-c-yellow mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>120</h5>
                                                    <span>Mails</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- widget primary card start -->
                                <div class="card flat-card widget-primary-card menu-large">
                                    <div class="row-table">
                                        <div class="col-sm-3 card-body">
                                            <i class="feather icon-star-on"></i>
                                        </div>
                                        <div class="col-sm-9">
                                            <h4>4000 +</h4>
                                            <h6>Ratings Received</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- widget primary card end -->
                            </div>
                            <!-- table card-1 end -->
                            <!-- table card-2 start -->
                            <div class="col-md-12 col-xl-4">
                                <div class="card flat-card">
                                    <div class="row-table">
                                        <div class="col-sm-6 card-body br">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-share-2 text-c-blue mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>1000</h5>
                                                    <span>Shares</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-wifi text-c-blue mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>600</h5>
                                                    <span>Network</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-table">
                                        <div class="col-sm-6 card-body br">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-rotate-ccw text-c-blue mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>3550</h5>
                                                    <span>Returns</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-shopping-cart text-c-blue mb-1 d-blockz"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>100%</h5>
                                                    <span>Order</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- widget-success-card start -->
                                <div class="card flat-card widget-purple-card">
                                    <div class="row-table">
                                        <div class="col-sm-3 card-body">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                        <div class="col-sm-9">
                                            <h4>17</h4>
                                            <h6>Achievements</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- widget-success-card end -->
                            </div>
                            <!-- table card-2 end -->
                            <!-- Widget primary-success card start -->
                            <div class="col-md-12 col-xl-4">
                                <div class="card flat-card">
                                    <div class="row-table">
                                        <div class="col-sm-6 card-body br">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-eye text-c-green mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>10k</h5>
                                                    <span>Visitors</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-music text-c-red mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>100%</h5>
                                                    <span>Volume</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-table">
                                        <div class="col-sm-6 card-body br">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-file-text text-c-blue mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>2000 +</h5>
                                                    <span>Files</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <i class="icon feather icon-mail text-c-yellow mb-1 d-block"></i>
                                                </div>
                                                <div class="col-sm-8 text-md-center">
                                                    <h5>120</h5>
                                                    <span>Mails</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- widget primary card start -->
                                <div class="card flat-card widget-primary-card">
                                    <div class="row-table">
                                        <div class="col-sm-3 card-body">
                                            <i class="feather icon-star-on"></i>
                                        </div>
                                        <div class="col-sm-9">
                                            <h4>4000 +</h4>
                                            <h6>Ratings Received</h6>
                                        </div>
                                    </div>
                                </div>
                                <!-- widget primary card end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
