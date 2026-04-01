@extends('admin.layout')

@section('header_title', 'Anfragen (Inquiries)')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Alle Anfragen</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Anfrage-Nr./ Projekt</th>
                        <th>Datum</th>
                        <th>Kunde</th>
                        <th>Status</th>
                        <th>Geschätzter Preis</th>
                        <th>ClickUp Task ID</th>
                        <th class="text-end">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inquiries as $inq)
                    <tr>
                        <td><strong>{{ $inq->quote_number }}</strong></td>
                        <td>{{ $inq->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if($inq->user)
                                {{ $inq->user->name ?? 'Unbekannt' }} <br>
                                <small class="text-muted">{{ $inq->user->email }}</small>
                            @else
                                <span class="text-muted fst-italic">Kein Kunde</span>
                            @endif
                        </td>
                        <td>
                            @if($inq->status === 'pending')
                                <span class="badge bg-warning text-dark">Ausstehend</span>
                            @elseif($inq->status === 'confirmed')
                                <span class="badge bg-success">Bestätigt</span>
                            @else
                                <span class="badge bg-danger">Storniert</span>
                            @endif
                        </td>
                        <td>{{ number_format($inq->total_estimated_price, 2, ',', '.') }} €</td>
                        <td>
                            @if($inq->clickUpMapping && $inq->clickUpMapping->clickup_task_id)
                                <code>{{ $inq->clickUpMapping->clickup_task_id }}</code>
                                @if($inq->clickUpMapping->last_synced_at)
                                <br><small class="text-muted">Sync: {{ $inq->clickUpMapping->last_synced_at->format('d.m. H:i') }}</small>
                                @endif
                            @else
                                <span class="text-muted fst-italic">Nicht verknüpft</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.inquiries.show', $inq) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Details
                            </a>
                            <form action="{{ route('admin.inquiries.destroy', $inq) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Möchten Sie diese Anfrage wirklich löschen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Noch keine Anfragen vorhanden.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($inquiries->hasPages())
    <div class="card-footer bg-white border-top-0 pt-3">
        {{ $inquiries->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
