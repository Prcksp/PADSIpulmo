            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    </ul>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('assets/img/avatar.png') }}" class="rounded-circle mr-1">
                            {{-- <div class="d-sm-none d-lg-inline-block">Hai, {{ Auth::user()->name }}</div> --}}
                            <div class="d-sm-none d-lg-inline-block">Hai</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!--<a href="{{ url('/my-account') }}" class="dropdown-item has-icon">-->
                            <!--    <i class="far fa-user"></i> Akun Saya-->
                            <!--</a>-->
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
