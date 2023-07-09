<!-- ======= Top Bar ======= -->
<section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
            <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:fantasy@flatknitsintl.com">fantasy@flatknitsintl.com</a></i>
            <i class="bi bi-phone d-flex align-items-center ms-4"><span>+88017 1612 8008</span></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
            <a href="https://www.facebook.com/fantasyisland.theater" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="https://www.youtube.com/@flatknitinternational4668" class="youtube"><i class="bi bi-youtube"></i></i></a>
        </div>
    </div>
</section>
<!-- ======= Header ======= -->
<header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
        <h1 class="logo">
            <a href="/">Fantasy Island</a>
            <br>
            <span style="font-size: 14px">Uttara</span>
        </h1>
        <!-- <a href="index.html" class="logo"><img src="assets/img/logo.png" alt=""></a>-->
        <nav id="navbar" class="navbar">
            <ul>
                @if(auth()->user() && auth()->user()->role === 'admin')
                    <li>
                        <a class="nav-link" href="{{route('admin.settings')}}">Settings</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{route('admin.tickets.index')}}">Tickets</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{route('admin.movies.index')}}">
                            Movies
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{route('admin.hall-packages.index')}}">
                            Hall Packages
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{route('admin.reports')}}">
                            Reports
                        </a>
                    </li>
                @else
                    @guest
                        <li><a class="nav-link scrollto active" href="/#hero">Home</a></li>
                        <li><a class="nav-link scrollto" href="/#movieTicket">Movie Ticket</a></li>
                        <li><a class="nav-link scrollto" href="/#about">About</a></li>
                        <li><a class="nav-link scrollto" href="/#contact">Contact</a></li>
                        <li>
                            <a class="nav-link" href="{{ route('verify-tickets') }}">
                                Verify Tickets
                            </a>
                        </li>
                    @endguest
                @endif 
                <li class="dropdown">
                    <a class=" dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @guest
                            Login / Register
                        @else 
                            <i class="fas fa-user"></i> &nbsp; {{auth()->user()->name}}
                        @endguest
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @guest
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                        @else 
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                        @endguest 
                    </ul>
                </li>
                @if(auth()->user() && auth()->user()->role !== 'admin')
                    <li>
                        <div>
                            <a style="
                                width: 150px;
                                display: block;
                                text-align: center;
                                margin: 0 auto;
                            "
                                class="btn btn-info btn-sm" href="{{ route('my-tickets') }}">
                                My  Tickets
                            </a>
                        </div>
                    </li>
                @endif 
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        <!-- .navbar -->
    </div>
</header>
<!-- End Header -->
@auth
    <div class="d-block d-md-none alert alert-success">
        <small>
            Welcome {{auth()->user()->name}}!
        </small>
    </div>
@endauth