@extends('layouts.main')

@section('title', 'Modifier Article')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pencil"></i> Modifier Article</h2>
    <a href="{{ route('articles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('articles.update', $article) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="reference" class="form-label">Référence <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('reference') is-invalid @enderror" 
                           id="reference" name="reference" value="{{ old('reference', $article->reference) }}" required>
                    @error('reference')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                           id="nom" name="nom" value="{{ old('nom', $article->nom) }}" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="code_barre" class="form-label">Code Barre</label>
                    <input type="text" class="form-control @error('code_barre') is-invalid @enderror" 
                           id="code_barre" name="code_barre" value="{{ old('code_barre', $article->code_barre) }}">
                    @error('code_barre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="famille_id" class="form-label">Famille <span class="text-danger">*</span></label>
                    <select class="form-select @error('famille_id') is-invalid @enderror" 
                            id="famille_id" name="famille_id" required>
                        <option value="">Sélectionner une famille</option>
                        @foreach($familles as $famille)
                            <option value="{{ $famille->id }}" {{ old('famille_id') == $famille->id ? 'selected' : '' }}>
                                {{ $famille->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('famille_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="unite" class="form-label">Unité <span class="text-danger">*</span></label>
                    <select class="form-select @error('unite') is-invalid @enderror" 
                            id="unite" name="unite" required>
                        <option value="Pièce" {{ old('unite', 'Pièce') == 'Pièce' ? 'selected' : '' }}>Pièce</option>
                        <option value="Kg" {{ old('unite') == 'Kg' ? 'selected' : '' }}>Kg</option>
                        <option value="Litre" {{ old('unite') == 'Litre' ? 'selected' : '' }}>Litre</option>
                        <option value="Mètre" {{ old('unite') == 'Mètre' ? 'selected' : '' }}>Mètre</option>
                        <option value="Carton" {{ old('unite') == 'Carton' ? 'selected' : '' }}>Carton</option>
                    </select>
                    @error('unite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="is_active" class="form-label">Statut</label>
                    <select class="form-select @error('is_active') is-invalid @enderror" 
                            id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

