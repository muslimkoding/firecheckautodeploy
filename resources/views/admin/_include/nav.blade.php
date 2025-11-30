<nav class="sb-topnav navbar navbar-expand navbar-white bg-white border-bottom">
    <!-- Navbar Brand-->
    @hasanyrole('superadmin')
        <a class="navbar-brand ps-3" href="{{ route('dashboard.admin') }}">Fire Check SHIAM</a>
    @endhasanyrole

    @hasanyrole('user')
        <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">Fire Check SHIAM</a>
    @endhasanyrole

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" id="sidebar">
            <g fill="none" fill-rule="evenodd" stroke="#000" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" transform="translate(1 1)">
                <rect width="14" height="14" rx="2"></rect>
                <path d="M6 0v18"></path>
            </g>
        </svg>
    </button>

    <!-- Navbar-->
    <div class="felex ms-auto">
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" id="navbarDropdown" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- Foto Profil Kecil di Navbar -->
                    <div class="position-relative">
                        @if (Auth::user()->image)
                            <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="{{ Auth::user()->name }}"
                                class="rounded-circle shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 32px; height: 32px;">
                                <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                            </div>
                        @endif
                    </div>
                </a>

                <!-- Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="min-width: 200px;">
                    <!-- Header Profil di Dropdown -->
                    <li>
                        <div class="d-flex align-items-center px-3 py-2">
                            @if (Auth::user()->image)
                                <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="{{ Auth::user()->name }}"
                                    class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark">{{ explode(' ', Auth::user()->name)[0] }}</div>
                                <small
                                    class="text-muted text-capitalize">{{ Auth::user()->roles->first()->name }}</small>
                            </div>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}">
                            <i class="fas fa-user-circle me-2 text-primary"></i>
                            <span>Profile</span>
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center w-100">
                                <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
