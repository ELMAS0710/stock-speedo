@extends('layouts.main')

@section('title', 'Détails Bon de Livraison')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-truck"></i> Bon de Livraison {{ $bonsLivraison->reference }}</h2>
    <div>
        @if($bonsLivraison->statut == 'en_preparation')
            <form action="{{ route('bons-livraison.livrer', $bonsLivraison) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Confirmer la livraison ?');">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Marquer comme Livré
                </button>
            </form>
        @endif
        <a href="{{ route('bons-livraison.pdf', $bonsLivraison) }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-pdf"></i> PDF
        </a>
        <a href="{{ route('bons-livraison.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
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

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Client:</th>
                        <td>
                            <a href="{{ route('clients.show', $bonsLivraison->client) }}">
                                {{ $bonsLivraison->client->nom }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Dépôt:</th>
                        <td>{{ $bonsLivraison->depot->nom }}</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $bonsLivraison->date_livraison->format('d/m/Y') }}</td>
                    </tr>
                    @if($bonsLivraison->date_livraison_effective)
                        <tr>
                            <th>Livré le:</th>
                            <td>{{ $bonsLivraison->date_livraison_effective->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Statut:</th>
                        <td>
                            @if($bonsLivraison->statut == 'en_preparation')
                                <span class="badge bg-warning">En préparation</span>
                            @elseif($bonsLivraison->statut == 'livre')
                                <span class="badge bg-success">Livré</span>
                            @else
                                <span class="badge bg-danger">Annulé</span>
                            @endif
                        </td>
                    </tr>
                    @if($bonsLivraison->reference_marche)
                    <tr>
                        <th>Référence Marché:</th>
                        <td><span class="badge bg-info">{{ $bonsLivraison->reference_marche }}</span></td>
                    </tr>
                    @endif
                    @if($bonsLivraison->devis)
                        <tr>
                            <th>Devis:</th>
                            <td>
                                <a href="{{ route('devis.show', $bonsLivraison->devis) }}">
                                    {{ $bonsLivraison->devis->reference }}
                                </a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lignes du Bon</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Quantité</th>
                                                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bonsLivraison->lignes as $ligne)
                                <tr>
                                    <td>
                                        <strong>{{ $ligne->article->reference }}</strong><br>
                                        <small>{{ $ligne->article->nom }}</small>
                                    </td>
                                    <td>{{ $ligne->quantite }} {{ $ligne->article->unite }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
