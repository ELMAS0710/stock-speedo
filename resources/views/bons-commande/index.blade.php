@extends('layouts.main')

@section('title', 'Bons de Commande')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cart"></i> Bons de Commande</h2>
    <a href="{{ route('bons-commande.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Bon de Commande
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
        <form method="GET" action="{{ route('bons-commande.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label class="form-label">Dépôt</label>
                    <select name="depot_id" class="form-select">
                        <option value="">Tous les dépôts</option>
                        @foreach($depots as $depot)
                            <option value="{{ $depot->id }}" {{ request('depot_id') == $depot->id ? 'selected' : '' }}>
                                {{ $depot->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>Validee</option>
                        <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livree</option>
                        <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulee</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                <a href="{{ route('bons-commande.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="bonsCommandeTable">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Date Commande</th>
                        <th>Dépôt</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bonsCommande as $bc)
                        <tr>
                            <td><strong>{{ $bc->reference }}</strong></td>
                            <td>{{ $bc->client->nom }} {{ $bc->client->prenom }}</td>
                            <td>{{ \Carbon\Carbon::parse($bc->date_commande)->format('d/m/Y') }}</td>
                            <td>{{ $bc->depot->nom }}</td>
                            <td>
                                @if($bc->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($bc->statut == 'validee')
                                    <span class="badge bg-success">Validee</span>
                                @elseif($bc->statut == 'livree')
                                    <span class="badge bg-info">Livree</span>
                                @elseif($bc->statut == 'annulee')
                                    <span class="badge bg-danger">Annulee</span>
                                @else
                                    <span class="badge bg-secondary">{{ $bc->statut }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('bons-commande.show', $bc) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun bon de commande trouvé</td>
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
    $('#bonsCommandeTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
        },
        "ordering": false,  // Désactiver complètement le tri
        "pageLength": 25
    });
});
</script>
@endpush