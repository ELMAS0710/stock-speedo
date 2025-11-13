@extends('layouts.main')

@section('title', 'Depots')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-building"></i> Depots</h2>
    <a href="{{ route('depots.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Depot
    </a>
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

<!-- Statistiques des dépôts -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $depots->count() }}</h3>
                <p class="mb-0"><i class="bi bi-building"></i> Dépôts Total</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($totalStock, 0, ',', ' ') }}</h3>
                <p class="mb-0"><i class="bi bi-box-seam"></i> Quantité Totale en Stock</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $depots->where('stocks_count', '>', 0)->count() }}</h3>
                <p class="mb-0"><i class="bi bi-check-circle"></i> Dépôts Actifs</p>
            </div>
        </div>
    </div>
</div>

<h5 class="mb-3"><i class="bi bi-grid-3x3-gap"></i> Liste des Dépôts</h5>

<div class="row">
    @forelse($depots as $depot)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm depot-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-building-fill text-primary"></i> 
                            {{ $depot->nom }}
                        </h5>
                    </div>
                    
                    <p class="text-muted mb-2">
                        <i class="bi bi-geo-alt"></i> 
                        {{ $depot->adresse ?: 'Adresse non renseignée' }}
                    </p>
                    
                    <div class="mb-3">
                        <span class="badge bg-info fs-6">
                            <i class="bi bi-box-seam"></i> {{ $depot->stocks_count }} articles
                        </span>
                    </div>
                    
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('depots.show', $depot) }}" class="btn btn-sm btn-info" title="Voir">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                        <a href="{{ route('depots.pdf', $depot) }}" class="btn btn-sm btn-danger" title="PDF">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('depots.edit', $depot) }}" class="btn btn-sm btn-warning" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('depots.destroy', $depot) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce depot ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mb-3 mt-3">Aucun depot enregistre</p>
                    <a href="{{ route('depots.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Creer le premier depot
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<style>
.depot-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 10px;
}

.depot-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection

