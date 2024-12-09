<header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark">
    <div class="container-fluid">
        <div class="m-header p-2">
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            <a href="#!" class="b-brand">
                <b class="text-white" style="font-size: 30px">Gecorp</b>
            </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav ml-auto">
                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" role="button" tabindex="0">{{ Auth::user()->nama }} <i class="fa fa-chevron-down m-l-5"></i></a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                @if(Auth::check())
                                    <h5 style="color: white">{{ Auth::user()->toko->nama_toko }}</h5>
                                    <p style="color: white">{{ Auth::user()->leveluser->nama_level }}</p>
                                @endif
                            </div>
                            <ul class="pro-body">
                                <li><a href="{{ route('master.user.edit', Auth::id()) }}" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                                <li>
                                    <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                                        <i class="feather icon-log-out"></i> Log Out
                                    </a>
                                </li>
                            </ul>

                            <!-- Form logout tersembunyi -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

<style>
    /* CSS untuk animasi dropdown */
    .dropdown .dropdown-menu {
        display: none; /* Hanya muncul saat di-hover */
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }
</style>
