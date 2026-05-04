@extends('admin.layout')

@section('header_title', $customer->exists ? 'Kunden bearbeiten' : 'Neuen Kunden anlegen')

@section('content')
<div class="card shadow-sm max-w-lg">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ $customer->exists ? 'Kunde: ' . $customer->name : 'Neuen Benutzer anlegen' }}</h5>
    </div>
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $customer->exists ? route('admin.customers.update', $customer) : route('admin.customers.store') }}" method="POST">
            @csrf
            @if($customer->exists)
                @method('PUT')
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name*</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="username" class="form-label">Benutzername (Optional)</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $customer->username) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email*</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                </div>
                
                    <div class="col-md-6">
                        <label for="password" class="form-label">Passwort</label>
                        <input type="text" class="form-control" disabled value="Wird automatisch generiert">
                        <div class="form-text">Das Passwort wird generiert und per E-Mail versendet.</div>
                    </div>
                
            </div>
            <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="firma" class="form-label">Firma</label>
                        <input type="text" class="form-control" id="firma" name="firma" value="{{ old('firma', $customer->firma) }}" >
                    </div>
                    <div class="col-md-6">
                        <label for="tel" class="form-label">Telefon</label>
                        <input type="text" class="form-control" id="tel" name="tel" value="{{ old('tel', $customer->tel) }}" >
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="strasse" class="form-label">Straße + Hausnummer</label>
                        <input type="text" class="form-control" id="strasse" name="strasse" value="{{ old('strasse', $customer->strasse) }}" >
                    </div>
                    <div class="col-md-6">
                        <label for="zip_stadt" class="form-label">PLZ + Ort</label>
                        <input type="text" class="form-control" id="zip_stadt" name="zip_stadt" value="{{ old('zip_stadt', $customer->zip_stadt) }}" >
                    </div>
                </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="role" class="form-label">Rolle</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="kunde" {{ old('role', $customer->role) === 'kunde' || old('role', $customer->role) === 'kunde' ? 'selected' : '' }}>Kunde</option>
                        <option value="admin" {{ old('role', $customer->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-6 mt-4">
                    <div class="form-check form-switch pt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_confirmed" name="is_confirmed" value="1" {{ old('is_confirmed', $customer->is_confirmed) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_confirmed">Account bestätigt</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Abbrechen</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ $customer->exists ? 'Änderungen speichern' : 'Kunden anlegen' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
