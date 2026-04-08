@extends('customer.layout')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Willkommen, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-muted">Hier finden Sie den aktuellen Status Ihrer Projekte und Ihre Dokumente.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-dark p-2">Kundennummer: #{{ Auth::user()->id + 1000 }}</span>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-primary fw-bold">{{ $stats['total_inquiries'] }}</h3>
                    <p class="text-muted mb-0">Gesamte Anfragen</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-warning fw-bold">{{ $stats['pending_inquiries'] }}</h3>
                    <p class="text-muted mb-0">Ausstehend</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-success fw-bold">{{ $stats['confirmed_inquiries'] }}</h3>
                    <p class="text-muted mb-0">Bestätigt</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-info fw-bold">{{ number_format($stats['total_spent'], 2, ',', '.') }} €</h3>
                    <p class="text-muted mb-0">Gesamtausgaben</p>
                </div>
            </div>
        </div>
    </div>

    {{-- قسم حالة المشروع النشط (ClickUp) --}}
    @php $activeInquiry = $inquiries->whereNotNull('clickUpMapping')->first(); @endphp
    
    @if($activeInquiry && $activeInquiry->clickUpMapping)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold text-info">
                <i class="bi bi-rocket-takeoff me-2"></i>Aktueller Projektfortschritt
            </h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <p class="mb-1 text-muted">Projekt: <strong>{{ $activeInquiry->quote_number }}</strong></p>
                    <p class="mb-0">Status: 
                        <span class="badge rounded-pill bg-info text-dark">
                            {{ $activeInquiry->clickUpMapping->clickup_status_name }}
                        </span>
                    </p>
                </div>
                <div class="col-md-8">
                    @php
                        $status = strtolower($activeInquiry->clickUpMapping->clickup_status_name);
                        $percent = match($status) {
                            'offen', 'open' => 5,
                            'todo', 'to do' => 15,
                            'in bearbeitung', 'in progress' => 45,
                            'review', 'qa' => 80,
                            'warte auf kundenabnahme', 'waiting' => 90,
                            'complete', 'closed', 'done' => 100,
                            default => 5
                        };
                        $barColor = $percent == 100 ? 'bg-success' : ($percent > 50 ? 'bg-primary' : 'bg-info');
                    @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small fw-bold">{{ $percent }}% abgeschlossen</span>
                        <small class="text-muted">Letzter Sync: {{ $activeInquiry->clickUpMapping->last_synced_at ? $activeInquiry->clickUpMapping->last_synced_at->diffForHumans() : 'Gerade eben' }}</small>
                    </div>
                    <div class="progress" style="height: 12px; border-radius: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated {{ $barColor }}" 
                             role="progressbar" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- جدول كافة الطلبات --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Meine Anfragen & Angebote</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Anfrage-Nr./ Projekt</th>
                            <th>Datum</th>
                            <th>Summe (Netto)</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $inq)
                        <tr>
                            <td class="ps-4"><strong>{{ $inq->quote_number }}</strong></td>
                            <td>{{ $inq->created_at->format('d.m.Y') }}</td>
                            <td>{{ number_format($inq->total_estimated_price, 2, ',', '.') }} €</td>
                            <td>
                                @if($inq->status === 'pending')
                                    <span class="badge bg-warning text-dark px-3">In Prüfung</span>
                                @elseif($inq->status === 'confirmed')
                                    <span class="badge bg-success px-3">Bestätigt</span>
                                @else
                                    <span class="badge bg-secondary px-3">Archiviert</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary shadow-sm me-1" data-bs-toggle="modal" data-bs-target="#pdfModal" data-quote="{{ $inq->quote_number }}">
                                    <i class="bi bi-eye"></i> Anzeigen
                                </button>
                                <a href="{{ route('chatbot.pdf', $inq->quote_number) }}" class="btn btn-sm btn-outline-danger shadow-sm" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> Download
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Keine Anfragen gefunden.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- PDF Modal --}}
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Angebot anzeigen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfFrame" src="" width="100%" height="600px" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                <a id="downloadBtn" href="" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var pdfModal = document.getElementById('pdfModal');
    pdfModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var quoteNumber = button.getAttribute('data-quote');
        var pdfUrl = '{{ url("/chatbot/pdf") }}/' + quoteNumber + '/embedded';
        var downloadUrl = '{{ url("/chatbot/pdf") }}/' + quoteNumber;
        
        document.getElementById('pdfFrame').src = pdfUrl;
        document.getElementById('downloadBtn').href = downloadUrl;
    });
});
</script>
@endsection