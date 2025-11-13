@extends('layouts.main')

@section('title', 'Article - ' . $article->nom)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-box text-primary"></i> {{ $article->nom }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="fas fa-home"></i> Accueil</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('articles.index') }}">Articles</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $article->nom }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations de l'article</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Référence</label>
                            <p class="fw-bold">{{ $article->reference }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Famille</label>
                            <p class="fw-bold">
                                <span class="badge bg-info">{{ $article->famille->nom ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>

                    @if($article->description)
                    <div class="mb-3">
                        <label class="text-muted small">Description</label>
                        <p>{{ $article->description }}</p>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small">Créé le</label>
                            <p>{{ $article->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Modifié le</label>
                            <p>{{ $article->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock par dépôt -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-warehouse"></i> Stock par dépôt</h5>
                </div>
                <div class="card-body p-0">
                    @if($article->stocks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Dépôt</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-center">État</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($article->stocks as $stock)
                                        <tr>
                                            <td>
                                                <i class="fas fa-warehouse text-primary"></i>
                                                {{ $stock->depot->nom }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary fs-6">{{ number_format($stock->quantite, 2, ',', ' ') }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($stock->quantite > 0)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i> En stock
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle"></i> Vide
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-secondary opacity-50 mb-3"></i>
                            <h6 class="text-secondary">Aucun stock pour cet article</h6>
                            <p class="text-muted">L'article n'est présent dans aucun dépôt</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Stock Total</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ number_format($article->stocks->sum('quantite'), 0) }}</h2>
                    <small class="text-muted">Unités en stock</small>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h6 class="mb-0"><i class="fas fa-warehouse"></i> Présent dans</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $article->stocks->count() }}</h2>
                    <small class="text-muted">Dépôt(s)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
}

.badge {
    padding: 0.5rem 0.75rem;
}
</style>
@endsection
