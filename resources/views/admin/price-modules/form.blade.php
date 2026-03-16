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
                        <option value="select" {{ old('type', $module->type) === 'select' ? 'selected' : '' }}>Auswahl (Select/Optionen)</option>
                    </select>
                </div>
            </div>

            

            <div id="dynamic-options-container" style="display: none;" class="mb-4 border p-3 rounded bg-light">
                <h6>Optionen für "Auswahl"</h6>
                <div id="options-list">
                    @php
                        $options = old('options', $module->options ?? []);
                    @endphp
                    @foreach($options as $index => $option)
                        <div class="row mb-2 option-row">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="options[{{$index}}][label]" value="{{ $option['label'] ?? '' }}" placeholder="Label (z.B. Wordpress)" required>
                            </div>
                            <div class="col-md-5">
                                <input type="number" step="0.01" class="form-control" name="options[{{$index}}][price]" value="{{ $option['price'] ?? '' }}" placeholder="Preis (€)" required>
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
                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" {{ old('is_active', $module->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Modul ist Aktiv (wird im Chatbot angezeigt)</label>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const optionsContainer = document.getElementById('dynamic-options-container');
        const addOptionBtn = document.getElementById('add-option-btn');
        const optionsList = document.getElementById('options-list');
        const priceInput = document.getElementById('price');
        
        function toggleOptions() {
            if (typeSelect.value === 'select') {
                optionsContainer.style.display = 'block';
                // Force base price to 0 if select, as prices are in options? 
                // Or leave base price for shared cost. 
            } else {
                optionsContainer.style.display = 'none';
            }
        }

        typeSelect.addEventListener('change', toggleOptions);
        toggleOptions();

        let optionIndex = {{ count(old('options', $module->options ?? [])) }};

        addOptionBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'row mb-2 option-row';
            row.innerHTML = `
                <div class="col-md-5">
                    <input type="text" class="form-control" name="options[${optionIndex}][label]" placeholder="Label (z.B. Wordpress)" required>
                </div>
                <div class="col-md-5">
                    <input type="number" step="0.01" class="form-control" name="options[${optionIndex}][price]" placeholder="Preis (€)" required>
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
