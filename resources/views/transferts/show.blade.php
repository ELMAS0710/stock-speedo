@extends('layouts.main')

@section('title', 'Transfert #' . $transfert->reference)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Transfert #{{ $transfert->reference }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transferts.index') }}">Transferts</a></li>
                    <li class="breadcrumb-item active">{{ $transfert->reference }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($transfert->statut === 'en_attente')
                <form action="{{ route('transferts.executer', $transfert) }}" method="POST" class="d-inline" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir exécuter ce transfert ? Les stocks seront mis à jour.')">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Exécuter
                    </button>
                </form>
            @endif
            
            @if(in_array($transfert->statut, ['en_attente', 'en_cours']))
                <a href="{{ route('transferts.edit', $transfert) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            @endif
            
            <a href="{{ route('transferts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations générales -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Générales</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Référence:</th>
                            <td><strong>{{ $transfert->reference }}</strong></td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $transfert->date_transfert->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Statut:</th>
                            <td>
                                @if($transfert->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark">En Attente</span>
                                @elseif($transfert->statut === 'en_cours')
                                    <span class="badge bg-info">En Cours</span>
                                @elseif($transfert->statut === 'termine')
                                    <span class="badge bg-success">Terminé</span>
                                @elseif($transfert->statut === 'annule')
                                    <span class="badge bg-danger">Annulé</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($transfert->statut) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Créé par:</th>
                            <td>{{ $transfert->createdBy->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Créé le:</th>
                            <td>{{ $transfert->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informations dépôts -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-warehouse"></i> Dépôts</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Dépôt Source:</th>
                            <td>
                                <a href="{{ route('depots.show', $transfert->depotSource) }}">
                                    {{ $transfert->depotSource->nom }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Adresse Source:</th>
                            <td>{{ $transfert->depotSource->adresse ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Dépôt Destination:</th>
                            <td>
                                <a href="{{ route('depots.show', $transfert->depotDestination) }}">
                                    {{ $transfert->depotDestination->nom }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Adresse Destination:</th>
                            <td>{{ $transfert->depotDestination->adresse ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($transfert->notes)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notes</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $transfert->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Lignes du transfert -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Articles Transférés</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th>Référence</th>
                            <th width="120" class="text-center">Quantité</th>
                            <th width="150">Stock Source</th>
                            <th width="150">Stock Destination</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfert->lignes as $ligne)
                        <tr>
                            <td>
                                @if($ligne->article)
                                    <a href="{{ route('articles.show', $ligne->article) }}">
                                        {{ $ligne->article->nom }}
                                    </a>
                                @else
                                    Article supprimé
                                @endif
                            </td>
                            <td>{{ $ligne->article->reference ?? 'N/A' }}</td>
                            <td class="text-center">
                                <strong>{{ $ligne->quantite }}</strong>
                            </td>
                            <td>
                                @php
                                    $stockSource = \App\Models\Stock::where('article_id', $ligne->article_id)
                                        ->where('depot_id', $transfert->depot_source_id)
                                        ->first();
                                @endphp
                                @if($stockSource)
                                    <span class="badge {{ $stockSource->quantite >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $stockSource->quantite }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">0</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $stockDest = \App\Models\Stock::where('article_id', $ligne->article_id)
                                        ->where('depot_id', $transfert->depot_destination_id)
                                        ->first();
                                @endphp
                                @if($stockDest)
                                    <span class="badge {{ $stockDest->quantite >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $stockDest->quantite }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">0</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun article</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($transfert->lignes->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2" class="text-end">Total Articles:</th>
                            <th class="text-center">{{ $transfert->lignes->count() }}</th>
                            <th colspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-end">Quantité Totale:</th>
                            <th class="text-center">{{ $transfert->lignes->sum('quantite') }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
