<header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark">
    <div class="container">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            <a href="#!" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ asset('flat-able-lite/dist/assets/images/logo.png') }}" alt="" class="logo">
                <img src="{{ asset('flat-able-lite/dist/assets/images/logo-icon.png') }}" alt="" class="logo-thumb">
            </a>
            <a href="#!" class="mob-toggler">
                <i class="feather icon-more-vertical"></i>
            </a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                
            </ul>
            <ul class="navbar-nav ml-auto">
                
                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Profile
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                {{-- <img src="{{ asset('flat-able-lite/dist/assets/images/user/avatar-1.jpg') }}" class="img-radius" alt="User-Profile-Image"> --}}
                                @if(Auth::check())
                                    <h5 style="color: white">{{ Auth::user()->nama }}</h5>
                                    <span>{{ Auth::user()->leveluser->nama_level }}</span><p style="color: white">{{ Auth::user()->toko->nama_toko }}</p>
                                @endif
                            </div>
                            <ul class="pro-body">
                                <li><a href="user-profile.html" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
                                <li><a href="email_inbox.html" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li>
                                <form action="{{ route ('logout')}}" method="post">
                                <li>
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="feather icon-log-out"></i> Log Out</button>
                                </li>
                                </form>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
