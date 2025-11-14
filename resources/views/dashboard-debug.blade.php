@extends('layouts.main')

@section('title', 'Tableau de bord - VERSION DEBUG')

@section('content')
<div class="container-fluid">
    <div class="alert alert-warning">
        <strong>⚠️ VERSION DEBUG</strong> - Fichier chargé le : {{ now()->format('d/m/Y H:i:s') }}
    </div>
    
    <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Tableau de bord</h2>

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="card-title text-muted"><i class="bi bi-people"></i> Clients actifs</h6>
                    <h3 class="mb-0">{{ $totalClients }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="card-title text-muted"><i class="bi bi-file-earmark-check"></i> Bons de livraison</h6>
                    <h3 class="mb-0">{{ $totalBonsLivraison }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="card-title text-muted"><i class="bi bi-file-earmark-text"></i> Devis validés</h6>
                    <h3 class="mb-0">{{ $devisValides }} / {{ $devisTotal }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="card-title text-muted"><i class="bi bi-boxes"></i> Stock total</h6>
                    <h3 class="mb-0">{{ number_format($stockTotal, 0, ',', ' ') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers mouvements de stock -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #ff6b6b; color: white;">
            <h5 class="mb-0"><i class="bi bi-arrow-left-right"></i> 🔴 VERSION DEBUG - Derniers mouvements de stock</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="cursor: default;">
                    <thead class="table-light">
                        <tr>
                            <th style="user-select: none; pointer-events: none;">Date</th>
                            <th style="user-select: none; pointer-events: none;">Article</th>
                            <th style="user-select: none; pointer-events: none;">Dépôt</th>
                            <th style="user-select: none; pointer-events: none; background-color: yellow;">TYPE DEBUG</th>
                            <th class="text-center" style="user-select: none; pointer-events: none;">Quantité</th>
                            <th style="user-select: none; pointer-events: none;">Utilisateur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($derniersMouvements as $mouvement)
                            <tr>
                                <td>
                                    <small>{{ $mouvement->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $mouvement->article->nom ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    {{ $mouvement->depot->nom ?? 'N/A' }}
                                </td>
                                <td style="background-color: #ffffcc; padding: 10px;">
                                    @if($mouvement->quantite > 0)
                                        <span class="badge bg-success" style="font-size: 14px;">
                                            <i class="fas fa-arrow-down"></i> Entrée
                                        </span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 14px;">
                                            <i class="fas fa-arrow-up"></i> Sortie
                                        </span>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        Qté: {{ $mouvement->quantite }} | 
                                        Test: {{ $mouvement->quantite > 0 ? 'POS' : 'NEG' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ number_format(abs($mouvement->quantite), 2, ',', ' ') }}</span>
                                </td>
                                <td>
                                    {{ $mouvement->createdBy->name ?? 'Inconnu' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    Aucun mouvement enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('mouvements-stock.index') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-list"></i> Voir tous les mouvements
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Articles les plus sortis -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-down"></i> Articles les plus sortis (30 derniers jours)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th class="text-end">Quantité sortie</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articlesPlusSortis as $mouvement)
                                    <tr>
                                        <td>
                                            <strong>{{ $mouvement->article->nom ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $mouvement->article->reference ?? '' }}</small>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-danger">{{ number_format($mouvement->total_sortie, 0, ',', ' ') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">
                                            Aucune sortie dans les 30 derniers jours
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock par dépôt -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Stock par dépôt</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($stockParDepot as $depot)
                            <div class="col-md-6 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $depot->nom }}</h6>
                                        <h4 class="mb-0 text-primary">{{ number_format($depot->stocks_sum_quantite ?? 0, 0, ',', ' ') }}</h4>
                                        <small class="text-muted">articles en stock</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
