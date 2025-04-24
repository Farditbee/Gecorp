@php
    $nav_link = 'text-primary bg-white';
@endphp

<nav class="pcoded-navbar theme-horizontal menu-light nav-svg">
    <div class="navbar-wrapper">
        <div class="navbar-content sidenav-horizontal" id="layout-sidenav">
            <ul class="nav pcoded-inner-navbar p-1">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigasi</label>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}"
                        class="nav-link {{ request()->routeIs('dashboard.*') ? $nav_link : '' }}">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript::void(0)"
                        class="nav-link {{ request()->routeIs('master.*') ? $nav_link : '' }}"><span
                            class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Data
                            Master</span></a>
                    <ul class="pcoded-submenu">
                        <li class="font-weight-bold">Stakeholder</li>
                        <li><a class="dropdown-item" href="{{ route('master.user.index') }}"><i class="fa fa-users"></i>
                                Data User</a></li>
                        <li><a class="dropdown-item" href="{{ route('master.toko.index') }}"><i class="fa fa-home"></i>
                                Data Toko</a></li>
                        <li><a class="dropdown-item" href="{{ route('master.member.index') }}"><i
                                    class="fa fa-user"></i> Data Member</a></li>
                        @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                            <li><a class="dropdown-item" href="{{ route('master.supplier.index') }}"><i
                                        class="fa fa-download"></i> Data Supplier</a></li>
                        @endif
                        <li class="font-weight-bold mt-2">Barang</li>
                        @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                            <li><a class="dropdown-item" href="{{ route('master.jenisbarang.index') }}"><i
                                        class="fa fa-sitemap"></i> Jenis Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.brand.index') }}"><i
                                        class="fa fa-tag"></i> Data Brand</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.barang.index') }}"><i
                                        class="fa fa-laptop"></i> Data Barang</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('master.stockbarang.index') }}"><i
                                    class="fa fa-tasks"></i> Stock Barang</a></li>
                        @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                            <li><a class="dropdown-item" href="{{ route('master.planorder.index') }}"><i
                                        class="fa fa-laptop"></i> Plan Order - All Toko</a></li>
                        @endif
                        @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                            <li class="font-weight-bold mt-2">Pengaturan</li>
                            <li><a class="dropdown-item" href="{{ route('master.leveluser.index') }}"><i
                                        class="fa fa-laptop"></i> Level User</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.levelharga.index') }}"><i
                                        class="fa fa-sitemap"></i> Level Harga</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.promo.index') }}"><i
                                        class="fa fa-star"></i> Promo</a></li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript::void(0)"
                        class="nav-link {{ request()->routeIs('transaksi.*') ? $nav_link : '' }}"><span
                            class="pcoded-micon"><i class="icon feather icon-shopping-cart"></i></span><span
                            class="pcoded-mtext">Transaksi</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if (
                                Auth::user()->id_level == 1 ||
                                Auth::user()->id_level == 2 ||
                                Auth::user()->id_level == 3 ||
                                Auth::user()->id_level == 4
                            )
                            @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                                <li><a class="dropdown-item" href="{{ route('transaksi.pembelianbarang.index') }}"><i
                                            class="fa fa-shopping-cart"></i> Pembelian Barang</a></li>
                            @endif
                            @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2 || Auth::user()->id_level == 3)
                                <li><a class="dropdown-item" href="{{ route('transaksi.pengirimanbarang.index') }}"><i
                                            class="fa fa-truck"></i> Pengiriman Barang</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('transaksi.kasir.index') }}"><i
                                        class="fa fa-laptop"></i> Transaksi Kasir</a></li>

                            <li><a class="dropdown-item" href="{{ route('kasbon.index') }}"><i
                                        class="fa fa-laptop"></i> Kasbon</a></li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript::void(0)"
                        class="nav-link {{ request()->routeIs('laporan.*') ? $nav_link : '' }}"><span
                            class="pcoded-micon"><i class="icon feather icon-file-text"></i></span><span
                            class="pcoded-mtext">Laporan</span></a>
                    <ul class="pcoded-submenu">
                        @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                            <li><a class="dropdown-item" href="{{ route('laporan.pembelian.index') }}"><i
                                        class="fa fa-book"></i> Laporan Pembelian</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('laporan.pengiriman.index') }}"><i
                                    class="fa fa-book"></i> Laporan Pengiriman</a></li>
                        <li><a class="dropdown-item" href="{{ route('laporan.rating.index') }}"><i
                                    class="fa fa-star"></i> Rating Barang</a></li>
                        <li><a class="dropdown-item" href="{{ route('laporan.asetbarang.index') }}"><i
                                    class="fa fa-box"></i> Asset Barang</a></li>
                        <li><a class="dropdown-item" href="{{ route('laporan.ratingmember.index') }}"><i
                                    class="fa fa-box"></i> Rating Member</a></li>
                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript::void(0)"
                        class="nav-link {{ request()->routeIs('reture.*') ? $nav_link : '' }}"><span
                            class="pcoded-micon"><i class="icon feather icon-rotate-ccw"></i></span><span
                            class="pcoded-mtext">Reture</span></a>
                    <ul class="pcoded-submenu">
                        <li>
                            <a href="{{ route('reture.index') }}" class="dropdown-item"><i
                                    class="icon feather icon-rotate-cw"></i> Reture Member</a>
                        </li>
                        @if (Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                            <li>
                                <a href="{{ route('reture.suplier.index') }}" class="dropdown-item"><i
                                        class="icon feather icon-corner-down-left"></i> Reture Supplier</a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2 || auth()->user()->id_level == 6)
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript::void(0)"
                            class="nav-link {{ request()->routeIs('laporankeuangan.*') ? $nav_link : '' }}"><span
                                class="pcoded-micon"><i class="icon feather icon-folder"></i></span><span
                                class="pcoded-mtext">Laporan Keuangan</span></a>
                        <ul class="pcoded-submenu">
                            <li>
                                <a href="{{ route('laporankeuangan.aruskas.index') }}" class="dropdown-item"><i
                                        class="icon feather icon-file-text"></i> Arus Kas</a>
                            </li>
                            <li>
                                <a href="{{ route('laporankeuangan.labarugi.index') }}" class="dropdown-item"><i
                                        class="icon feather icon-file-minus"></i> Laba Rugi</a>
                            </li>
                            <li>
                                <a href="{{ route('laporankeuangan.neraca.index') }}" class="dropdown-item"><i
                                        class="icon feather icon-book"></i> Neraca</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->id_level != 5)
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript::void(0)"
                            class="nav-link {{ request()->routeIs('keuangan.*') ? $nav_link : '' }}">
                            <span class="pcoded-micon"><i class="icon feather icon-briefcase"></i></span>
                            <span class="pcoded-mtext">Akuntansi Keuangan</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li>
                                <a href="{{ route('keuangan.pemasukan.index') }}" class="dropdown-item">
                                    <i class="icon feather icon-file-plus"></i> Pemasukan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('keuangan.pengeluaran.index') }}" class="dropdown-item">
                                    <i class="icon feather icon-file-minus"></i> Pengeluaran
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('keuangan.piutang.index') }}" class="dropdown-item">
                                    <i class="icon feather icon-file-plus"></i> Piutang
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('keuangan.hutang.index') }}" class="dropdown-item">
                                    <i class="icon feather icon-file-minus"></i> Hutang
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('keuangan.mutasi.index') }}" class="dropdown-item">
                                    <i class="icon feather icon-file-text"></i> Mutasi
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
