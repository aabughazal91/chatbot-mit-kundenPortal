<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center">
        @if (Route::has('login'))
            <nav class="position-absolute top-0 end-0 p-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm me-2">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm">Register</a>
                    @endif
                @endauth
            </nav>
        @endif

        <div class="card shadow-sm border-0" style="max-width: 600px; width: 100%;">
            <div class="card-body p-5 text-center">
                <h1 class="display-6 fw-bold mb-3">Welcome to {{ config('app.name', 'Laravel') }}</h1>
                <p class="text-muted mb-4">Start by calculating the cost of your web project or log in to view your dashboard.</p>
                <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                    <a href="{{ url('/konfigurator') }}" class="btn btn-primary px-4 py-2">Start Configurator</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
