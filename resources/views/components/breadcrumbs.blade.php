<div class="page-header pt-4">
    <div class="page-block">
        <div class="row align-items-center justify-content-between">
            <div class="col-sm-auto d-flex align-items-center">
                <div class="page-header-title d-flex align-items-center">
                    <h5 class="mb-0" style="font-size: 20px">{{ $menu[0] }}</h5>
                    <div id="load-screen" class="loader ml-2">
                        <div class="inner one"></div>
                        <div class="inner two"></div>
                        <div class="inner three"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-auto">
                <ul class="breadcrumb p-0 m-0 font-weight-bold">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard.index') }}"><i class="feather icon-home"></i></a>
                    </li>
                    @if (!empty($menu[1]))
                        <li class="breadcrumb-item text-primary">{{ $menu[1] }}</li>
                    @endif
                    @if (!empty($menu[2]))
                        <li class="breadcrumb-item text-primary">{{ $menu[0] }}</li>
                        <li class="breadcrumb-item" aria-current="page">{{ $menu[2] }}</li>
                    @else
                        <li class="breadcrumb-item" aria-current="page">{{ $menu[0] }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
