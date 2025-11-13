@extends('layouts.main')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart-plus"></i> Creer Bon de Commande</h2>
        <a href="{{ route('bons-commande.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Erreurs de validation :</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('bons-commande.store') }}" method="POST">
        @csrf
        
        <!-- Informations du Bon de Commande -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="bi bi-info-circle"></i> Informations du Bon de Commande</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Attention:</strong> La validation du bon de commande impactera le stock du depot selectionne.
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">-- Selectionnez un client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom }} {{ $client->prenom }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="depot_id" class="form-label">Depot <span class="text-danger">*</span></label>
                        <select name="depot_id" id="depot_id" class="form-select @error('depot_id') is-invalid @enderror" required>
                            <option value="">-- Selectionnez un depot --</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}" {{ old('depot_id') == $depot->id ? 'selected' : '' }}>
                                    {{ $depot->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('depot_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                <div class="col-md-4">
                    <label for="date_commande" class="form-label">Date Commande <span class="text-danger">*</span></label>
                    <input type="date" name="date_commande" id="date_commande" 
                           class="form-control @error('date_commande') is-invalid @enderror" 
                           value="{{ old('date_commande', date('Y-m-d')) }}" required>
                    @error('date_commande')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                    <div class="col-md-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  placeholder="Informations complementaires...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="bi bi-box-seam"></i> Articles</h5>
                <button type="button" class="btn btn-sm btn-light" id="addArticleBtn">
                    <i class="bi bi-plus-circle"></i> Ajouter un article
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="articlesTable">
                        <thead class="table-light">
                            <tr>
                                <th width="40%">Article</th>
                                <th width="15%">Quantite</th>
                                <th width="15%">Prix Unitaire</th>
                                <th width="15%">TVA (%)</th>
                                <th width="10%">Total HT</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="articlesBody">
                            <tr class="text-center text-muted">
                                <td colspan="6">
                                    <i class="bi bi-inbox"></i> Aucun article ajoute. Cliquez sur "Ajouter un article" pour commencer.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end"><strong>Total HT:</strong></td>
                                <td colspan="2"><strong id="totalHT">0,00 FCFA</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="4" class="text-end"><strong>Total TVA:</strong></td>
                                <td colspan="2"><strong id="totalTVA">0,00 FCFA</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="4" class="text-end"><strong>Total TTC:</strong></td>
                                <td colspan="2"><strong class="text-primary" id="totalTTC">0,00 FCFA</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('bons-commande.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Enregistrer le Bon de Commande
            </button>
        </div>
    </form>
</div>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart-plus"></i> Creer Bon de Commande</h2>
        <a href="{{ route('bons-commande.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Erreurs de validation :</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('bons-commande.store') }}" method="POST">
        @csrf
        
        <!-- Informations du Bon de Commande -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="bi bi-info-circle"></i> Informations du Bon de Commande</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Attention:</strong> La validation du bon de commande impactera le stock du depot selectionne.
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">-- Selectionnez un client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom }} {{ $client->prenom }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="depot_id" class="form-label">Depot <span class="text-danger">*</span></label>
                        <select name="depot_id" id="depot_id" class="form-select @error('depot_id') is-invalid @enderror" required>
                            <option value="">-- Selectionnez un depot --</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}" {{ old('depot_id') == $depot->id ? 'selected' : '' }}>
                                    {{ $depot->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('depot_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="date_commande" class="form-label">Date Commande <span class="text-danger">*</span></label>
                        <input type="date" name="date_commande" id="date_commande" 
                               class="form-control @error('date_commande') is-invalid @enderror" 
                               value="{{ old('date_commande', date('Y-m-d')) }}" required>
                        @error('date_commande')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="form-control @error('notes') is-invalid @enderror" 
                                  placeholder="Informations complementaires...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="bi bi-box-seam"></i> Articles</h5>
                <button type="button" class="btn btn-sm btn-light" id="addArticleBtn">
                    <i class="bi bi-plus-circle"></i> Ajouter un article
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="articlesTable">
                        <thead class="table-light">
                            <tr>
                                <th width="40%">Article</th>
                                <th width="15%">Quantite</th>
                                <th width="15%">Prix Unitaire</th>
                                <th width="15%">TVA (%)</th>
                                <th width="10%">Total HT</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="articlesBody">
                            <tr class="text-center text-muted">
                                <td colspan="6">
                                    <i class="bi bi-inbox"></i> Aucun article ajoute. Cliquez sur "Ajouter un article" pour commencer.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end"><strong>Total HT:</strong></td>
                                <td colspan="2"><strong id="totalHT">0,00 FCFA</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="4" class="text-end"><strong>Total TVA:</strong></td>
                                <td colspan="2"><strong id="totalTVA">0,00 FCFA</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="4" class="text-end"><strong>Total TTC:</strong></td>
                                <td colspan="2"><strong class="text-primary" id="totalTTC">0,00 FCFA</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('bons-commande.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Enregistrer le Bon de Commande
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let articleCount = 0;
const articlesData = @json($articles);

// Ajouter une ligne d'article
document.getElementById('addArticleBtn').addEventListener('click', function() {
    const tbody = document.getElementById('articlesBody');
    
    // Supprimer le message "Aucun article"
    if (articleCount === 0) {
        tbody.innerHTML = '';
    }
    
    articleCount++;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="articles[${articleCount}][article_id]" class="form-select article-select" required>
                <option value="">-- Selectionnez un article --</option>
                ${articlesData.map(article => `
                    <option value="${article.id}" 
                            data-prix="${article.prix_vente}" 
                            data-tva="${article.taux_tva || 0}">
                        ${article.nom} (${article.reference})
                    </option>
                `).join('')}
            </select>
        </td>
        <td>
            <input type="number" name="articles[${articleCount}][quantite]" class="form-control article-quantite" 
                   step="0.01" min="0.01" placeholder="0.00" required>
        </td>
        <td>
            <input type="number" name="articles[${articleCount}][prix_unitaire]" class="form-control article-prix" 
                   step="0.01" min="0" placeholder="0.00" required>
        </td>
        <td>
            <input type="number" name="articles[${articleCount}][taux_tva]" class="form-control article-tva" 
                   step="0.01" min="0" max="100" value="0" placeholder="0">
        </td>
        <td class="article-total">0,00 FCFA</td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger remove-article">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Evenement pour remplir automatiquement le prix et TVA quand on selectionne un article
    const selectArticle = row.querySelector('.article-select');
    selectArticle.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const prix = selectedOption.getAttribute('data-prix');
        const tva = selectedOption.getAttribute('data-tva');
        
        if (prix) {
            row.querySelector('.article-prix').value = prix;
        }
        if (tva) {
            row.querySelector('.article-tva').value = tva;
        }
        calculateTotals();
    });
    
    // Ajouter les evenements pour le calcul
    row.querySelectorAll('.article-quantite, .article-prix, .article-tva').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
    
    row.querySelector('.remove-article').addEventListener('click', function() {
        row.remove();
        articleCount--;
        if (articleCount === 0) {
            document.getElementById('articlesBody').innerHTML = `
                <tr class="text-center text-muted">
                    <td colspan="6">
                        <i class="bi bi-inbox"></i> Aucun article ajoute. Cliquez sur "Ajouter un article" pour commencer.
                    </td>
                </tr>
            `;
        }
        calculateTotals();
    });
});

// Calculer les totaux
function calculateTotals() {
    let totalHT = 0;
    let totalTVA = 0;
    
    document.querySelectorAll('#articlesBody tr').forEach(row => {
        const quantite = parseFloat(row.querySelector('.article-quantite')?.value) || 0;
        const prix = parseFloat(row.querySelector('.article-prix')?.value) || 0;
        const tva = parseFloat(row.querySelector('.article-tva')?.value) || 0;
        
        const montantHT = quantite * prix;
        const montantTVA = montantHT * (tva / 100);
        
        if (row.querySelector('.article-total')) {
            row.querySelector('.article-total').textContent = montantHT.toFixed(2) + ' FCFA';
        }
        
        totalHT += montantHT;
        totalTVA += montantTVA;
    });
    
    const totalTTC = totalHT + totalTVA;
    
    document.getElementById('totalHT').textContent = totalHT.toFixed(2) + ' FCFA';
    document.getElementById('totalTVA').textContent = totalTVA.toFixed(2) + ' FCFA';
    document.getElementById('totalTTC').textContent = totalTTC.toFixed(2) + ' FCFA';
}
</script>
@endpush

<!-- Include Bootstrap Icons if not already included -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

