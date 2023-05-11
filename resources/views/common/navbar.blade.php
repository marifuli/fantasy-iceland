<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <!-- Container wrapper -->
    <div class="container">
        <!-- Toggle button -->
        <button
            class="navbar-toggler"
            type="button"
            data-mdb-toggle="collapse"
            data-mdb-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
            >
            <i class="fas fa-bars"></i>
        </button>
        <!-- Collapsible wrapper -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="/">
                Fantasy
            </a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(auth()->user() && auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.tickets.index')}}">Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.movies.index')}}">
                            Movies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.hall-packages.index')}}">
                            Hall Packages
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/">Entry tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            Movies
                        </a>
                    </li>
                @endif 
            </ul>
        </div>
        <!-- Collapsible wrapper -->
        <!-- Right elements -->
        <div class="d-flex align-items-center">
            <!-- Icon -->
            {{-- <a class="text-reset me-3" href="#">
                <i class="fas fa-shopping-cart"></i>
                <span class="badge rounded-pill badge-notification bg-danger">1</span>
            </a> --}}
            <!-- Notifications -->
            <div class="dropdown">
                <a
                    class="text-reset me-3 dropdown-toggle hidden-arrow"
                    href="#"
                    id="navbarDropdownMenuLink"
                    role="button"
                    data-mdb-toggle="dropdown"
                    aria-expanded="false"
                    >
                    <i class="fas fa-user"></i>
                </a>
                <ul
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="navbarDropdownMenuLink"
                    >
                    @guest
                        <li>
                            <a class="dropdown-item" href="{{ route('login') }}">
                                Login 
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('register') }}">
                                Register 
                            </a>
                        </li>
                    @else 
                        <li>
                            <a class="dropdown-item" href="#">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                Logout  
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
        <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
</nav>
<!-- Navbar -->