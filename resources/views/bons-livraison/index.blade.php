@extends('layouts.main')

@section('title', 'Bons de Livraison')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-truck"></i> Bons de Livraison</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="bi bi-funnel"></i> Filtres</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('bons-livraison.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Référence</label>
                    <input type="text" name="reference" class="form-control" value="{{ request('reference') }}" placeholder="Rechercher par référence">
                </div>
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
                <a href="{{ route('bons-livraison.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="bonsLivraisonTable">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Date Livraison</th>
                        <th>BC</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bonsLivraison as $bl)
                        <tr>
                            <td><strong>{{ $bl->reference }}</strong></td>
                            <td>{{ $bl->client->nom }} {{ $bl->client->prenom }}</td>
                            <td>{{ \Carbon\Carbon::parse($bl->date_livraison)->format('d/m/Y') }}</td>
                            <td>
                                @if($bl->bon_commande_id)
                                    <span class="badge bg-info">{{ $bl->bonCommande->reference ?? 'N/A' }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($bl->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($bl->statut == 'livre')
                                    <span class="badge bg-success">Livré</span>
                                @elseif($bl->statut == 'annule')
                                    <span class="badge bg-danger">Annulé</span>
                                @else
                                    <span class="badge bg-secondary">{{ $bl->statut }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('bons-livraison.show', $bl) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun bon de livraison trouvé</td>
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
    $('#bonsLivraisonTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
        },
        "ordering": false,  // Désactiver complètement le tri
        "pageLength": 25
    });
});
</script>
@endpush

