@extends('admin.layout')

@section('header_title', 'Anfrage Details: ' . $inquiry->angebot_nummer)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kundenangaben & Kalkulation</h5>
                <a href="{{ route('chatbot.pdf', $inquiry->angebot_nummer) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> PDF Anzeigen/Download
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table w-100">
                        <thead class="table-light">
                            <tr>

                                <th>Modul</th>
                                <th>Kundenwahl</th>
                                <th> </th>
                                <th class="text-end">Preis (Einzeln)</th>
                                <th class="text-end">Summe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiry->items as $item)
                            <tr>
                                <td>{{ $item->priceModule ? $item->priceModule->bezeichnung_de : 'Unbekannt (Gelöscht)' }}</td>
                                <td>{{ $item->kunden_auswahl ?? ($item->menge > 1 ? $item->menge . ' Stück' : 'Ja') }}</td>
                                <td class="text-center"></td>
                                <td class="text-end">
                                    <form action="{{ route('admin.inquiries.updateItemPrice', [$inquiry, $item]) }}" method="POST" class="d-inline-flex align-items-center gap-1 justify-content-end">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="preis_zum_zeitpunkt" value="{{ $item->preis_zum_zeitpunkt }}" step="0.01" min="0" class="form-control form-control-sm text-end" style="width: 100px;">
                                        <span>€</span>
                                        <button type="submit" class="btn btn-outline-primary btn-sm" title="Preis speichern">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">{{ number_format($item->preis_zum_zeitpunkt * $item->menge, 2, ',', '.') }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr>
                                <th colspan="4" class="text-end fs-5">Gesamtsumme (Brutto):</th>
                                <th class="text-end fs-5 text-primary">{{ number_format($inquiry->geschätzter_gesamtpreis, 2, ',', '.') }} €</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-md-4">
        
        <!-- Status -->
        <div class="card shadow-sm mb-4 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-activity"></i> Anfragestatus</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inquiries.updateStatus', $inquiry) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Aktueller Status</label>
                        <select name="status" class="form-select">
                            <option value="offen" {{ $inquiry->status === 'offen' ? 'selected' : '' }}>Ausstehend</option>
                            <option value="bestätigt" {{ $inquiry->status === 'bestätigt' ? 'selected' : '' }}>Bestätigt</option>
                            <option value="storniert" {{ $inquiry->status === 'storniert' ? 'selected' : '' }}>Storniert</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Status Speichern</button>
                </form>
            </div>
        </div>
        <div>
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-card-text"></i> Projekt Name</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.inquiries.updateProjectName', $inquiry) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Update Projekt Name</label>
                            <input type="text" name="quote_number" class="form-control" value="{{ $inquiry->angebot_nummer }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Projekt Name Speichern</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Verknüpfung -->
        <div class="card shadow-sm mb-4 border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-person-fill"></i> Kunden zuweisen</h6>
            </div>
            <div class="card-body">
                @if($inquiry->user_id)
                    <div class="alert alert-success py-2 mb-0">
                        <strong>Aktueller Kunde:</strong><br>
                        {{ $inquiry->user->name ?? 'Unbekannt' }} <br>
                        <a href="mailto:{{ $inquiry->user->email ?? '' }}" class="text-decoration-none text-dark">{{ $inquiry->user->email ?? 'Keine Email' }}</a>
                    </div>
                @else
                    <form action="{{ route('admin.inquiries.linkUser', $inquiry) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small">Kunden E-Mail (Verpflichtend)</label>
                            <input type="text" name="identifier" class="form-control form-control-sm" required placeholder="[EMAIL_ADDRESS]">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Name (Optional)</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="Max Mustermann">
                        </div>
                        <button type="submit" class="btn btn-sm btn-success w-100">Benutzer verknüpfen</button>
                    </form>
                    <div class="form-text small mt-2">Wenn die E-Mail nicht im System existiert, wird automatisch ein neues Konto mit einem zufälligen Passwort erstellt, und der Status wird auf 'Bestätigt' gesetzt.</div>
                @endif
            </div>
        </div>

        <!-- ClickUp Integration -->
        <div class="card shadow-sm border-secondary">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"> ClickUp Integration</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inquiries.updateClickUp', $inquiry) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">ClickUp Task ID</label>
                        <input type="text" name="clickup_task_id" class="form-control" placeholder="z.B. 86dqx1..." value="{{ old('clickup_task_id', $inquiry->clickUpMapping->clickup_aufgabe_id ?? '') }}" required>
                        <div class="form-text">Task ID aus der URL kopieren.</div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Task verknüpfen</button>
                </form>

                @if($inquiry->clickUpMapping && $inquiry->clickUpMapping->zuletzt_synchronisiert_am)
                <hr>
                <div class="small">
                    <strong>Letzter Sync:</strong> <br>
                    <span class="text-muted">{{ $inquiry->clickUpMapping->zuletzt_synchronisiert_am->format('d.m.Y H:i:s') }}</span>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
