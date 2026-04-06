<x-guest-layout>

<link rel="stylesheet" href="{{ asset('css/register.css') }}">

<form method="POST" action="{{ route('register') }}">
    @csrf

    {{-- Header --}}
    <div class="text-center mb-4">
        <h1 class="register-title mb-1">Konto erstellen</h1>
        <p class="register-subtitle">Registrieren Sie sich, um loszulegen</p>
    </div>

    {{-- ── Section: Personal Info ───────────────────────────────── --}}
    <p class="section-label">Persönliche Informationen</p>

    <div class="row g-3 mb-3">
        {{-- Full Name --}}
        <div class="col-md-6">
            <label for="name" class="form-label">Vollständiger Name <span class="text-danger">*</span></label>
            <div class="input-icon-wrapper">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       placeholder="Max Mustermann">
                <span class="input-icon">👤</span>
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        {{-- Username --}}
        <div class="col-md-6">
            <label for="username" class="form-label">Benutzername <span class="badge-optional">Optional</span></label>
            <div class="input-icon-wrapper">
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                       name="username" value="{{ old('username') }}"
                       placeholder="maxmuster">
                <span class="input-icon">@</span>
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-1" />
        </div>
    </div>

    {{-- ── Section: Account Credentials ────────────────────────── --}}
    <p class="section-label mt-4">E-Mail & Passwort</p>

    <div class="row g-3 mb-3">
        {{-- Email --}}
        <div class="col-md-6">
            <label for="email" class="form-label">E-Mail-Adresse <span class="text-danger">*</span></label>
            <div class="input-icon-wrapper">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email"
                       placeholder="max@beispiel.de">
                <span class="input-icon">✉️</span>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Confirm Email --}}
        <div class="col-md-6">
            <label for="email_confirmation" class="form-label">E-Mail bestätigen <span class="text-danger">*</span></label>
            <div class="input-icon-wrapper">
                <input id="email_confirmation" type="email" class="form-control @error('email_confirmation') is-invalid @enderror"
                       name="email_confirmation" value="{{ old('email_confirmation') }}" required
                       placeholder="max@beispiel.de">
                <span class="input-icon">✉️</span>
            </div>
            <x-input-error :messages="$errors->get('email_confirmation')" class="mt-1" />
        </div>
    </div>

    <div class="row g-3 mb-3">
        {{-- Password --}}
        <div class="col-md-6">
            <label for="password" class="form-label">Passwort <span class="text-danger">*</span></label>
            <div class="input-icon-wrapper">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="new-password"
                       placeholder="Mindestens 8 Zeichen">
                <span class="input-icon">🔒</span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Confirm Password --}}
        <div class="col-md-6">
            <label for="password_confirmation" class="form-label">Passwort bestätigen <span class="text-danger">*</span></label>
            <div class="input-icon-wrapper">
                <input id="password_confirmation" type="password" class="form-control"
                       name="password_confirmation" required autocomplete="new-password"
                       placeholder="Passwort wiederholen">
                <span class="input-icon">🔒</span>
            </div>
        </div>
    </div>

    {{-- ── Section: Company Info ────────────────────────────────── --}}
    <p class="section-label mt-4">Firmeninformationen <span class="badge-optional ms-1">Optional</span></p>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="company" class="form-label">Firma</label>
            <input id="company" type="text" class="form-control @error('company') is-invalid @enderror"
                   name="company" value="{{ old('company') }}" placeholder="Musterfirma GmbH">
            <x-input-error :messages="$errors->get('company')" class="mt-1" />
        </div>
        <div class="col-md-6">
            <label for="phone" class="form-label">Telefon</label>
            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                   name="phone" value="{{ old('phone') }}" placeholder="+49 123 456789">
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="street" class="form-label">Straße + Hausnummer</label>
            <input id="street" type="text" class="form-control @error('street') is-invalid @enderror"
                   name="street" value="{{ old('street') }}" placeholder="Musterstraße 42">
            <x-input-error :messages="$errors->get('street')" class="mt-1" />
        </div>
        <div class="col-md-6">
            <label for="zip" class="form-label">PLZ + Ort</label>
            <input id="zip" type="text" class="form-control @error('zip') is-invalid @enderror"
                   name="zip" value="{{ old('zip') }}" placeholder="10115 Berlin">
            <x-input-error :messages="$errors->get('zip')" class="mt-1" />
        </div>
    </div>

    {{-- ── Footer ───────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mt-2">
        <a href="{{ route('login') }}" class="login-link">
            Bereits registriert? &rarr; Login
        </a>
        <button type="submit" class="btn btn-register">
            Konto erstellen
        </button>
    </div>
</form>

</x-guest-layout>
