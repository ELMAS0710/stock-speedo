@extends('layouts.main')

@section('title', 'Devis')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-file-earmark-text"></i> Devis</h2>
    <a href="{{ route('devis.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Devis
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="bi bi-funnel"></i> Filtres</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('devis.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Client</label>
                    <select name="client_id" class="form-select">
                        <option value="">Tous les clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->nom }} {{ $client->prenom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="refuse" {{ request('statut') == 'refuse' ? 'selected' : '' }}>Refusé</option>
                        <option value="expire" {{ request('statut') == 'expire' ? 'selected' : '' }}>Expiré</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                <a href="{{ route('devis.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="devisTable">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Date Devis</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devis as $devi)
                        <tr>
                            <td><strong>{{ $devi->reference }}</strong></td>
                            <td>{{ $devi->client->nom }} {{ $devi->client->prenom }}</td>
                            <td>{{ \Carbon\Carbon::parse($devi->date_devis)->format('d/m/Y') }}</td>
                            <td>
                                @if($devi->statut == 'brouillon')
                                    <span class="badge bg-secondary">Brouillon</span>
                                @elseif($devi->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($devi->statut == 'valide')
                                    <span class="badge bg-success">Validé</span>
                                @elseif($devi->statut == 'refuse')
                                    <span class="badge bg-danger">Refusé</span>
                                @else
                                    <span class="badge bg-dark">Expiré</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('devis.show', $devi) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun devis trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#devisTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
        },
        "order": [[2, "desc"]],
        "pageLength": 25
    });
});
</script>
@endpush
