<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin Dashboard - Library Management')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/assets/images/logo.webp') }}">

    <!-- jsvectormap css -->
    <link href="{{ asset('assets/assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

    <!--Swiper slider css-->
    <link href="{{ asset('assets/assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="{{ asset('assets/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

    <style>
        .nav-link[aria-expanded="true"] .ri-arrow-down-s-line {
            transform: rotate(180deg);
        }

        .nav-link .ri-arrow-down-s-line {
            transition: transform 0.2s;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .search-results {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Sidebar optimizations */
        .app-menu {
            width: 240px !important;
            max-width: 240px !important;
            height: 100vh !important;
            position: fixed;
            overflow: hidden;
        }

        .app-menu .navbar-nav {
            font-size: 0.8rem !important;
        }

        .app-menu .nav-link {
            font-size: 0.9rem !important;
            padding: 0.5rem 1rem !important;
        }

        .app-menu .nav-link i {
            font-size: 0.9rem !important;
            margin-right: 0.5rem !important;
        }

        .app-menu .menu-dropdown .nav-link {
            font-size: 0.75rem !important;
            padding: 0.4rem 1.5rem !important;
        }

        .app-menu .menu-title {
            font-size: 1.9rem !important;
            padding: 0.5rem 1rem !important;
            color: #0a0e19;
        }

        .navbar-brand-box .logo img {
            max-width: 80px !important;
            margin-top: 20px;
            height: auto !important;
            /* existing height */
            border-radius: 50%;
            /* makes it circular */
            object-fit: cover;
            /* ensures image covers the circle */
            border: 2px solid #fff;
            /* optional: adds a border around the circle */
            padding: 2px;
            /* optional: space between image and border */
            display: block;
            /* remove inline spacing issues */
        }


        .main-content {
            margin-left: 240px !important;
        }

        /* Scrollbar styling for sidebar - HIDDEN but scrollable */
        #scrollbar {
            height: calc(100vh - 70px);
            overflow-y: auto;
            padding-bottom: 20px;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        #scrollbar::-webkit-scrollbar {
            display: none;
            width: 0 !important;
        }

        @media (max-width: 991.98px) {
            .app-menu {
                width: 100% !important;
                height: auto !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            #scrollbar {
                height: auto;
                max-height: 70vh;
            }
        }
    </style>
    @stack('styles')
</head>

<body>


    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Topbar Start -->
        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/assets/images/logo.webp') }}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/assets/images/logo.webp') }}" alt="" height="17">
                                </span>
                            </a>

                            <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/assets/images/logo.webp') }}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/assets/images/logo.webp') }}" alt="" height="17">
                                </span>
                            </a>
                        </div>

                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                            id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user"
                                        src="{{ asset('assets/assets/images/users/avatar-1.jpg') }}"
                                        alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span
                                            class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::guard('admin')->user()->name }}</span>
                                        <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">Admin</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Welcome {{ Auth::guard('admin')->user()->name }}!</h6>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="mdi mdi-speedometer text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Dashboard</span>
                                </a>

                                <div class="dropdown-divider"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- Left Sidebar Start -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/assets/images/logo.webp') }}" alt="" height="100px" width="100px">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/assets/images/logo.webp') }}" alt="" height="100px" width="100px">
                    </span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/assets/images/logo.webp') }}" alt="" height="100px" width="100px">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/assets/images/logo.webp') }}" alt="" height="100px" width="100px">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">
                    <div id="two-column-menu"></div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{ route('admin.dashboard') }}">
                                <i class="ri-dashboard-line"></i> <span data-key="t-widgets">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.roles.index') }}">
                                <i class="ri-user-3-line"></i> <span data-key="t-advance-ui">Role Management</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.students.index') }}">
                                <i class="ri-user-3-line"></i> <span data-key="t-advance-ui">Student Management</span>
                            </a>
                        </li>

                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.books.index') }}">
                                <i class="ri-book-2-line"></i> <span data-key="t-advance-ui">Book Management</span>
                            </a>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.vendors.index') }}">
                                <i class="ri-group-line"></i> <span data-key="t-advance-ui">Vendor Management</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.notifications.index') }}">
                                <i class="ri-store-line"></i> <span data-key="t-advance-ui">Push Notification</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.subscription_types.index') }}">
                                <i class="ri-wallet-line"></i> <span data-key="t-advance-ui">Subscriptions Type</span>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.profile.index') }}">
                                <i class="ri-profile-line"></i> <span data-key="t-advance-ui">Profile</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ri-logout-box-line"></i> <span data-key="t-advance-ui">Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->

        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">


                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Crafted with <i class="mdi mdi-heart text-danger"></i> by Wayone It solutions
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/assets/js/plugins.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/admin-delete.js') }}"></script>

    <script>
            < script >
            $(document).ready(function () {
                // Mobile sidebar toggle
                $('#topnav-hamburger-icon').on('click', function () {
                    $('body').toggleClass('sidebar-enable');
                    if ($(window).width() >= 992) {
                        $('body').toggleClass('vertical-collpsed');
                    } else {
                        $('body').removeClass('vertical-collpsed');
                    }
                });

                // Close sidebar on mobile when clicking outside
                $(document).on('click', function (e) {
                    if ($(window).width() < 992) {
                        if (!$(e.target).closest('.app-menu, #topnav-hamburger-icon').length) {
                            $('body').removeClass('sidebar-enable');
                        }
                    }
                });

                // Submenu toggle for mobile
                $('.menu-link[data-bs-toggle="collapse"]').on('click', function (e) {
                    if ($(window).width() < 992) {
                        e.preventDefault();
                        const target = $($(this).attr('href'));
                        target.collapse('toggle');
                    }
                });
            });

        // Notification System variables only
        let lastOrderId = 0;
        let lastSubscriptionId = 0;

        // Removed playSound, showPopup, checkNewData, and speechSynthesis code

        @stack('scripts')
    </>

            @stack('scripts')
</body >

</html >