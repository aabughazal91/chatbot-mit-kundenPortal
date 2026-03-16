@extends('admin.layout')

@section('header_title', 'Anfrage Details: ' . $inquiry->quote_number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kundenangaben & Kalkulation</h5>
                <a href="{{ route('chatbot.pdf', $inquiry->quote_number) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> PDF Anzeigen/Download
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Modul</th>
                                <th class="text-center">Menge</th>
                                <th class="text-end">Preis (Einzeln)</th>
                                <th class="text-end">Summe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inquiry->items as $item)
                            <tr>
                                <td>{{ $item->priceModule ? $item->priceModule->label_de : 'Unbekannt (Gelöscht)' }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price_at_time, 2, ',', '.') }} €</td>
                                <td class="text-end">{{ number_format($item->price_at_time * $item->quantity, 2, ',', '.') }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr>
                                <th colspan="3" class="text-end fs-5">Gesamtsumme (Netto):</th>
                                <th class="text-end fs-5 text-primary">{{ number_format($inquiry->total_estimated_price, 2, ',', '.') }} €</th>
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
                            <option value="pending" {{ $inquiry->status === 'pending' ? 'selected' : '' }}>Ausstehend</option>
                            <option value="confirmed" {{ $inquiry->status === 'confirmed' ? 'selected' : '' }}>Bestätigt</option>
                            <option value="cancelled" {{ $inquiry->status === 'cancelled' ? 'selected' : '' }}>Storniert</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Status Speichern</button>
                </form>
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
                            <label class="form-label small">E-Mail oder Benutzername (Verpflichtend)</label>
                            <input type="text" name="identifier" class="form-control form-control-sm" required placeholder="kunde@beispiel.de oder username">
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
                <h6 class="mb-0"><img src="https://clickup.com/landing/images/logo.svg" height="15" alt="ClickUp"> Integration</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inquiries.updateClickUp', $inquiry) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">ClickUp Task ID</label>
                        <input type="text" name="clickup_task_id" class="form-control" placeholder="z.B. 86dqx1..." value="{{ old('clickup_task_id', $inquiry->clickUpMapping->clickup_task_id ?? '') }}" required>
                        <div class="form-text">Task ID aus der URL kopieren.</div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Task verknüpfen</button>
                </form>

                @if($inquiry->clickUpMapping && $inquiry->clickUpMapping->last_synced_at)
                <hr>
                <div class="small">
                    <strong>Letzter Sync:</strong> <br>
                    <span class="text-muted">{{ $inquiry->clickUpMapping->last_synced_at->format('d.m.Y H:i:s') }}</span>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
