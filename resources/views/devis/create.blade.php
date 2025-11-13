@extends('layouts.main')

@section('title', 'Nouveau Devis')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-plus-circle"></i> Nouveau Devis</h2>
    <a href="{{ route('devis.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('devis.store') }}" id="devisForm">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                        <select class="form-select @error('client_id') is-invalid @enderror" 
                                id="client_id" name="client_id" required>
                            <option value="">-- Sélectionnez un client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="reference_marche" class="form-label">Référence Marché</label>
                        <input type="text" class="form-control @error('reference_marche') is-invalid @enderror"
                               id="reference_marche" name="reference_marche" value="{{ old('reference_marche') }}"
                               placeholder="Ex: MARCHE-2025-001">
                        @error('reference_marche')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
<div class="col-md-4">
                    <div class="mb-3">
                        <label for="date_devis" class="form-label">Date Devis <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_devis') is-invalid @enderror" 
                               id="date_devis" name="date_devis" value="{{ old('date_devis', date('Y-m-d')) }}" required>
                        @error('date_devis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="date_validite" class="form-label">Date Validité <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_validite') is-invalid @enderror" 
                               id="date_validite" name="date_validite" value="{{ old('date_validite', date('Y-m-d', strtotime('+30 days'))) }}" required>
                        @error('date_validite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <h5>Articles</h5>
            <div id="lignes">
                <div class="ligne-devis mb-3">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label">Article</label>
                            <select class="form-select article-select" name="lignes[0][article_id]" required>
                                <option value="">-- Sélectionnez un article --</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}" 
                                            data-prix="{{ $article->prix_vente }}"
                                            data-unite="{{ $article->unite }}">
                                        {{ $article->reference }} - {{ $article->nom }} ({{ number_format($article->prix_vente, 2) }} FCFA)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantité</label>
                            <input type="number" step="1" min="1" class="form-control quantite" 
                                   name="lignes[0][quantite]" placeholder="Qté" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Prix Unitaire</label>
                            <input type="number" step="0.01" class="form-control prix-unitaire" 
                                   name="lignes[0][prix_unitaire]" placeholder="Prix U." required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-remove-ligne d-block" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="btnAjouterLigne">
                <i class="bi bi-plus-circle"></i> Ajouter un article
            </button>

            <hr>

            <div class="mb-3">
                <label for="conditions" class="form-label">Conditions</label>
                <textarea class="form-control" id="conditions" name="conditions" rows="3">{{ old('conditions') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Créer le Devis
                </button>
                <a href="{{ route('devis.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let ligneIndex = 1;

$(document).on('change', '.article-select', function() {
    const prix = $(this).find(':selected').data('prix');
    $(this).closest('.ligne-devis').find('.prix-unitaire').val(prix);
});

$('#btnAjouterLigne').click(function() {
    const newLigne = `
        <div class="ligne-devis mb-3">
            <div class="row">
                <div class="col-md-5">
                    <select class="form-select article-select" name="lignes[${ligneIndex}][article_id]" required>
                        <option value="">-- Sélectionnez un article --</option>
                        @foreach($articles as $article)
                            <option value="{{ $article->id }}" 
                                    data-prix="{{ $article->prix_vente }}"
                                    data-unite="{{ $article->unite }}">
                                {{ $article->reference }} - {{ $article->nom }} ({{ number_format($article->prix_vente, 2) }} FCFA)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" step="1" min="1" class="form-control quantite" 
                           name="lignes[${ligneIndex}][quantite]" placeholder="Qté" required>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" class="form-control prix-unitaire" 
                           name="lignes[${ligneIndex}][prix_unitaire]" placeholder="Prix U." required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-remove-ligne">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#lignes').append(newLigne);
    ligneIndex++;
    updateRemoveButtons();
});

$(document).on('click', '.btn-remove-ligne', function() {
    $(this).closest('.ligne-devis').remove();
    updateRemoveButtons();
});

function updateRemoveButtons() {
    const count = $('.ligne-devis').length;
    $('.btn-remove-ligne').prop('disabled', count === 1);
}
</script>
@endsection
