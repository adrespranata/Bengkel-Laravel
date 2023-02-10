
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ $title }}</title>
    <meta content="Responsive admin theme build on top of Bootstrap 4" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="{{ url('img/konfigurasi/logo/' . $konfigurasi->logo) }}">

    <link href="{{ URL::to('/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('/assets/css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('/assets/css/style.css') }}" rel="stylesheet" type="text/css">

</head>

<body>

    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="home-btn d-none d-sm-block">
        <a href="/" class="text-white"><i class="fas fa-home h2"></i></a>
    </div>
    <div class="wrapper-page">
        <div class="card card-pages shadow-none">
            <div class="card-body">
                <div class="text-center m-t-0 m-b-15">
                    <a href="index.html" class="logo logo-admin"><img src="{{ url('img/konfigurasi/logo/' . $konfigurasi->logo) }}" alt="" height="35"></a>
                </div>
                <h5 class="font-18 text-center">Halaman Login</h5>
                <hr>
                <div id="login_alert"></div>
               <form action="{{ url('login/validasi') }}" method="POST" id="formlogin" class="formlogin">
                    @csrf
                    <div class="form-group">
                        <div class="col-12">
                            <label>Username</label>
                            <input  type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ old('username') }}">
                            <div class="invalid-feedback errorUsername">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                            <div class="invalid-feedback errorPassword">
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center m-t-20">
                        <div class="col-12">
                            <button class="btn btn-primary btn-block btn-lg waves-effect waves-light btnlogin" type="submit">Log In</button>
                        </div>
                    </div>
               </form>
            </div>
        </div>
    </div>
    <!-- END wrapper -->
    <!-- Sweet-Alert  -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- jQuery  -->
    <script src="{{ URL::to('/assets/js/jquery.min.js') }}"></script>
    <script src="{{ URL::to('/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::to('/assets/js/metismenu.min.js') }}"></script>
    <script src="{{ URL::to('/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ URL::to('/assets/js/waves.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::to('/assets/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#formlogin').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.btnlogin').prop('disable', true);
                        $('.btnlogin').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <i>Loading...')

                    },
                    complete: function() {
                        $('.btnlogin').prop('disable', false);
                        $('.btnlogin').html('Login')
                    },
                    success: function(response) {
                        if (response.error) {
                            if (response.error.username) {
                                $('#username').addClass('is-invalid');
                                $('.errorUsername').html(response.error.username);
                            } else {
                                $('#username').removeClass('is-invalid');
                                $('.errorUsername').html();
                            }

                            if (response.error.password) {
                                $('#password').addClass('is-invalid');
                                $('.errorPassword').html(response.error.password);
                            } else {
                                $('#password').removeClass('is-invalid');
                                $('.errorPassword').html();
                            }
                        }

                        if (response.sukses) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Anda berhasil login!",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1250
                            }).then(function() {
                                window.location = response.sukses.link;
                            });

                        }

                        if (response.nonactive) {
                            Swal.fire({
                                title: "Oooopss!",
                                text: "User belum aktif!",
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1250
                            });
                        }
                    }
                });
                return false;
            });
        });
    </script>
</body>
</html>