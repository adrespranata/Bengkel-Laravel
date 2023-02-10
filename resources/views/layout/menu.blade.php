@section('nav')
<nav class="navbar-custom">
    <ul class="navbar-right list-inline float-right mb-0">

        <!-- full screen -->
        <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
            <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                <i class="mdi mdi-arrow-expand-all noti-icon"></i>
            </a>
        </li>

        <li class="dropdown notification-list list-inline-item">
            <div class="dropdown notification-list nav-pro-img">
                <a class="dropdown-toggle nav-link arrow-none nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ url('img/user/' . session()->get('foto')) }}" alt="user" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                    <!-- item-->
                    <a class="dropdown-item" href="javascript:void(0);"> {{ session()->get('nama') }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" id="logout"><i class="mdi mdi-power text-danger"></i> Logout</a>
                    
                </div>
            </div>
        </li>

    </ul>

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            <button class="button-menu-mobile open-left waves-effect">
                <i class="mdi mdi-menu"></i>
            </button>
        </li>

    </ul>

</nav>
@endSection

@section('menu')
    @if (session()->get('level') == 1)
        <li class="menu-title">Dashboard</li>
        <li>
            <a href="/dashboard" class="waves-effect">
                <i class="icon-accelerator"></i> <span> Dashboard </span>
            </a>
        </li>
        <li class="menu-title">Data</li>
        <li>
            <a href="{{ url('auth/staf') }}" class="waves-effect">
                <i class="mdi mdi-hard-hat"></i> <span> Staf </span>
            </a>
        </li>
        <li>
            <a href="{{ url('auth/pelanggan') }}" class="waves-effect">
                <i class="mdi mdi-account-multiple"></i> <span> Pelanggan </span>
            </a>
        </li>
        <li>
            <a href="{{ url('auth/supplier') }}" class="waves-effect">
                <i class="mdi mdi-truck"></i> <span> Supplier </span>
            </a>
        </li>


        <li class="menu-title">Transaksi</li>
        <li>
            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-archive"></i> <span> Products <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
            <ul class="submenu">
                <li><a href="{{ url('auth/sparepart') }}">Sparepart</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cart"></i> <span>Transaksi <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
            <ul class="submenu">
                <li><a href="{{ url('auth/purchase') }}">Stock-in</a></li>
                <li><a href="{{ url('auth/sale') }}">Stock-out</a></li>
            </ul>
        </li>
        <li class="menu-title">Laporan</li>
        <li>
            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-file-document"></i> <span>Laporan <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span> </a>
            <ul class="submenu">
                <li><a href="{{ url('auth/laporan') }}">Laporan Purchase</a></li>
                <li><a href="{{ url('laporan/laporansale') }}">Laporan Sale</a></li>
            </ul>
        </li>
        <li class="menu-title">Etc</li>
        <li>
            <a href="{{ url('konfigurasi/user') }}" class="waves-effect">
                <i class="mdi mdi-account-switch"></i> <span> Konfigurasi User </span>
            </a>
        </li>
        <li>
            <a href="{{ url('konfigurasi/web') }}" class="waves-effect">
                <i class="mdi mdi-settings-outline"></i> <span> Konfigurasi Web </span>
            </a>
        </li>
    @endif
@endSection