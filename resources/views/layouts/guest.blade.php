<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Login') }}</title>

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-dark text-white">
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-5 bg-dark">
        <div class="mb-4">
            <a href="/">
                <x-application-logo />
            </a>
        </div>

        <div class="w-100 px-4 py-4 bg-dark border border-secondary text-white shadow rounded" style="max-width: 450px;">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
