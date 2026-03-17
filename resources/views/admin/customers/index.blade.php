@extends('admin.layout')

@section('header_title', 'Kundenverwaltung')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Alle Kunden & Benutzer</h6>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Neuen Kunden anlegen
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Rolle</th>
                        <th>Status</th>
                        <th>Erstellt am</th>
                        <th class="text-end">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                @if($customer->isAdmin())
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-secondary">Kunde</span>
                                @endif
                            </td>
                            <td>
                                @if($customer->is_confirmed)
                                    <span class="badge bg-success">Bestätigt</span>
                                @else
                                    <span class="badge bg-warning text-dark">Ausstehend</span>
                                @endif
                            </td>
                            <td>{{ $customer->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(auth()->id() !== $customer->id)
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Möchten Sie diesen Benutzer wirklich löschen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Keine Kunden gefunden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
