@extends('admin.layout')

@section('header_title', $module->exists ? 'Modul bearbeiten' : 'Neues Modul erstellen')

@section('content')
<div class="card shadow-sm max-w-lg">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ $module->exists ? 'Modul: ' . $module->label_de : 'Neues Preismodul anlegen' }}</h5>
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

        <form action="{{ $module->exists ? route('admin.price-modules.update', $module) : route('admin.price-modules.store') }}" method="POST">
            @csrf
            @if($module->exists)
                @method('PUT')
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="key" class="form-label">Key (Interner Bezeichner)</label>
                    <input type="text" class="form-control" id="key" name="key" value="{{ old('key', $module->key) }}" required {{ $module->exists ? 'readonly' : '' }}>
                    <div class="form-text">Z.B. 'cms_basic' oder 'seiten_zahl'. Darf keine Leerzeichen enthalten.</div>
                </div>
                <div class="col-md-6">
                    <label for="category" class="form-label">Kategorie (Optional)</label>
                    <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $module->category ?? 'base') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="label_de" class="form-label">Label (Angezeigt für Kunden)</label>
                <input type="text" class="form-control" id="label_de" name="label_de" value="{{ old('label_de', $module->label_de) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Beschreibung (Optional)</label>
                <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $module->description) }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="price" class="form-label">Preis (€)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $module->price) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Typ (Berechnungsart)</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="boolean" {{ old('type', $module->type) === 'boolean' ? 'selected' : '' }}>Fixpreis (Ja/Nein Frage)</option>
                        <option value="quantity" {{ old('type', $module->type) === 'quantity' ? 'selected' : '' }}>Pro Einheit (Zahlen Eingabe)</option>
                    </select>
                </div>
            </div>

            <div class="mb-4 form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" {{ old('is_active', $module->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Modul ist Aktiv (wird im Chatbot angezeigt)</label>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.price-modules.index') }}" class="btn btn-outline-secondary">Abbrechen</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ $module->exists ? 'Änderungen speichern' : 'Modul erstellen' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
