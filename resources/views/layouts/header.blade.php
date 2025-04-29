<header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark">
    <div class="container-fluid">
        <div class="m-header p-2">
            <a class="mobile-menu" id="mobile-collapse" href="#!">
                <span></span>
            </a>
            <a href="#!" class="b-brand">
                <b class="text-white" style="font-size: 30px;">
                    {{ Auth::user()->toko->singkatan }}
                </b>
            </a>
            <a href="" onclick="event.preventDefault(); document.getElementById('logout-m-form').submit();" title="Logout"
                class="btn btn-outline-secondary rounded p-2 text-white d-block d-md-none"
                style="position: absolute; top: 10px; right: 10px; width: 40px; height: 40px; font-size: 18px;">
                <i class="feather icon-log-out"></i>
            </a>
            <form id="logout-m-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav ml-auto">
                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" role="button" tabindex="0">{{ Auth::user()->nama }}
                            <i class="fa fa-chevron-down m-l-5"></i></a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                @if (Auth::check())
                                    <h5 style="color: white">{{ Auth::user()->toko->singkatan }}</h5>
                                    <p style="color: white">{{ Auth::user()->leveluser->nama_level }}</p>
                                @endif
                            </div>
                            <ul class="pro-body">
                                <li><a href="{{ route('master.user.edit', Auth::id()) }}" class="dropdown-item"><i
                                            class="feather icon-user"></i> Profile</a></li>
                                <li>
                                    <a href=""
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="dropdown-item">
                                        <i class="feather icon-log-out"></i> Log Out
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
                <li class="d-block d-lg-none">
                    <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="dropdown-item">
                        <i class="feather icon-log-out"></i> Log Out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
