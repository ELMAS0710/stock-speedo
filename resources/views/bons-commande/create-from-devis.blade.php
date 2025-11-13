@extends('layouts.main')

@section('title', 'Créer un Bon de Commande depuis le Devis ' . $devis->numero)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-file-invoice text-primary"></i> Créer un Bon de Commande
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('devis.index') }}">Devis</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('devis.show', $devis) }}">{{ $devis->numero }}</a></li>
                    <li class="breadcrumb-item active">Créer BC</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('devis.show', $devis) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour au devis
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations du devis -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Informations du Devis</h5>
                </div>
                <div class="card-body">
                    <p><strong>Numéro:</strong> {{ $devis->numero }}</p>
                    <p><strong>Client:</strong> {{ $devis->client->nom }}</p>
                    <p><strong>Date:</strong> {{ $devis->date_devis->format('d/m/Y') }}</p>
                    <p><strong>Statut:</strong> <span class="badge bg-success">Validé</span></p>
                    <p class="mb-0"><strong>Total:</strong> {{ number_format($devis->lignes->sum(function($l) { return $l->quantite * $l->prix_unitaire; }), 2, ',', ' ') }} FCFA</p>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Articles</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th class="text-end">Qté</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devis->lignes as $ligne)
                                    <tr>
                                        <td>{{ $ligne->article->nom }}</td>
                                        <td class="text-end">{{ number_format($ligne->quantite, 0, ',', ' ') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de création -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Créer le Bon de Commande</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('bons-commande.store-from-devis', $devis) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="depot_id" class="form-label">
                                <i class="fas fa-warehouse text-primary"></i> Dépôt <span class="text-danger">*</span>
                            </label>
                            <select name="depot_id" id="depot_id" class="form-select @error('depot_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un dépôt --</option>
                                @foreach($depots as $depot)
                                    <option value="{{ $depot->id }}" {{ old('depot_id') == $depot->id ? 'selected' : '' }}>
                                        {{ $depot->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('depot_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Dépôt où seront prélevés les articles
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="date_commande" class="form-label">
                                <i class="fas fa-calendar text-primary"></i> Date de commande <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="date_commande" 
                                   id="date_commande" 
                                   class="form-control @error('date_commande') is-invalid @enderror" 
                                   value="{{ old('date_commande', date('Y-m-d')) }}" 
                                   required>
                            @error('date_commande')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note text-primary"></i> Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="Notes ou instructions particulières...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information:</strong> Le bon de commande sera créé avec le statut "En attente" et devra être validé pour mettre à jour le stock.
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('devis.show', $devis) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Créer le Bon de Commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
