@extends('layouts.main')

@section('title', 'Détails Client')
@section('page-title', 'Détails Client')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="stat-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4><i class="bi bi-person me-2"></i>Informations du client</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Modifier
                        </a>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border-start border-primary border-4 ps-3">
                            <label class="text-muted small">Nom complet</label>
                            <h5 class="mb-0">{{ $client->nom }} {{ $client->prenom }}</h5>
                        </div>
                    </div>

                    @if($client->entreprise)
                    <div class="col-md-6">
                        <div class="border-start border-primary border-4 ps-3">
                            <label class="text-muted small">Entreprise</label>
                            <h5 class="mb-0">{{ $client->entreprise }}</h5>
                        </div>
                    </div>
                    @endif

                    @if($client->email)
                    <div class="col-md-6">
                        <div class="border-start border-info border-4 ps-3">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0"><i class="bi bi-envelope me-2"></i>{{ $client->email }}</p>
                        </div>
                    </div>
                    @endif

                    @if($client->telephone)
                    <div class="col-md-6">
                        <div class="border-start border-info border-4 ps-3">
                            <label class="text-muted small">Téléphone</label>
                            <p class="mb-0"><i class="bi bi-telephone me-2"></i>{{ $client->telephone }}</p>
                        </div>
                    </div>
                    @endif

                    @if($client->adresse)
                    <div class="col-md-6">
                        <div class="border-start border-success border-4 ps-3">
                            <label class="text-muted small">Adresse</label>
                            <p class="mb-0">{{ $client->adresse }}</p>
                        </div>
                    </div>
                    @endif

                    @if($client->ville)
                    <div class="col-md-6">
                        <div class="border-start border-success border-4 ps-3">
                            <label class="text-muted small">Ville</label>
                            <p class="mb-0">{{ $client->ville }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <div class="border-start border-secondary border-4 ps-3">
                            <label class="text-muted small">Statut</label>
                            <p class="mb-0">
                                @if($client->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($client->notes)
                    <div class="col-md-12">
                        <div class="border-start border-warning border-4 ps-3">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0">{{ $client->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Devis du client -->
            <div class="stat-card mb-4">
                <h5 class="mb-3"><i class="bi bi-file-earmark-text me-2"></i>Devis</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Date</th>
                                <th>Montant TTC</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->devis as $devis)
                            <tr>
                                <td><strong>{{ $devis->reference }}</strong></td>
                                <td>{{ $devis->date_devis->format('d/m/Y') }}</td>
                                <td>{{ number_format($devis->montant_ttc, 2, ',', ' ') }} €</td>
                                <td>
                                    @switch($devis->statut)
                                        @case('en_attente')
                                            <span class="badge bg-warning">En attente</span>
                                            @break
                                        @case('valide')
                                            <span class="badge bg-success">Validé</span>
                                            @break
                                        @case('refuse')
                                            <span class="badge bg-danger">Refusé</span>
                                            @break
                                        @case('expire')
                                            <span class="badge bg-secondary">Expiré</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ route('devis.show', $devis) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun devis</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Bons de Commande du client -->
            <div class="stat-card mb-4">
                <h5 class="mb-3"><i class="bi bi-cart-check me-2"></i>Bons de Commande</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Date</th>
                                <th>Montant TTC</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->bonsCommande as $bc)
                            <tr>
                                <td><strong>{{ $bc->reference }}</strong></td>
                                <td>{{ $bc->date_commande->format('d/m/Y') }}</td>
                                <td>{{ number_format($bc->montant_ttc, 2, ',', ' ') }} €</td>
                                <td>
                                    @switch($bc->statut)
                                        @case('en_attente')
                                            <span class="badge bg-warning">En attente</span>
                                            @break
                                        @case('validee')
                                            <span class="badge bg-success">Validée</span>
                                            @break
                                        @case('livree')
                                            <span class="badge bg-info">Livrée</span>
                                            @break
                                        @case('annulee')
                                            <span class="badge bg-danger">Annulée</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ route('bons-commande.show', $bc) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun bon de commande</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Bons de livraison du client -->
            <div class="stat-card">
                <h5 class="mb-3"><i class="bi bi-file-earmark-check me-2"></i>Bons de livraison</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Date</th>
                                <th>Dépôt</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->bonsLivraison as $bon)
                            <tr>
                                <td><strong>{{ $bon->reference }}</strong></td>
                                <td>{{ $bon->date_livraison->format('d/m/Y') }}</td>
                                <td>{{ $bon->depot->nom }}</td>
                                <td>
                                    @switch($bon->statut)
                                        @case('en_preparation')
                                            <span class="badge bg-warning">En préparation</span>
                                            @break
                                        @case('livre')
                                            <span class="badge bg-success">Livré</span>
                                            @break
                                        @case('annule')
                                            <span class="badge bg-danger">Annulé</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ route('bons-livraison.show', $bon) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun bon de livraison</td>
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
