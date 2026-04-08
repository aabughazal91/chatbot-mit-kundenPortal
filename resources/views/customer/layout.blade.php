<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kunden Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar d-flex flex-column">
            <div  class="text-center" >
                <x-application-logo />
            </div>
            <h4 class="text-center mb-4">Kunden Dashboard</h4>
            <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="bi bi-person me-2"></i> Mein Profil
            </a>
            
            <div class="mt-auto mb-3 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light w-75">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-0">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light navbar-custom px-4 py-3">
                <div class="container-fluid pl-0">
                    <span class="navbar-brand mb-0 h1">@yield('header_title', 'Kunden Dashboard')</span>
                    <img src="{{ asset('logo.png') }}" alt="">
                    <div class="d-flex">
                        <span class="navbar-text">
                            Eingeloggt als: <strong>{{ auth()->user()->name }}</strong>
                        </span>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="content mt-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
