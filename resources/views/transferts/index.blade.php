@extends('layouts.main')

@section('title', 'Transferts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-arrow-left-right"></i> Transferts de Stock</h2>
    <a href="{{ route('transferts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Transfert
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover data-table" id="transfertsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>De</th>
                        <th>Vers</th>
                        <th>Nb Articles</th>
                        <th>Statut</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transferts as $transfert)
                        <tr>
                            <td><strong>#{{ $transfert->id }}</strong></td>
                            <td>{{ $transfert->date_transfert->format('d/m/Y') }}</td>
                            <td>{{ $transfert->depotSource->nom }}</td>
                            <td>{{ $transfert->depotDestination->nom }}</td>
                            <td>{{ $transfert->lignes->count() }}</td>
                            <td>
                                @if($transfert->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($transfert->statut == 'en_cours')
                                    <span class="badge bg-info">En cours</span>
                                @elseif($transfert->statut == 'termine')
                                    <span class="badge bg-success">Terminé</span>
                                @else
                                    <span class="badge bg-danger">Annulé</span>
                                @endif
                            </td>
                            <td>{{ $transfert->createdBy->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('transferts.show', $transfert) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="bi bi-inbox"></i> Aucun transfert trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
