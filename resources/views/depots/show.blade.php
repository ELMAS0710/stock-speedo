@extends('layouts.main')

@section('title', 'Depot - ' . $depot->nom)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-building-fill"></i> {{ $depot->nom }}</h2>
        <p class="text-muted mb-0"><i class="bi bi-geo-alt"></i> {{ $depot->adresse ?: 'Adresse non renseignee' }}</p>
    </div>
    <div>
        <a href="{{ route('depots.pdf', $depot) }}" class="btn btn-danger me-2">
            <i class="bi bi-file-pdf"></i> Telecharger PDF
        </a>
        <a href="{{ route('depots.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<!-- Carte statistique -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-4">
                <h1 class="display-4 mb-0">{{ $totalArticles }}</h1>
                <p class="mb-0 fs-5">Article(s) en stock dans ce depot</p>
            </div>
        </div>
    </div>
</div>

<!-- Stock du dépôt -->
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="card-title mb-0"><i class="bi bi-boxes"></i> Liste des articles en stock</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="40%">Article</th>
                        <th width="20%">Reference</th>
                        <th width="20%">Famille</th>
                        <th width="15%" class="text-center">Quantite</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $index => $stock)
                        <tr>
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $stock->article->nom }}</strong>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $stock->article->reference }}</code>
                            </td>
                            <td>
                                @if($stock->article->famille)
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-tag"></i> {{ $stock->article->famille->nom }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic">Non categorise</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info fs-6 px-3 py-2">
                                    {{ number_format($stock->quantite, 0, ',', ' ') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-0 fs-5">Aucun article en stock dans ce depot</p>
                                    <p class="text-muted small">Les articles seront affiches ici une fois ajoutes</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($stocks->count() > 0)
    <div class="card-footer bg-light text-muted">
        <i class="bi bi-info-circle"></i> Total : <strong>{{ $totalArticles }}</strong> article(s) reference(s) dans ce depot
    </div>
    @endif
</div>

<style>
.card {
    border-radius: 10px;
    overflow: hidden;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

code {
    font-size: 0.9em;
    font-weight: 500;
}

.badge {
    font-weight: 500;
}
</style>
@endsection

