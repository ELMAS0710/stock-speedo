@extends('layouts.main')

@section('title', 'Créer Bon de Livraison depuis Bon de Commande')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-truck"></i> Créer Bon de Livraison</h2>
    <a href="{{ route('bons-commande.show', $bonCommande) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour au BC
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations du Bon de Livraison</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('bons-livraison.store-from-bc', $bonCommande) }}">
                    @csrf

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Note:</strong> Le stock a déjà été impacté lors de la validation du bon de commande.
                    </div>

                    <div class="mb-3">
                        <label for="date_livraison" class="form-label">Date Livraison <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_livraison') is-invalid @enderror" 
                               id="date_livraison" name="date_livraison" 
                               value="{{ old('date_livraison', date('Y-m-d')) }}" required>
                        @error('date_livraison')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Créer le Bon de Livraison
                        </button>
                        <a href="{{ route('bons-commande.show', $bonCommande) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">BC {{ $bonCommande->reference }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Client:</strong> {{ $bonCommande->nom_client }} {{ $bonCommande->prenom_client }}</p>
                <p><strong>Dépôt:</strong> {{ $bonCommande->depot->nom }}</p>
                <p><strong>Date Commande:</strong> {{ $bonCommande->date_commande->format('d/m/Y') }}</p>
                <p><strong>Montant TTC:</strong> {{ number_format($bonCommande->montant_ttc, 2, ',', ' ') }} FCFA</p>
                <hr>
                <h6>Articles ({{ $bonCommande->lignes->count() }})</h6>
                <ul class="list-unstyled">
                    @foreach($bonCommande->lignes as $ligne)
                        <li>
                            <small>
                                <strong>{{ $ligne->article->nom }}</strong><br>
                                Qté: {{ $ligne->quantite }}
                            </small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
