@extends('admin.layout')

@section('header_title', 'Module (Preise)')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Alle Module</h5>
        <a href="{{ route('admin.price-modules.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Neues Modul
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Key</th>
                        <th>Label (DE)</th>
                        <th>Preis</th>
                        <th>Typ</th>
                        <th>Status</th>
                        <th class="text-end">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modules as $module)
                    <tr>
                        <td>{{ $module->id }}</td>
                        <td><code>{{ $module->key }}</code></td>
                        <td>
                            <strong>{{ $module->bezeichnung_de }}</strong><br>
                            <small class="text-muted">{{ Str::limit($module->beschreibung, 50) }}</small>
                        </td>
                        <td>{{ number_format($module->preis, 2, ',', '.') }} €</td>
                        <td>
                            @if($module->typ === 'boolean')
                                <span class="badge bg-info">Fixpreis (Ja/Nein)</span>
                            @elseif($module->typ === 'quantity')
                                <span class="badge bg-warning text-dark">Pro Einheit (Zahl)</span>
                            @else
                                <span class="badge bg-secondary">Auswahl</span>
                            @endif
                        </td>
                        <td>
                            @if($module->ist_aktiv)
                                <span class="badge bg-success">Aktiv</span>
                            @else
                                <span class="badge bg-secondary">Inaktiv</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.price-modules.edit', $module) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.price-modules.destroy', $module) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Wirklich löschen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Löschen
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Keine Module gefunden.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
