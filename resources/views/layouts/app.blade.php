<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Money Tracker') }} | @yield('title', 'Dashboard')</title>

    <!-- Memuat asset Bootstrap 5.2 melalui Vite -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    
    <!-- Optional: Bootstrap Icons (bi) untuk tampilan icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            padding-top: 56px; /* Tinggi standar navbar Bootstrap */
        }
        
        .sidebar {
            position: fixed;
            top: 56px; 
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding: 0; 
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            overflow-y: auto; 
        }

        @media (min-width: 768px) {
            main.col-md-9 {
                margin-left: 16.666667%; /* 2/12 kolom */
                width: 83.333333%; /* 10/12 kolom */
            }
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.5rem 1rem;
        }
        .sidebar .nav-link.active {
            color: #0d6efd; /* Warna primary Bootstrap */
            background-color: #f8f9fa;
        }
        .sidebar-heading {
            padding: 0 1rem;
        }
        main {
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler me-3" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name', 'Money Tracker') }}</a>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    
                <li class="nav-item dropdown me-3">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe me-1"></i> {{ strtoupper(app()->getLocale()) }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('language.switch', 'id') }}">Bahasa Indonesia</a></li>
                        <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">English</a></li>
                    </ul>
                </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer me-2"></i> {{ __('Dashboard') }}
                            </a>
                        </li>
                        
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                            <span>{{ __('app.Manajemen Data') }}</span>
                        </h6>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('transactions.*')) active @endif" href="{{ route('transactions.index') }}">
                                <i class="bi bi-cash-stack me-2"></i> {{ __('app.Transaksi') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('budgets.*')) active @endif" href="{{ route('budgets.index') }}">
                                <i class="bi bi-pie-chart-fill me-2"></i> {{ __('app.Anggaran') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('categories.*')) active @endif" href="{{ route('categories.index') }}">
                                <i class="bi bi-tags-fill me-2"></i> {{ __('app.Kategori') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('goals.*')) active @endif" href="{{ route('goals.index') }}">
                                <i class="bi bi-bullseye me-2"></i> {{ __('app.Tujuan Keuangan') }}
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ __('Terdapat Kesalahan Input!') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content') 
                
            </main>
        </div>
    </div>
</body>
</html>
