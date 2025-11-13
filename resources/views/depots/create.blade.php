@extends('layouts.main')

@section('title', 'Nouveau Depot')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-building-fill"></i> Nouveau Depot</h2>
    <a href="{{ route('depots.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="bi bi-plus-circle"></i> Informations du Depot</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('depots.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">
                                <i class="bi bi-building"></i> Nom du Depot <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}" 
                                   required
                                   placeholder="Ex: Depot Paris">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label">
                                <i class="bi bi-pin-map"></i> Ville
                            </label>
                            <input type="text" 
                                   class="form-control @error('ville') is-invalid @enderror" 
                                   id="ville" 
                                   name="ville" 
                                   value="{{ old('ville') }}"
                                   placeholder="Ex: Paris">
                            @error('ville')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="responsable" class="form-label">
                                <i class="bi bi-person-badge"></i> Responsable
                            </label>
                            <input type="text" 
                                   class="form-control @error('responsable') is-invalid @enderror" 
                                   id="responsable" 
                                   name="responsable" 
                                   value="{{ old('responsable') }}"
                                   placeholder="Ex: Jean Dupont">
                            @error('responsable')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">
                                <i class="bi bi-telephone"></i> Telephone
                            </label>
                            <input type="text" 
                                   class="form-control @error('telephone') is-invalid @enderror" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="{{ old('telephone') }}"
                                   placeholder="Ex: +33 1 45 67 89 01">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="adresse" class="form-label">
                            <i class="bi bi-geo-alt"></i> Adresse
                        </label>
                        <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                  id="adresse" 
                                  name="adresse" 
                                  rows="3"
                                  placeholder="Ex: 123 Rue de la Paix, 75001 Paris">{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('depots.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Enregistrer le Depot
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
}

.form-label {
    font-weight: 600;
    color: #374151;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}
</style>
@endsection
