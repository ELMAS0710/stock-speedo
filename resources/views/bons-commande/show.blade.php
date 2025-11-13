@extends('layouts.main')

@section('title', 'Bon de Commande ' . $bonCommande->reference)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cart-check"></i> Bon de Commande {{ $bonCommande->reference }}</h2>
    <div>
        <a href="{{ route('bons-commande.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <a href="{{ route('bons-commande.pdf', $bonCommande) }}" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> PDF
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
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations Client</h5>
            </div>
            <div class="card-body">
                <p><strong>Client:</strong> {{ $bonCommande->nom_client }} {{ $bonCommande->prenom_client }}</p>
                <p><strong>Date Commande:</strong> {{ $bonCommande->date_commande->format('d/m/Y') }}</p>
                <p><strong>Dépôt:</strong> {{ $bonCommande->depot->nom }}</p>
                <p>
                    <strong>Statut:</strong>
                    @if($bonCommande->statut === 'en_attente')
                        <span class="badge bg-warning">En Attente</span>
                    @elseif($bonCommande->statut === 'validee')
                        <span class="badge bg-success">Validée</span>
                    @elseif($bonCommande->statut === 'livree')
                        <span class="badge bg-info">Livrée</span>
                    @else
                        <span class="badge bg-danger">Annulée</span>
                    @endif
                </p>
                @if($bonCommande->devis)
                    <p><strong>Devis:</strong> 
                        <a href="{{ route('devis.show', $bonCommande->devis) }}">{{ $bonCommande->devis->reference }}</a>
                    </p>
                @endif
                @if($bonCommande->reference_marche)
                    <p><strong>Référence Marché:</strong> <span class="badge bg-primary">{{ $bonCommande->reference_marche }}</span></p>
                @endif
                @if($bonCommande->notes)
                    <p><strong>Notes:</strong><br>{{ $bonCommande->notes }}</p>
                @endif
                <p class="text-muted mb-0"><small>Créé par {{ $bonCommande->createdBy->name }} le {{ $bonCommande->created_at->format('d/m/Y H:i') }}</small></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                @if($bonCommande->statut === 'en_attente')
                    <form method="POST" action="{{ route('bons-commande.valider', $bonCommande) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Confirmer la validation? Le stock sera mis à jour.')">
                            <i class="bi bi-check-circle"></i> Valider et Impacter Stock
                        </button>
                    </form>
                @endif

                @if($bonCommande->statut === 'validee' && !$bonCommande->bonLivraison)
                    <a href="{{ route('bons-livraison.from-bc', $bonCommande) }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-truck"></i> Générer Bon de Livraison
                    </a>
                @endif

                @if($bonCommande->bonLivraison)
                    <a href="{{ route('bons-livraison.show', $bonCommande->bonLivraison) }}" class="btn btn-info w-100">
                        <i class="bi bi-eye"></i> Voir Bon de Livraison
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Articles Commandés</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Total HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bonCommande->lignes as $ligne)
                                <tr>
                                    <td>
                                        <strong>{{ $ligne->article->reference }}</strong><br>
                                        <small>{{ $ligne->article->nom }}</small>
                                    </td>
                                    <td>{{ $ligne->quantite }}</td>
                                    <td>{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} FCFA</td>
                                    <td><strong>{{ number_format($ligne->total_ht, 2, ',', ' ') }} FCFA</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total HT:</th>
                                <th>{{ number_format($bonCommande->montant_ht, 2, ',', ' ') }} FCFA</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">TVA:</th>
                                <th>{{ number_format($bonCommande->montant_tva, 2, ',', ' ') }} FCFA</th>
                            </tr>
                            <tr class="table-primary">
                                <th colspan="3" class="text-end">Total TTC:</th>
                                <th>{{ number_format($bonCommande->montant_ttc, 2, ',', ' ') }} FCFA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
