@extends('layouts.main')

@section('title', 'Modifier Famille')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pencil"></i> Modifier Famille</h2>
    <a href="{{ route('familles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('familles.update', $famille) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom', $famille->nom) }}" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $famille->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="is_active" class="form-label">Statut</label>
                <select class="form-select @error('is_active') is-invalid @enderror" 
                        id="is_active" name="is_active">
                    <option value="1" {{ old('is_active', $famille->is_active) == 1 ? 'selected' : '' }}>Actif</option>
                    <option value="0" {{ old('is_active', $famille->is_active) == 0 ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Mettre à jour
                </button>
                <a href="{{ route('familles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
