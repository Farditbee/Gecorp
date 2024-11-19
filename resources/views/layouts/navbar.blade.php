<nav class="pcoded-navbar theme-horizontal menu-light">
        <div class="navbar-wrapper container">
            <div class="navbar-content sidenav-horizontal" id="layout-sidenav">
                <ul class="nav pcoded-inner-navbar sidenav-inner">
                    <li class="nav-item pcoded-menu-caption">
                    	<label>Navigasi</label>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.index')}}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Data Master</span></a>
                        <ul class="pcoded-submenu">
                            <li><a class="dropdown-item" href="{{ route('master.toko.index')}}"><i class="fa fa-home"></i> Data Toko</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.user.index')}}"><i class="fa fa-users"></i> Data User</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.stockbarang.index')}}"><i class="fa fa-tasks"></i> Stock Barang</a></li>
                            @if (Auth::user()->id_level == 1)
                            <li><a class="dropdown-item" href="{{ route('master.barang.index')}}"><i class="fa fa-laptop"></i> Data Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.brand.index')}}"><i class="fa fa-tag"></i> Data Brand</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.supplier.index')}}"><i class="fa fa-download"></i> Data Supplier</a></li>
                            <li><a class="dropdown-item" href="{{route('master.jenisbarang.index')}}"><i class="fa fa-sitemap"></i> Jenis Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.leveluser.index')}}"><i class="fa fa-laptop"></i> Level User</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.levelharga.index')}}"><i class="fa fa-sitemap"></i> Level Harga</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('master.promo.index')}}"><i class="fa fa-star"></i> Data Promo</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.member.index')}}"><i class="fa fa-user"></i> Data Member</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.stockopname.index')}}"><i class="fa fa-edit"></i> Stock Opname</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.planorder.index')}}"><i class="fa fa-laptop"></i> Plan Order - All Toko</a></li>
                        </ul>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="icon feather icon-shopping-cart"></i></span><span class="pcoded-mtext">Data Transaksi</span></a>
                        <ul class="pcoded-submenu">
                            <li><a class="dropdown-item" href="{{ route('master.pembelianbarang.index')}}"><i class="fa fa-shopping-cart"></i> Pembelian Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.pengirimanbarang.index')}}"><i class="fa fa-truck"></i> Pengiriman Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.kasir.index')}}"><i class="fa fa-laptop"></i> Transaksi Kasir</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link "><span class="pcoded-micon"><i class="fa fa-truck"></i></span><span class="pcoded-mtext">Mutasi Toko</span></a>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="icon feather icon-file-text"></i></span><span class="pcoded-mtext">Laporan</span></a>
                        <ul class="pcoded-submenu">
                            <li><a class="dropdown-item" href="{{ route('laporan.pembelian.index')}}"><i class="fa fa-book"></i> Laporan Pembelian</a></li>
                            <li><a class="dropdown-item" href="{{ route('laporan.pengiriman.index')}}"><i class="fa fa-book"></i> Laporan Pengiriman</a></li>
                            <li><a class="dropdown-item" href="{{ route('laporan.rating.index')}}"><i class="fa fa-star"></i> Rating Barang</a></li>
                        </ul>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-tag"></i></span><span class="pcoded-mtext">Addon</span></a>
                        <ul class="pcoded-submenu">
                            <li><a href="#" class="dropdown-item"><i class="fa fa-tag"></i> Addon 1</a></li>
                        </ul>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="icon feather icon-rotate-ccw"></i></span><span class="pcoded-mtext">Reture</span></a>
                        <ul class="pcoded-submenu">
                            <li><a href="#" class="dropdown-item"><i class="icon feather icon-rotate-cw"></i> Reture 1</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
