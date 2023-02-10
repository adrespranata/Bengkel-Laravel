<?php
use Illuminate\Support\Facades\URL;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ $title }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ url('img/konfigurasi/logo/' . $konfigurasi->logo) }}" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{URL::to('/assets/front/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('/assets/front/assets/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('/assets/front/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('/assets/front/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ URL::to('/assets/front/assets/vendor/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('/assets/front/assets/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('/assets/front/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">

    <!-- Template Main CSS File -->
    <link href="{{ URL::to('/assets/front/assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo mr-auto"><a href="">{{ $konfigurasi->nama_web }}</a></h1>
            @yield('navbar')
            
        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex justify-content-center align-items-center">
        <div class="container position-relative" data-aos="zoom-in" data-aos-delay="100">
            <h1>{{ $konfigurasi->nama_web }}</h1>
            <h2>{{ $konfigurasi->deskripsi }}</h2>
        </div>
    </section><!-- End Hero -->

    <main id="main">
       @yield('isi') 
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">

        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-md-6 footer-contact">
                        <h3>{{ $konfigurasi->nama_web }}</h3>
                        <p>
                            {{ $konfigurasi->alamat }}
                            <br>
                            <strong>Phone : </strong>{{ $konfigurasi->whatsapp }}<br>
                            <strong>Email : </strong>{{ $konfigurasi->email }}<br>
                        </p>
                    </div>


                    <div class="col-lg-6 col-md-6 footer-newsletter">
                        <h4>Join News Newsletter</h4>
                        <p>Dapatkan pemberitahuan update bengkel, berlangganan sekarang!</p>
                        <form action="" method="post">
                            <input type="email" name="email"><input type="submit" value="Subscribe">
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="container d-md-flex py-4">

            <div class="mr-md-auto text-center text-md-left">
                <div class="copyright">
                    &copy;<strong><span>{{ $konfigurasi->nama_web }}</span></strong>. All Rights Reserved
                </div>
                <div class="credits">
                    Designed by <a href="">Adres Pranata</a>
                </div>
            </div>
            <div class="social-links text-center text-md-right pt-3 pt-md-0">
                <a href="https://wa.me/{{ $konfigurasi->whatsapp }}" class="whatsapp" target="_blank"><i class="bx bxl-whatsapp"></i></a>
                <a href="{{ $konfigurasi->facebook }}" class="facebook" target="_blank"><i class="bx bxl-facebook"></i></a>
                <a href="{{ $konfigurasi->instagram }}" class="instagram" target="_blank"><i class="bx bxl-instagram"></i></a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top"><i class="bx bx-up-arrow-alt"></i></a>
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ URL::to('/assets/front/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::to('/assets/front/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::to('/assets/front/assets/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ URL::to('/assets/front/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ URL::to('/assets/front/assets/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{ URL::to('/assets/front/assets/vendor/counterup/counterup.min.js') }}"></script>
    <script src="{{ URL::to('/assets/front/assets/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ URL::to('/assets/front/assets/vendor/aos/aos.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ URL::to('/assets/front/assets/js/main.js') }}"></script>
    <script>
        function numberOnly(event) {
            var angka = (event.which) ? event.which : event.keyCode
            if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
                return false;
            return true;
        }
    </script>

</body>

</html>