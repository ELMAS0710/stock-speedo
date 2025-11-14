@extends('layouts.main')

@section('title', 'Mouvements de Stock')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-exchange-alt text-primary"></i> Mouvements de Stock
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Accueil</a>
                    </li>
                    <li class="breadcrumb-item active">Mouvements de Stock</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stock.export-excel') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel"></i> Exporter Excel
            </a>
            <a href="{{ route('mouvements-stock.create') }}" class="btn btn-primary shadow">
                <i class="fas fa-plus-circle"></i> Nouveau Mouvement
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="bi bi-funnel"></i> Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('mouvements-stock.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Type de mouvement</label>
                        <select name="type" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                            <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                            <option value="transfert_entree" {{ request('type') == 'transfert_entree' ? 'selected' : '' }}>Transfert Entrée</option>
                            <option value="transfert_sortie" {{ request('type') == 'transfert_sortie' ? 'selected' : '' }}>Transfert Sortie</option>
                            <option value="ajustement" {{ request('type') == 'ajustement' ? 'selected' : '' }}>Ajustement</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Article</label>
                        <select name="article_id" class="form-select">
                            <option value="">Tous les articles</option>
                            @foreach(\App\Models\Article::orderBy('nom')->get() as $article)
                                <option value="{{ $article->id }}" {{ request('article_id') == $article->id ? 'selected' : '' }}>
                                    {{ $article->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dépôt</label>
                        <select name="depot_id" class="form-select">
                            <option value="">Tous les dépôts</option>
                            @foreach(\App\Models\Depot::orderBy('nom')->get() as $depot)
                                <option value="{{ $depot->id }}" {{ request('depot_id') == $depot->id ? 'selected' : '' }}>
                                    {{ $depot->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Rechercher
                        </button>
                        <a href="{{ route('mouvements-stock.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Historique des Mouvements
                <span class="badge bg-primary ms-2">{{ $mouvements->total() }} mouvement(s)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($mouvements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="cursor: default;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="user-select: none; pointer-events: none;"><i class="fas fa-calendar"></i> Date</th>
                                <th style="user-select: none; pointer-events: none;"><i class="fas fa-tag"></i> Type</th>
                                <th style="user-select: none; pointer-events: none;"><i class="fas fa-box"></i> Article</th>
                                <th style="user-select: none; pointer-events: none;"><i class="fas fa-warehouse"></i> Dépôt</th>
                                <th class="text-center" style="user-select: none; pointer-events: none;"><i class="fas fa-sort-numeric-up"></i> Quantité</th>
                                <th class="text-center" style="user-select: none; pointer-events: none;"><i class="fas fa-boxes"></i> Stock Actuel</th>
                                <th style="user-select: none; pointer-events: none;"><i class="fas fa-user"></i> Utilisateur</th>
                                <th style="user-select: none; pointer-events: none;"><i class="fas fa-comment-dots"></i> Motif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mouvements as $mouvement)
                                <tr>
                                    <td class="text-center align-middle">
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt"></i> {{ $mouvement->created_at->format('d/m/Y') }}<br>
                                            <i class="far fa-clock"></i> {{ $mouvement->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="align-middle">
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
                                    <td class="align-middle">
                                        <strong>{{ $mouvement->article->nom ?? 'N/A' }}</strong><br>
                                        <small class="text-muted"><i class="fas fa-hashtag"></i> {{ $mouvement->article->reference ?? 'N/A' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <i class="fas fa-warehouse text-primary"></i> {{ $mouvement->depot->nom ?? 'N/A' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-primary fs-6">{{ number_format(abs($mouvement->quantite), 2, ',', ' ') }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong class="text-success">{{ number_format($mouvement->quantite_apres, 2, ',', ' ') }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <i class="fas fa-user-circle text-muted"></i> {{ $mouvement->user_name ?? 'Inconnu' }}
                                    </td>
                                    <td class="align-middle">
                                        @if($mouvement->motif)
                                            <small class="text-muted">{{ $mouvement->motif }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-secondary opacity-50 mb-3"></i>
                    <h5 class="text-secondary">Aucun mouvement de stock</h5>
                    <p class="text-muted"><i class="fas fa-info-circle"></i> Commencez par créer un nouveau mouvement de stock.</p>
                    <a href="{{ route('mouvements-stock.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle"></i> Créer un Mouvement
                    </a>
                </div>
            @endif
            
            @if($mouvements->hasPages())
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-center">
                        {{ $mouvements->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush