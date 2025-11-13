@extends('layouts.main')

@section('title', 'Modifier Client')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pencil"></i> Modifier Client</h2>
    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                           id="nom" name="nom" value="{{ old('nom', $client->nom) }}" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                           id="prenom" name="prenom" value="{{ old('prenom', $client->prenom) }}">
                    @error('prenom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $client->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                           id="telephone" name="telephone" value="{{ old('telephone', $client->telephone) }}">
                    @error('telephone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" class="form-control @error('adresse') is-invalid @enderror" 
                           id="adresse" name="adresse" value="{{ old('adresse', $client->adresse) }}">
                    @error('adresse')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" class="form-control @error('ville') is-invalid @enderror" 
                           id="ville" name="ville" value="{{ old('ville', $client->ville) }}">
                    @error('ville')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="code_postal" class="form-label">Code Postal</label>
                    <input type="text" class="form-control @error('code_postal') is-invalid @enderror" 
                           id="code_postal" name="code_postal" value="{{ old('code_postal', $client->code_postal) }}">
                    @error('code_postal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="is_active" class="form-label">Statut</label>
                    <select class="form-select @error('is_active') is-invalid @enderror" 
                            id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', $client->is_active) == 1 ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', $client->is_active) == 0 ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3">{{ old('notes', $client->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Mettre à jour
                </button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
