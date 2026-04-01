@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Admin Dashboard</h1>
            <p class="text-muted mb-0">Übersicht über alle Anfragen und Statistiken</p>
        </div>
        <div>
            <span class="badge bg-primary">Admin</span>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Gesamte Anfragen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_inquiries'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ausstehend</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_inquiries'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Bestätigt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['confirmed_inquiries'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Gesamtkunden</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_customers'] }}</div>
                            
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Inquiries -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Neueste Anfragen</h6>
                    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-sm btn-primary">Alle anzeigen</a>
                </div>
                <div class="card-body">
                    @if($recentInquiries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Anfrage-Nr.</th>
                                        <th>Kunde</th>
                                        <th>Betrag</th>
                                        <th>Status</th>
                                        <th>Datum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentInquiries as $inquiry)
                                    <tr>
                                        <td><strong>{{ $inquiry->quote_number }}</strong></td>
                                        <td>{{ $inquiry->user ? $inquiry->user->name : 'Nicht zugewiesen' }}</td>
                                        <td>{{ number_format($inquiry->total_estimated_price, 2, ',', '.') }} €</td>
                                        <td>
                                            @if($inquiry->status === 'pending')
                                                <span class="badge bg-warning text-dark">In Prüfung</span>
                                            @elseif($inquiry->status === 'confirmed')
                                                <span class="badge bg-success">Bestätigt</span>
                                            @else
                                                <span class="badge bg-secondary">Archiviert</span>
                                            @endif
                                        </td>
                                        <td>{{ $inquiry->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Keine Anfragen gefunden.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

         <!-- Additional Stats -->
            <div class="col-xl-4 col-lg-5 mb-2">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Weitere Statistiken</h6>
                    </div>
                    <div class="card-body">
                        <div class="row no-gutters align-items-center mb-3">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Gesamtumsatz</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_revenue'], 2, ',', '.') }} €</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aktive Module</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_modules'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cubes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Top Modules -->
        <!-- <div class="col-xl-4 col-lg-5 offset-xl-8 offset-lg-7 mt-2">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Module</h6>
                </div>
                 <div class="card-body">
                    @if($topModules->count() > 0)
                        @foreach($topModules as $module)
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($module->usage_count / $topModules->first()->usage_count) * 100 }}%" aria-valuenow="{{ $module->usage_count }}" aria-valuemin="0" aria-valuemax="{{ $topModules->first()->usage_count }}"></div>
                        </div>
                        <h6 class="small font-weight-bold">{{ $module->label_de }}</h6>
                        <span class="text-xs">{{ $module->usage_count }} Mal verwendet</span>
                        <hr>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-cube fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Keine Module gefunden.</p>
                        </div>
                    @endif
                </div> --> 
            </div>

           
        </div>
    </div>
</div>
@endsection
