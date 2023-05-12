<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title', "Fantasy Island")
    </title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
    
    <!-- Favicons -->
    <link href="/assetsv2/img/fantasy-island-logo.png" rel="icon">
    <link href="/assetsv2/img/fantasy-island-logo.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assetsv2/vendor/aos/aos.css" rel="stylesheet">
    <link href="/assetsv2/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assetsv2/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assetsv2/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assetsv2/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="/assetsv2/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/assetsv2/css/style.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('assets/scss/app.min.css') }}">


    <script>
        function contentLoaded (call) {
            document.addEventListener('DOMContentLoaded', ()=> call())
        }
    </script>
</head>
<body>
    @include('common.navbar')
    <div>
        {{-- <div class="container"> --}}
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif    
            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif    
        {{-- </div>      --}}
  
        @yield('content')
    </div>
    
    @include('common.footer')
    
    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


    <!-- Vendor JS Files -->
    <script src="/assetsv2/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="/assetsv2/vendor/aos/aos.js"></script>
    <script src="/assetsv2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assetsv2/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="/assetsv2/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="/assetsv2/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/assetsv2/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="/assetsv2/vendor/php-email-form/validate.js"></script>
    <script src="/assetsv2/js/main.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
</body>
</html>