@extends('admin.layout')

@section('header_title', $module->exists ? 'Modul bearbeiten' : 'Neues Modul erstellen')

@section('content')
<div class="card shadow-sm max-w-lg">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ $module->exists ? 'Modul: ' . $module->bezeichnung_de : 'Neues Preismodul anlegen' }}</h5>
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
                    <label for="kategorie" class="form-label">Kategorie (Optional)</label>
                    <input type="text" class="form-control" id="kategorie" name="kategorie" value="{{ old('kategorie', $module->kategorie ?? 'base') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="bezeichnung_de" class="form-label">Bezeichnung (Angezeigt für Kunden)</label>
                <input type="text" class="form-control" id="bezeichnung_de" name="bezeichnung_de" value="{{ old('bezeichnung_de', $module->bezeichnung_de) }}" required>
            </div>

            <div class="mb-3">
                <label for="beschreibung" class="form-label">Beschreibung (Optional)</label>
                <textarea class="form-control" id="beschreibung" name="beschreibung" rows="2">{{ old('beschreibung', $module->beschreibung) }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="preis" class="form-label">Preis (€)</label>
                    <input type="number" step="0.01" class="form-control" id="preis" name="preis" value="{{ old('preis', $module->preis) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="typ" class="form-label">Typ (Berechnungsart)</label>
                    <select class="form-select" id="typ" name="typ" required>
                        <option value="boolean" {{ old('typ', $module->typ) === 'boolean' ? 'selected' : '' }}>Fixpreis (Ja/Nein Frage)</option>
                        <option value="quantity" {{ old('typ', $module->typ) === 'quantity' ? 'selected' : '' }}>Pro Einheit (Zahlen Eingabe)</option>
                        <option value="select" {{ old('typ', $module->typ) === 'select' ? 'selected' : '' }}>Auswahl (Select/Optionen)</option>
                    </select>
                </div>
            </div>

            

            <div id="dynamic-options-container" style="display: none;" class="mb-4 border p-3 rounded bg-light">
                <h6>Optionen für "Auswahl"</h6>
                <div id="options-list">
                    @php
                        $options = old('optionen', $module->optionen ?? []);
                    @endphp
                    @foreach($options as $index => $option)
                        <div class="row mb-2 option-row">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="optionen[{{$index}}][label]" value="{{ $option['label'] ?? '' }}" placeholder="Label (z.B. Wordpress)" required>
                            </div>
                            <div class="col-md-5">
                                <input type="number" step="0.01" class="form-control" name="optionen[{{$index}}][price]" value="{{ $option['price'] ?? '' }}" placeholder="Preis (€)" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-danger remove-option mt-1">X</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-option-btn">+ Option hinzufügen</button>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.price-modules.index') }}" class="btn btn-outline-secondary">Abbrechen</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ $module->exists ? 'Änderungen speichern' : 'Modul erstellen' }}
                </button>
            </div>
            <div class="mb-4 mt-4 form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="ist_aktiv" name="ist_aktiv" {{ old('ist_aktiv', $module->ist_aktiv ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="ist_aktiv">Modul ist Aktiv (wird im Chatbot angezeigt)</label>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('typ');
        const optionsContainer = document.getElementById('dynamic-options-container');
        const addOptionBtn = document.getElementById('add-option-btn');
        const optionsList = document.getElementById('options-list');
        const priceInput = document.getElementById('preis');
        
        function toggleOptions() {
            if (typeSelect.value === 'select') {
                optionsContainer.style.display = 'block';
            } else {
                optionsContainer.style.display = 'none';
            }
        }

        typeSelect.addEventListener('change', toggleOptions);
        toggleOptions();

        let optionIndex = {{ count(old('optionen', $module->optionen ?? [])) }};

        addOptionBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'row mb-2 option-row';
            row.innerHTML = `
                <div class="col-md-5">
                    <input type="text" class="form-control" name="optionen[${optionIndex}][label]" placeholder="Label (z.B. Wordpress)" required>
                </div>
                <div class="col-md-5">
                    <input type="number" step="0.01" class="form-control" name="optionen[${optionIndex}][price]" placeholder="Preis (€)" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-danger remove-option mt-1">X</button>
                </div>
            `;
            optionsList.appendChild(row);
            optionIndex++;
        });

        optionsList.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-option')) {
                e.target.closest('.option-row').remove();
            }
        });
    });
</script>
@endsection
