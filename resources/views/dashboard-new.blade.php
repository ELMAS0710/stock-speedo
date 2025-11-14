@extends('layouts.main')

@section('title', 'Dashboard - Speedo Gestion Stock')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Clients</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalClients }}</h2>
                    </div>
                    <div class="rounded p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="bi bi-people text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Bons de livraison</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalBonsLivraison }}</h2>
                    </div>
                    <div class="rounded p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="bi bi-truck text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Devis validés</h6>
                        <h2 class="mb-0 fw-bold">{{ $devisValides }}</h2>
                    </div>
                    <div class="rounded p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="bi bi-check-circle text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Stock Total</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($stockTotal, 0, ',', ' ') }}</h2>
                    </div>
                    <div class="rounded p-3" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                        <i class="bi bi-box-seam text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Stock par Dépôt -->
        <div class="col-md-6">
            <div class="stat-card">
                <h5 class="mb-4"><i class="bi bi-building me-2"></i>Stock par Dépôt</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Dépôt</th>
                                <th>Ville</th>
                                <th class="text-end">Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockParDepot as $depot)
                            <tr>
                                <td><strong>{{ $depot->nom }}</strong></td>
                                <td>{{ $depot->ville }}</td>
                                <td class="text-end">
                                    <span class="badge bg-primary">
                                        {{ number_format($depot->stocks_sum_quantite ?? 0, 0, ',', ' ') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun dépôt</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Articles les plus sortis -->
        <div class="col-md-6">
            <div class="stat-card">
                <h5 class="mb-4"><i class="bi bi-graph-up me-2"></i>Articles les plus sortis (30 derniers jours)</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Référence</th>
                                <th class="text-end">Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articlesPlusSortis as $mouvement)
                            <tr>
                                <td><strong>{{ $mouvement->article->nom ?? 'N/A' }}</strong></td>
                                <td>{{ $mouvement->article->reference ?? '' }}</td>
                                <td class="text-end">
                                    <span class="badge bg-danger">
                                        {{ number_format($mouvement->total_sortie, 0, ',', ' ') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun mouvement</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Derniers mouvements -->
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Derniers mouvements de stock</h5>
                    <a href="{{ route('mouvements-stock.index') }}" class="btn btn-sm btn-primary">
                        Voir tous les mouvements <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Article</th>
                                <th>Dépôt</th>
                                <th>Type</th>
                                <th class="text-end">Quantité</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($derniersMouvements as $mouvement)
                            <tr>
                                <td>{{ $mouvement->created_at->format('d/m/Y H:i') }}</td>
                                <td><strong>{{ $mouvement->article->nom ?? 'N/A' }}</strong></td>
                                <td>{{ $mouvement->depot->nom ?? 'N/A' }}</td>
                                <td>
                                    @if($mouvement->quantite > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-arrow-down"></i> Entrée
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-arrow-up"></i> Sortie
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-primary">
                                        {{ number_format(abs($mouvement->quantite), 0, ',', ' ') }}
                                    </span>
                                </td>
                                <td>{{ $mouvement->createdBy->name ?? 'Inconnu' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Aucun mouvement</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
