@php
    $nav_link = 'text-primary bg-white'; // Highlight for active menu
@endphp

<nav class="pcoded-navbar theme-horizontal menu-light nav-svg">
    <div class="navbar-wrapper">
        <div class="navbar-content sidenav-horizontal" id="layout-sidenav">
            <ul class="nav pcoded-inner-navbar p-1">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigasi</label>
                </li>

                {{-- Dashboard --}}
                @if (Auth::user()->id_level != 4)
                    <li class="nav-item">
                        <a href="{{ route('dashboard.index') }}"
                            class="nav-link {{ request()->routeIs('dashboard.*') ? $nav_link : '' }}">
                            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                            <span class="pcoded-mtext">Dashboard</span>
                        </a>
                    </li>
                @endif

                {{-- Data Master --}}
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:void(0)" class="nav-link {{ request()->routeIs('master.*') ? $nav_link : '' }}">
                        <span class="pcoded-micon"><i class="feather icon-box"></i></span>
                        <span class="pcoded-mtext">Data Master</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="font-weight-bold">Entitas</li>
                        <li><a class="dropdown-item" href="{{ route('master.user.index') }}"><i class="fa fa-users"></i>
                                Data User</a></li>
                        <li><a class="dropdown-item" href="{{ route('master.toko.index') }}"><i class="fa fa-home"></i>
                                Data Toko</a></li>
                        <li><a class="dropdown-item" href="{{ route('master.member.index') }}"><i
                                    class="fa fa-user"></i> Data Member</a></li>
                        @if (in_array(Auth::user()->id_level, [1, 2, 6]))
                            <li><a class="dropdown-item" href="{{ route('master.supplier.index') }}"><i
                                        class="fa fa-download"></i> Data Supplier</a></li>
                        @endif

                        <li class="font-weight-bold mt-2">Manajemen Barang</li>
                        @if (in_array(Auth::user()->id_level, [1, 2, 6]))
                            <li><a class="dropdown-item" href="{{ route('master.jenisbarang.index') }}"><i
                                        class="fa fa-sitemap"></i> Jenis Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.brand.index') }}"><i
                                        class="fa fa-tag"></i> Data Brand</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.barang.index') }}"><i
                                        class="fa fa-laptop"></i> Data Barang</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('master.stockbarang.index') }}"><i
                                    class="fa fa-tasks"></i> Stok Barang</a></li>

                        @if (in_array(Auth::user()->id_level, [1, 2, 6]))
                            <li class="font-weight-bold mt-2">Pengaturan</li>
                            @if (in_array(Auth::user()->id_level, [1]))
                                <li><a class="dropdown-item" href="{{ route('master.leveluser.index') }}"><i
                                            class="fa fa-laptop"></i> Level User</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('master.levelharga.index') }}"><i
                                        class="fa fa-sitemap"></i> Level Harga</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.promo.index') }}"><i
                                        class="fa fa-star"></i> Promo</a></li>
                        @endif
                    </ul>
                </li>

                {{-- Distribusi --}}
                @if (in_array(Auth::user()->id_level, [1, 2, 3, 5, 6]))
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0)"
                            class="nav-link {{ request()->routeIs('distribusi.*') ? $nav_link : '' }}">
                            <span class="pcoded-micon"><i class="feather icon-package"></i></span>
                            <span class="pcoded-mtext">Distribusi</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li><a class="dropdown-item" href="{{ route('distribusi.pengirimanbarang.index') }}"><i
                                        class="fa fa-truck"></i> Pengiriman Barang</a></li>
                            @if (in_array(Auth::user()->id_level, [1, 2, 6]))
                                <li><a class="dropdown-item" href="{{ route('distribusi.planorder.index') }}"><i
                                            class="fa fa-laptop"></i> Lokasi & Riwayat Barang</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Transaksi --}}
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript:void(0)"
                        class="nav-link {{ request()->routeIs('transaksi.*') ? $nav_link : '' }}">
                        <span class="pcoded-micon"><i class="feather icon-shopping-cart"></i></span>
                        <span class="pcoded-mtext">Transaksi</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if (in_array(Auth::user()->id_level, [1, 2, 6]))
                            <li><a class="dropdown-item" href="{{ route('transaksi.pembelianbarang.index') }}"><i
                                        class="fa fa-shopping-cart"></i> Pembelian Barang</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('transaksi.kasir.index') }}"><i
                                    class="fa fa-cash-register"></i> Transaksi Kasir</a></li>
                        <li><a class="dropdown-item" href="{{ route('transaksi.index') }}"><i
                                    class="fa fa-money-bill"></i> Kasbon Member</a></li>
                    </ul>
                </li>

                {{-- Retur --}}
                @if (in_array(Auth::user()->id_level, [1, 2, 3, 4, 5, 6]))
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0)"
                            class="nav-link {{ request()->routeIs('reture.*') ? $nav_link : '' }}">
                            <span class="pcoded-micon"><i class="feather icon-rotate-ccw"></i></span>
                            <span class="pcoded-mtext">Retur</span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li><a href="{{ route('reture.index') }}" class="dropdown-item"><i
                                        class="feather icon-rotate-cw"></i> Retur dari Member</a></li>
                            @if (in_array(Auth::user()->id_level, [1, 2, 6]))
                                <li><a href="{{ route('reture.suplier.index') }}" class="dropdown-item"><i
                                            class="feather icon-corner-down-left"></i> Retur ke Supplier</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Rekapitulasi --}}
                @if (in_array(Auth::user()->id_level, [1, 2, 3, 5, 6]))
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0)"
                            class="nav-link {{ request()->routeIs('laporan.*') ? $nav_link : '' }}">
                            <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                            <span class="pcoded-mtext">Rekapitulasi</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @if (in_array(Auth::user()->id_level, [1, 2, 6]))
                                <li><a class="dropdown-item" href="{{ route('laporan.pembelian.index') }}"><i
                                            class="fa fa-book"></i> Rekap Pembelian</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('laporan.pengiriman.index') }}"><i
                                        class="fa fa-truck"></i> Rekap Pengiriman</a></li>
                            @if (!in_array(Auth::user()->id_level, [3, 5]))
                                <li><a class="dropdown-item" href="{{ route('laporan.rating.index') }}"><i
                                            class="fa fa-star"></i> Rating Barang</a></li>
                                <li><a class="dropdown-item" href="{{ route('laporan.ratingmember.index') }}"><i
                                            class="fa fa-star"></i> Rating Member</a></li>
                            @endif
                            @if (in_array(Auth::user()->id_level, [1, 6]))
                                <li><a class="dropdown-item" href="{{ route('laporan.asetbarang.index') }}"><i
                                            class="fa fa-box"></i> Aset Barang Jualan</a></li>
                                <li><a class="dropdown-item" href="{{ route('laporan.asetbarang.index') }}"><i
                                            class="fa fa-box"></i> Aset Barang Retur</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Laporan Keuangan --}}
                @if (in_array(Auth::user()->id_level, [1, 6]))
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0)"
                            class="nav-link {{ request()->routeIs('laporankeuangan.*') ? $nav_link : '' }}">
                            <span class="pcoded-micon"><i class="feather icon-folder"></i></span>
                            <span class="pcoded-mtext">Laporan Keuangan</span>
                        </a>
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

                {{-- Jurnal Keuangan --}}
                <li class="nav-item pcoded-hasmenu">
                    <a href="javascript::void(0)"
                        class="nav-link {{ request()->routeIs('keuangan.*') ? $nav_link : '' }}">
                        <span class="pcoded-micon"><i class="icon feather icon-briefcase"></i></span>
                        <span class="pcoded-mtext">Jurnal Keuangan</span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li>
                            <a href="{{ route('keuangan.pemasukan.index') }}" class="dropdown-item">
                                <i class="icon feather icon-file-plus"></i> Pemasukan Lainnya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('keuangan.pengeluaran.index') }}" class="dropdown-item">
                                <i class="icon feather icon-file-minus"></i> Pengeluaran Lainnya
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
                                <i class="icon feather icon-file-text"></i> Mutasi Kas
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
