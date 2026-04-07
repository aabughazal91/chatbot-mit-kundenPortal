<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body class="min-vh-100 position-relative overflow-hidden">
    <div class="hero-gradient min-vh-100 position-relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
            <div class="pattern-dots"></div>
        </div>
        
        @if (Route::has('login'))
            <nav class="position-absolute top-0 end-0 p-4 z-10">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-light btn-sm">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
        <div class="d-flex justify-content-center">
            <img src="{{ asset('logo-white.png') }}"
                 alt="agentur-77"
                 style="height: 160px; width:auto;">
        </div>
        <div class="container py-3">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 text-white">
                    <div class="floating">
                        <h1 class="display-3 fw-bold mb-4">Projektkostenrechner</h1>
                        <p class="lead mb-4" style="color: var(--text-secondary);">Erhalten Sie in wenigen Minuten präzise Kostenvoranschläge für Ihre Webentwicklungsprojekte. Unser intelligenter Rechner hilft Ihnen bei der effektiven Budgetplanung.</p>
                        <div class="d-grid gap-3 d-sm-flex justify-content-sm-start">
                            <a href="{{ url('/chatbot') }}" class="btn btn-light btn-lg px-4 py-3 btn-gradient">Rechner starten</a>
                            
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="glass-effect rounded-3 p-4 text-center text-white h-100">
                                <div class="feature-icon mb-3">
                                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    </svg>
                                </div>
                                <h5 class="fw-bold">Sofortige Kostenvoranschläge</h5>
                                <p class="small mb-0" style="color: var(--text-secondary);">Ermitteln Sie die Projektkosten in wenigen Minuten.</p>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="glass-effect rounded-3 p-4 text-center text-white h-100">
                                <div class="feature-icon mb-3">
                                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"/>
                                    </svg>
                                </div>
                                <h5 class="fw-bold">Benutzerdefinierte Funktionen</h5>
                                <p class="small mb-0" style="color: var(--text-secondary);">Auf Ihre Bedürfnisse zugeschnitten</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="glass-effect rounded-3 p-4 text-center text-white">
                                <div class="feature-icon mb-3">
                                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                    </svg>
                                </div>
                                <h5 class="fw-bold">Detaillierte Aufschlüsselung</h5>
                                <p class="small mb-0" style="color: var(--text-secondary);">Basierend auf  Ihren Anforderungen entwickeln wir auf Wunsch einen kostenfreien & unverbindlichen Designentwurf Ihrer Webseite. Diesen besprechen wir dann gemeinsam. Im Anschluss steht Ihnen der Designvorschlag so lange zur Verfügung, bis Sie eine Entscheidung treffen können. Dann geht es weiter.</p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>