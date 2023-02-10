<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ $title }}</title>
    <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
    <meta content="Themesdesign" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ url('img/konfigurasi/logo/' . $konfigurasi->logo) }}">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.26/dist/sweetalert2.all.min.js">
    <link href="{{ URL::to('/assets/css/fontawesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('/assets/css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ URL::to('/assets/js/jquery.min.js') }}"></script>
    <script src="{{ URL::to('/assets/select2/dist/js/select2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::to('/assets/select2/dist/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::to('/assets/select2/dist/css/select2-bootstrap4.min.css') }}" />
    <link href="{{ URL::to('/assets/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet" />
    <!-- DataTables -->
    <link href="{{ URL::to('/assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::to('/assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <!-- Responsive datatable examples -->
    <link href="{{ URL::to('/assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Chart -->
    <link href="{{ URL::to('/assets/css/Chart.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('/assets/css/Chart.min.css') }}" rel="stylesheet" type="text/css">


</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
        <div class="topbar">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="/" class="logo">
                    <span class="logo-light">
                        <i class="mdi mdi-death-star-variant"></i> {{ $konfigurasi->nama_web }}
                    </span>
                    <span class="logo-sm">
                        <i class="mdi mdi-death-star-variant"></i>
                    </span>
                </a>
            </div>

            @yield('nav')

        </div>
        <!-- Top Bar End -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="slimscroll-menu" id="remove-scroll">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu" id="side-menu">
                        @yield('menu')
                    </ul>

                </div>
                <!-- Sidebar -->
                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            @yield('judul')
                        </div> <!-- end row -->
                    </div>
                    <!-- end page-title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                   @yield('isi')
                                   @yield('script')
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
                <!-- container-fluid -->

            </div>
            <!-- content -->


            <footer class="footer">
                © 2022 <span class="d-none d-sm-inline-block"> - Crafted with <i class="mdi mdi-heart text-danger"></i></span>
            </footer>

        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->
</body>

</html>