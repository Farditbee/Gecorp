<title>Dashboard - Gecorp</title>
@extends('layouts.main')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Dashboard</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('master.index') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a>Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-xxl-6 col-md-3">
                    <div class="row">
                        <div class="col-xxl-12 col-md-12">
                            <div class="card statistics-card-1" style="position: relative;">
                                <img src="{{ asset('images/dash-1.svg') }}" alt="img" class="img-fluid"
                                    style="position: absolute; top: 0; right: 0; width: 125px; height: auto; z-index: 1;">
                                <div class="card-body position-relative">
                                    <div class="d-flex align-items-center">
                                        <div class="avtar bg-brand-color-1 text-white me-3">
                                            <i class="ph-duotone ph-currency-dollar f-26"></i>
                                        </div>
                                        <div>
                                            <p class="font-weight-bold mb-0">Total Pendapatan</p>
                                            <div class="d-flex align-items-end">
                                                <h2 class="mb-0" id="total-pendapatan">
                                                    Rp. 0
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="card table-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Top 5 Penjualan</h5>
                                    <div class="d-flex align-items-center gap-2">
                                        <select id="filter-rating" class="form-select form-select-sm w-auto">
                                            <option value="all">Semua Toko</option>
                                            @foreach ($toko as $tokoData)
                                                <option value="{{ $tokoData->id }}">{{ $tokoData->nama_toko }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="performance-scroll simplebar-scrollable-y"
                                    style="height: 350px; position: relative" data-simplebar="init">
                                    <div class="simplebar-wrapper" style="margin: 0px;">
                                        <div class="simplebar-height-auto-observer-wrapper">
                                            <div class="simplebar-height-auto-observer"></div>
                                        </div>
                                        <div class="simplebar-mask">
                                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                    aria-label="scrollable content"
                                                    style="height: 100%; overflow: hidden scroll;">
                                                    <div class="simplebar-content" style="padding: 0px;">
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover m-b-0 without-header">
                                                                    <tbody id="listData">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="simplebar-placeholder" style="width: 471px; height: 443px;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                        <div class="simplebar-scrollbar"
                                            style="height: 325px; transform: translate3d(0px, 0px, 0px); display: block;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-md-9">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Laporan Penjualan</h5>
                            <div class="d-flex align-items-center gap-2">
                                <select id="filter-toko" class="form-select form-select-sm w-auto">
                                    <option value="all">Semua Toko</option>
                                    @foreach ($toko as $tokoData)
                                        <option value="{{ $tokoData->id }}">{{ $tokoData->nama_toko }}</option>
                                    @endforeach
                                </select>
                                <select id="filter-period" class="form-select form-select-sm w-auto">
                                    <option value="daily">Harian</option>
                                    <option value="monthly" selected>Bulanan</option>
                                    <option value="yearly">Tahunan</option>
                                </select>
                                <select id="filter-month" class="form-select form-select-sm w-auto" style="display: none;">
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <select id="filter-year" class="form-select form-select-sm w-auto"></select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row pb-2 align-items-center">
                                <div class="col-auto m-b-10">
                                    <h3 class="mb-1" id="total-penjualan">Rp. 0</h3>
                                    <span>Data Penjualan</span>
                                </div>
                                <div class="col-auto ms-auto">
                                    <button class="btn btn-outline-primary btn-sm" id="chart-area" title="Area Chart">
                                        <i class="fa fa-chart-area"></i>
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" id="chart-bar" title="Bar Chart">
                                        <i class="fa fa-chart-bar"></i>
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" id="chart-line" title="Line Chart">
                                        <i class="fa fa-chart-line"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="laporan-chart"></div>
                        </div>
                    </div>
                </div>
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
                <!-- Widget primary-success card end -->

                <!-- prject ,team member start -->
                <div class="col-xl-6 col-md-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <h5>Projects</h5>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="feather icon-more-horizontal"></i>
                                    </button>
                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                        <li class="dropdown-item full-card"><a href="#!"><span><i
                                                        class="feather icon-maximize"></i> maximize</span><span
                                                    style="display:none"><i class="feather icon-minimize"></i>
                                                    Restore</span></a></li>
                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i
                                                        class="feather icon-minus"></i> collapse</span><span
                                                    style="display:none"><i class="feather icon-plus"></i>
                                                    expand</span></a></li>
                                        <li class="dropdown-item reload-card"><a href="#!"><i
                                                    class="feather icon-refresh-cw"></i> reload</a></li>
                                        <li class="dropdown-item close-card"><a href="#!"><i
                                                    class="feather icon-trash"></i> remove</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="chk-option">
                                                    <label
                                                        class="check-task custom-control custom-checkbox d-flex justify-content-center done-task">
                                                        <input type="checkbox" class="custom-control-input">
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </div>
                                                Assigned
                                            </th>
                                            <th>Name</th>
                                            <th>Due Date</th>
                                            <th class="text-right">Priority</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="chk-option">
                                                    <label
                                                        class="check-task custom-control custom-checkbox d-flex justify-content-center done-task">
                                                        <input type="checkbox" class="custom-control-input">
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </div>
                                                <div class="d-inline-block align-middle">
                                                    <img src="{{ asset('flat-able-lite/dist/assets/images/user/avatar-4.jpg') }}"
                                                        alt="user image" class="img-radius wid-40 align-top m-r-15">
                                                    <div class="d-inline-block">
                                                        <h6>John Deo</h6>
                                                        <p class="text-muted m-b-0">Graphics Designer</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Able Pro</td>
                                            <td>Jun, 26</td>
                                            <td class="text-right"><label class="badge badge-light-danger">Low</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="chk-option">
                                                    <label
                                                        class="check-task custom-control custom-checkbox d-flex justify-content-center done-task">
                                                        <input type="checkbox" class="custom-control-input">
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </div>
                                                <div class="d-inline-block align-middle">
                                                    <img src="{{ asset('flat-able-lite/dist/assets/images/user/avatar-2.jpg') }}"
                                                        alt="user image" class="img-radius wid-40 align-top m-r-15">
                                                    <div class="d-inline-block">
                                                        <h6>Jenifer Vintage</h6>
                                                        <p class="text-muted m-b-0">Web Designer</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Mashable</td>
                                            <td>March, 31</td>
                                            <td class="text-right"><label class="badge badge-light-primary">high</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="chk-option">
                                                    <label
                                                        class="check-task custom-control custom-checkbox d-flex justify-content-center done-task">
                                                        <input type="checkbox" class="custom-control-input">
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </div>
                                                <div class="d-inline-block align-middle">
                                                    <img src="{{ asset('flat-able-lite/dist/assets/images/user/avatar-3.jpg') }}"
                                                        alt="user image" class="img-radius wid-40 align-top m-r-15">
                                                    <div class="d-inline-block">
                                                        <h6>William Jem</h6>
                                                        <p class="text-muted m-b-0">Developer</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Flatable</td>
                                            <td>Aug, 02</td>
                                            <td class="text-right"><label class="badge badge-light-success">medium</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="chk-option">
                                                    <label
                                                        class="check-task custom-control custom-checkbox d-flex justify-content-center done-task">
                                                        <input type="checkbox" class="custom-control-input">
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </div>
                                                <div class="d-inline-block align-middle">
                                                    <img src="{{ asset('flat-able-lite/dist/assets/images/user/avatar-2.jpg') }}"
                                                        alt="user image" class="img-radius wid-40 align-top m-r-15">
                                                    <div class="d-inline-block">
                                                        <h6>David Jones</h6>
                                                        <p class="text-muted m-b-0">Developer</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Guruable</td>
                                            <td>Sep, 22</td>
                                            <td class="text-right"><label class="badge badge-light-primary">high</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12">
                    <div class="card latest-update-card">
                        <div class="card-header">
                            <h5>Latest Updates</h5>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="feather icon-more-horizontal"></i>
                                    </button>
                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                        <li class="dropdown-item full-card"><a href="#!"><span><i
                                                        class="feather icon-maximize"></i> maximize</span><span
                                                    style="display:none"><i class="feather icon-minimize"></i>
                                                    Restore</span></a></li>
                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i
                                                        class="feather icon-minus"></i> collapse</span><span
                                                    style="display:none"><i class="feather icon-plus"></i>
                                                    expand</span></a></li>
                                        <li class="dropdown-item reload-card"><a href="#!"><i
                                                    class="feather icon-refresh-cw"></i> reload</a></li>
                                        <li class="dropdown-item close-card"><a href="#!"><i
                                                    class="feather icon-trash"></i> remove</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="latest-update-box">
                                <div class="row p-t-30 p-b-30">
                                    <div class="col-auto text-right update-meta">
                                        <p class="text-muted m-b-0 d-inline-flex">2 hrs ago</p>
                                        <i class="feather icon-twitter bg-twitter update-icon"></i>
                                    </div>
                                    <div class="col">
                                        <a href="#!">
                                            <h6>+ 1652 Followers</h6>
                                        </a>
                                        <p class="text-muted m-b-0">You’re getting more and more followers, keep it up!
                                        </p>
                                    </div>
                                </div>
                                <div class="row p-b-30">
                                    <div class="col-auto text-right update-meta">
                                        <p class="text-muted m-b-0 d-inline-flex">4 hrs ago</p>
                                        <i class="feather icon-briefcase bg-c-red update-icon"></i>
                                    </div>
                                    <div class="col">
                                        <a href="#!">
                                            <h6>+ 5 New Products were added!</h6>
                                        </a>
                                        <p class="text-muted m-b-0">Congratulations!</p>
                                    </div>
                                </div>
                                <div class="row p-b-0">
                                    <div class="col-auto text-right update-meta">
                                        <p class="text-muted m-b-0 d-inline-flex">2 day ago</p>
                                        <i class="feather icon-facebook bg-facebook update-icon"></i>
                                    </div>
                                    <div class="col">
                                        <a href="#!">
                                            <h6>+1 Friend Requests</h6>
                                        </a>
                                        <p class="text-muted m-b-10">This is great, keep it up!</p>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tr>
                                                    <td class="b-none">
                                                        <a href="#!" class="align-middle">
                                                            <img src="{{ asset('flat-able-lite/dist/assets/images/user/avatar-2.jpg') }}"
                                                                alt="user image"
                                                                class="img-radius wid-40 align-top m-r-15">
                                                            <div class="d-inline-block">
                                                                <h6>Jeny William</h6>
                                                                <p class="text-muted m-b-0">Graphic Designer</p>
                                                            </div>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="#!" class="b-b-primary text-primary">View all Projects</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- prject ,team member start -->
                <!-- seo start -->
                <div class="col-xl-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h3>$16,756</h3>
                                    <h6 class="text-muted m-b-0">Visits<i class="fa fa-caret-down text-c-red m-l-10"></i>
                                    </h6>
                                </div>
                                <div class="col-6">
                                    <div id="seo-chart1" class="d-flex align-items-end"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h3>49.54%</h3>
                                    <h6 class="text-muted m-b-0">Bounce Rate<i
                                            class="fa fa-caret-up text-c-green m-l-10"></i></h6>
                                </div>
                                <div class="col-6">
                                    <div id="seo-chart2" class="d-flex align-items-end"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h3>1,62,564</h3>
                                    <h6 class="text-muted m-b-0">Products<i
                                            class="fa fa-caret-down text-c-red m-l-10"></i></h6>
                                </div>
                                <div class="col-6">
                                    <div id="seo-chart3" class="d-flex align-items-end"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- seo end -->

                <!-- Latest Customers start -->

                <!-- Latest Customers end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        let customFilter = {};
        let customFilter2 = {};

        async function getTotalPendapatan() {
            let getDataRest = await renderAPI(
                'GET',
                '{{ asset('dummy/pendapatan.json') }}'
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status === 200) {
                let data = getDataRest.data.data;
                await $('#total-pendapatan').html(formatRupiah(data));
            } else {
                console.error(getDataRest?.data?.message || "Error retrieving data.");
            }
        }

        async function getLaporanPenjualan() {
            let filterParams = {};

            if (customFilter['nama_toko']) {
                filterParams.nama_toko = customFilter['nama_toko'];
            }
            if (customFilter['period']) {
                filterParams.period = customFilter['period'];
            }
            if (customFilter['month']) {
                filterParams.month = customFilter['month'];
            }
            if (customFilter['year']) {
                filterParams.year = customFilter['year'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ asset('dummy/laporan.json') }}', {
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status === 200) {
                await setLaporanPenjualan(getDataRest.data.data);
            } else {
                console.error(getDataRest?.data?.message || "Error retrieving data.");
            }
        }

        async function setLaporanPenjualan(data) {
            const filterToko = document.getElementById('filter-toko');
            const filterPeriod = document.getElementById('filter-period');
            const filterMonth = document.getElementById('filter-month');
            const filterYear = document.getElementById('filter-year');
            const total = document.getElementById('total-penjualan');
            const chartContainer = document.getElementById('laporan-chart');
            let currentChartType = 'bar';

            customFilter = {
                nama_toko: filterToko.value,
                period: filterPeriod.value,
                month: filterMonth.value,
                year: filterYear.value,
            };

            const getDaysInMonth = (year, month) => new Date(year, month, 0).getDate();

            const updateChart = (period, year, chartType) => {
                let penjualan = [];
                const month = parseInt(filterMonth.value);

                if (period === 'daily') {
                    const daysInMonth = getDaysInMonth(year, month);
                    penjualan = data.daily?.[year]?.[month] || Array.from({
                        length: daysInMonth
                    }, () => 0);
                } else if (period === 'monthly') {
                    penjualan = data.monthly?.[year] || [];
                } else if (period === 'yearly') {
                    penjualan = data.yearly?.[year] || [];
                }

                total.textContent = formatRupiah(penjualan.reduce((a, b) => a + b, 0));

                const categories = {
                    daily: Array.from({
                        length: penjualan.length
                    }, (_, i) => `Day ${i + 1}`),
                    monthly: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                    yearly: [year],
                };

                const chartOptions = {
                    series: [{
                        name: 'penjualan',
                        data: penjualan,
                    }],
                    chart: {
                        height: 350,
                        type: chartType,
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                            },
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: chartType === 'line' ? 'smooth' : 'straight',
                        width: 3,
                        colors: ['#90EE90'],
                    },
                    xaxis: {
                        categories: categories[period]
                    },
                    colors: ['#90EE90'],
                    legend: {
                        position: 'top'
                    },
                    fill: {
                        type: 'solid',
                        colors: ['#90EE90']
                    },
                    markers: {
                        size: 5,
                        colors: ['#90EE90'],
                        strokeWidth: 2
                    },
                };

                chartContainer.innerHTML = '';
                const chart = new ApexCharts(chartContainer, chartOptions);
                chart.render();
            };

            const populateYearOptions = () => {
                const currentYear = new Date().getFullYear();
                for (let year = currentYear - 5; year <= currentYear; year++) {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    filterYear.appendChild(option);
                }
                filterYear.value = currentYear;
            };

            populateYearOptions();
            updateChart(filterPeriod.value, filterYear.value, currentChartType);

            filterPeriod.addEventListener('change', () => {
                filterMonth.style.display = filterPeriod.value === 'daily' ? 'block' : 'none';
                updateChart(filterPeriod.value, filterYear.value, currentChartType);
            });

            filterMonth.addEventListener('change', () => {
                if (filterPeriod.value === 'daily') {
                    updateChart(filterPeriod.value, filterYear.value, currentChartType);
                }
            });

            filterYear.addEventListener('change', () => {
                updateChart(filterPeriod.value, filterYear.value, currentChartType);
            });

            document.getElementById('chart-area').addEventListener('click', () => {
                currentChartType = 'area';
                updateChart(filterPeriod.value, filterYear.value, currentChartType);
            });

            document.getElementById('chart-bar').addEventListener('click', () => {
                currentChartType = 'bar';
                updateChart(filterPeriod.value, filterYear.value, currentChartType);
            });

            document.getElementById('chart-line').addEventListener('click', () => {
                currentChartType = 'line';
                updateChart(filterPeriod.value, filterYear.value, currentChartType);
            });
        }

        async function getListData(customFilter2 = {}) {
            let filterParams = {};

            if (customFilter2['nama_toko']) {
                filterParams.nama_toko = customFilter2['nama_toko'];
            }

            let getDataRest = await renderAPI(
                'GET',
                '{{ asset('dummy/rating.json') }}', {
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let handleDataArray = await Promise.all(
                    getDataRest.data.data.map(async item => await handleData(item))
                );
                await setListData(handleDataArray, getDataRest.data.pagination);
            } else {
                let errorMessage = getDataRest?.data?.message;
                let errorRow = `
                <tr>
                    <td colspan="${$('.nk-tb-head th').length}"> ${errorMessage} </td>
                </tr>`;
                $('#listData').html(errorRow);
            }
        }

        async function handleData(data) {
            let nama_barang = data?.nama_barang ?? '-';
            let dataJumlah = data?.jumlah ?? '-';

            let fontSize = dataJumlah.toString().length > 3 ?
                '0.50rem' :
                dataJumlah.toString().length > 2 ?
                '0.70rem' :
                '0.80rem';

            let jumlah = `
        <span class="badge-success" style="
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border-radius: 100%;
            line-height: 2rem;
            text-align: center;
            font-size: ${fontSize};
            font-weight: bold;">
            ${dataJumlah}
        </span>
    `;

            let handleData = {
                nama_barang: nama_barang === '' ? '-' : nama_barang,
                jumlah: dataJumlah === '' ? '-' : jumlah,
            };

            return handleData;
        }

        async function setListData(dataList) {
            let getDataTable = '';
            for (let index = 0; index < dataList.length; index++) {
                let element = dataList[index];

                getDataTable += `
                <tr>
                    <td>
                        <div class="d-inline-block">
                            <h5 class="m-b-0 font-weight-bold">${element.nama_barang}</h5>
                            <p class="m-b-0"><i class="fa fa-shopping-cart"></i> <span style="font-size: 1rem;">Terjual :</span> ${element.jumlah}</p>
                        </div>
                    </td>
                </tr>`;
            }
            $('#listData tr').remove();
            $('#listData').html(getDataTable);
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        document.addEventListener('DOMContentLoaded', async function() {
            await getTotalPendapatan();
            await getLaporanPenjualan();
            await getListData();
        });
    </script>
@endsection
