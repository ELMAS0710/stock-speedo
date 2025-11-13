@extends('layouts.main')

@section('title', 'Détails Devis')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-file-earmark-text"></i> Devis {{ $devis->reference }}</h2>
    <div>
        @if($devis->statut == 'brouillon')
            <form action="{{ route('devis.valider', $devis) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Valider
                </button>
            </form>
        @endif
        
        @if($devis->statut == 'valide' && !$devis->bonCommande)
            <a href="{{ route('bons-commande.from-devis', $devis) }}" class="btn btn-primary">
                <i class="bi bi-cart-check"></i> Créer Bon de Commande
            </a>
        @endif

        @if($devis->bonCommande)
            <a href="{{ route('bons-commande.show', $devis->bonCommande) }}" class="btn btn-info">
                <i class="bi bi-eye"></i> Voir Bon de Commande
            </a>
        @endif

        <a href="{{ route('devis.pdf', $devis) }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-pdf"></i> PDF
        </a>
        <a href="{{ route('devis.index') }}" class="btn btn-secondary">
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
                            <a href="{{ route('clients.show', $devis->client) }}">
                                {{ $devis->client->nom }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $devis->date_devis->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Validité:</th>
                        <td>{{ $devis->date_validite->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Statut:</th>
                        <td>
                            @if($devis->statut == 'brouillon')
                                <span class="badge bg-secondary">Brouillon</span>
                            @elseif($devis->statut == 'valide')
                                <span class="badge bg-success">Validé</span>
                            @else
                                <span class="badge bg-danger">Annulé</span>
                            @endif
                        </td>
                    </tr>
                    @if($devis->reference_marche)
                    <tr>
                        <th>Référence Marché:</th>
                        <td><span class="badge bg-primary">{{ $devis->reference_marche }}</span></td>
                    </tr>
                    @endif
                    <tr>
                        <th>Créé par:</th>
                        <td>{{ $devis->createdBy->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lignes du Devis</h5>
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
                            @foreach($devis->lignes as $ligne)
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
                                <th>{{ number_format($devis->montant_ht, 2, ',', ' ') }} FCFA</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">TVA:</th>
                                <th>{{ number_format($devis->montant_tva, 2, ',', ' ') }} FCFA</th>
                            </tr>
                            <tr class="table-primary">
                                <th colspan="3" class="text-end">Total TTC:</th>
                                <th>{{ number_format($devis->montant_ttc, 2, ',', ' ') }} FCFA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
