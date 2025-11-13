@extends('layouts.main')

@section('title', 'Nouveau Bon de Livraison')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-file-earmark-check"></i> Nouveau Bon de Livraison</h2>
        <a href="{{ route('bons-livraison.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('bons-livraison.store') }}" method="POST" id="blForm">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations Générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                <select class="form-select @error('client_id') is-invalid @enderror" 
                                        id="client_id" name="client_id" required>
                                    <option value="">Sélectionner un client</option>
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

                            <div class="col-md-6">
                                <label for="depot_id" class="form-label">Dépôt <span class="text-danger">*</span></label>
                                <select class="form-select @error('depot_id') is-invalid @enderror" 
                                        id="depot_id" name="depot_id" required>
                                    <option value="">Sélectionner un dépôt</option>
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

                            <div class="col-md-6">
                                <label for="date_livraison" class="form-label">Date de Livraison <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_livraison') is-invalid @enderror" 
                                       id="date_livraison" name="date_livraison" 
                                       value="{{ old('date_livraison', date('Y-m-d')) }}" required>
                                @error('date_livraison')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="bon_commande_id" class="form-label">Bon de Commande (optionnel)</label>
                                <select class="form-select @error('bon_commande_id') is-invalid @enderror" 
                                        id="bon_commande_id" name="bon_commande_id">
                                    <option value="">Aucun</option>
                                    @foreach($bonsCommande as $bc)
                                        <option value="{{ $bc->id }}" {{ old('bon_commande_id') == $bc->id ? 'selected' : '' }}>
                                            {{ $bc->reference }} - {{ $bc->client->nom ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bon_commande_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Lignes du Bon</h5>
                        <button type="button" class="btn btn-light btn-sm" onclick="ajouterLigne()">
                            <i class="bi bi-plus-circle"></i> Ajouter une ligne
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="lignesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Article</th>
                                        <th style="width: 15%">Quantité</th>
                                        <th style="width: 20%">Prix Unitaire</th>
                                        <th style="width: 20%">Total</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="lignesBody">
                                    <!-- Les lignes seront ajoutées ici par JavaScript -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total HT</th>
                                        <th><span id="totalHT">0.00 DH</span></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-calculator"></i> Résumé</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Nombre de lignes</label>
                            <h4 id="nbLignes">0</h4>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Quantité totale</label>
                            <h4 id="qteTotal">0</h4>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="text-muted small">Total HT</label>
                            <h3 class="text-primary" id="totalHTSummary">0.00 DH</h3>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Enregistrer le Bon
                        </button>
                        <a href="{{ route('bons-livraison.index') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let ligneIndex = 0;
const articles = @json(\App\Models\Article::select('id', 'nom', 'reference')->orderBy('nom')->get());

function ajouterLigne() {
    const tbody = document.getElementById('lignesBody');
    const tr = document.createElement('tr');
    tr.id = `ligne_${ligneIndex}`;
    tr.innerHTML = `
        <td>
            <select class="form-select form-select-sm" name="lignes[${ligneIndex}][article_id]" required onchange="calculerTotal()">
                <option value="">Sélectionner un article</option>
                ${articles.map(a => `<option value="${a.id}">${a.nom} (${a.reference})</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="number" step="0.01" class="form-control form-control-sm" 
                   name="lignes[${ligneIndex}][quantite]" value="1" min="0.01" required 
                   onchange="calculerLigne(${ligneIndex})">
        </td>
        <td>
            <input type="number" step="0.01" class="form-control form-control-sm" 
                   name="lignes[${ligneIndex}][prix_unitaire]" value="0" min="0" required 
                   onchange="calculerLigne(${ligneIndex})">
        </td>
        <td>
            <span id="total_${ligneIndex}" class="fw-bold">0.00 DH</span>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="supprimerLigne(${ligneIndex})">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    ligneIndex++;
    calculerTotal();
}

function supprimerLigne(index) {
    document.getElementById(`ligne_${index}`).remove();
    calculerTotal();
}

function calculerLigne(index) {
    const qte = parseFloat(document.querySelector(`input[name="lignes[${index}][quantite]"]`).value) || 0;
    const pu = parseFloat(document.querySelector(`input[name="lignes[${index}][prix_unitaire]"]`).value) || 0;
    const total = qte * pu;
    document.getElementById(`total_${index}`).textContent = total.toFixed(2) + ' DH';
    calculerTotal();
}

function calculerTotal() {
    const lignes = document.querySelectorAll('#lignesBody tr');
    let totalHT = 0;
    let qteTotal = 0;
    
    lignes.forEach((ligne, idx) => {
        const qteInput = ligne.querySelector('input[name*="[quantite]"]');
        const puInput = ligne.querySelector('input[name*="[prix_unitaire]"]');
        if (qteInput && puInput) {
            const qte = parseFloat(qteInput.value) || 0;
            const pu = parseFloat(puInput.value) || 0;
            totalHT += qte * pu;
            qteTotal += qte;
        }
    });
    
    document.getElementById('totalHT').textContent = totalHT.toFixed(2) + ' DH';
    document.getElementById('totalHTSummary').textContent = totalHT.toFixed(2) + ' DH';
    document.getElementById('nbLignes').textContent = lignes.length;
    document.getElementById('qteTotal').textContent = qteTotal.toFixed(2);
}

// Ajouter une ligne par défaut
document.addEventListener('DOMContentLoaded', function() {
    ajouterLigne();
});
</script>
@endpush
@endsection
