<!-- Tombol hamburger -->
<div class="hamburger-menu" onclick="toggleNavbar()">&#9776;</div>

<nav class="pcoded-navbar theme-horizontal menu-light">
    <div class="navbar-wrapper container">
            <ul class="nav pcoded-inner-navbar sidenav-inner">

                <li class="nav-item">
                    <a href="{{ route('master.index')}}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown"><span class="pcoded-micon"><i class="feather icon-box"></i></span>
                        Data Master
                    </a>
                    <div class="dropdown-menu profile-notification ">
                        <ul class="pro-body">
                            <li><a class="dropdown-item" href="{{ route('master.toko.index')}}"><i class="fa fa-home"></i> Data Toko</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.user.index')}}"><i class="fa fa-users"></i> Data User</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.barang.index')}}"><i class="fa fa-laptop"></i> Data Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.brand.index')}}"><i class="fa fa-tag"></i> Data Brand</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.supplier.index')}}"><i class="fa fa-download"></i> Data Supplier</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.promo.index')}}"><i class="fa fa-star"></i> Data Promo</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.member.index')}}"><i class="fa fa-user"></i> Data Member</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.leveluser.index')}}"><i class="fa fa-laptop"></i> Level User</a></li>
                            <li><a class="dropdown-item" href="{{route('master.jenisbarang.index')}}"><i class="fa fa-sitemap"></i> Jenis Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.levelharga.index')}}"><i class="fa fa-sitemap"></i> Level Harga</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.stockbarang.index')}}"><i class="fa fa-tasks"></i> Stock Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.stockopname.index')}}"><i class="fa fa-edit"></i> Stock Opname</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.planorder.index')}}"><i class="fa fa-laptop"></i> Plan Order - All Toko</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown"><span class="pcoded-micon"><i class="menu-icon fa fa-shopping-cart"></i></span>
                        Data Transaksi
                    </a>
                    <div class="dropdown-menu profile-notification ">
                        <ul class="pro-body">
                            <li><a class="dropdown-item" href="{{ route('master.pembelianbarang.index')}}"><i class="fa fa-shopping-cart"></i> Pembelian Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.pengirimanbarang.index')}}"><i class="fa fa-truck"></i> Pengiriman Barang</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item pcoded-menu-caption">
                    <label>Forms &amp; table</label>
                </li>
                <li class="nav-item">
                    <a href="form_elements.html" class="nav-link "><span class="pcoded-micon"><i class="fa fa-truck"></i></span><span class="pcoded-mtext">Mutasi Toko</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown"><span class="pcoded-micon"><i class="menu-icon fa fa-book"></i></span>
                        Laporan
                    </a>
                    <div class="dropdown-menu profile-notification ">
                        <ul class="pro-body">
                            <li><a class="dropdown-item" href="#"><i class="fa fa-tasks"></i> Laporan 1</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown"><span class="pcoded-micon"><i class="menu-icon fa fa-tags"></i></span>
                        Addon
                    </a>
                    <div class="dropdown-menu profile-notification ">
                        <ul class="pro-body">
                            <li><a class="dropdown-item" href="#"><i class="fa fa-sitemap"></i> Addon1</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown"><span class="pcoded-micon"><i class="menu-icon fa fa-book"></i></span>
                        Reture
                    </a>
                    <div class="dropdown-menu profile-notification ">
                        <ul class="pro-body">
                            <li><a class="dropdown-item" href="#"><i class="fa fa-sitemap"></i> Reture 1</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown"><span class="pcoded-micon"><i class="menu-icon fa fa-user"></i></span>
                        Administrator
                    </a>
                    <div class="dropdown-menu profile-notification ">
                        <ul class="pro-body">
                            <li><a class="dropdown-item" href="{{ route('master.pembelianbarang.index')}}"><i class="fa fa-shopping-cart"></i> Pembelian Barang</a></li>
                            <li><a class="dropdown-item" href="{{ route('master.pengirimanbarang.index')}}"><i class="fa fa-truck"></i> Pengiriman Barang</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
    </div>
</nav>

<script>
function toggleNavbar() {
    var navbar = document.querySelector('.navbar-wrapper');
    navbar.classList.toggle('active'); // Menambah/menghapus class 'active'
}
</script>
