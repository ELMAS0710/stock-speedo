

<?php $__env->startSection('title', 'Nouveau Transfert'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête avec breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-exchange-alt text-primary"></i> Nouveau Transfert
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><i class="fas fa-home"></i> Accueil</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('transferts.index')); ?>">Transferts</a></li>
                    <li class="breadcrumb-item active">Nouveau</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('transferts.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">Erreurs de validation</h5>
                    <p class="mb-2">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Instructions -->
    <div class="alert alert-info alert-dismissible fade show shadow-sm mb-4">
        <div class="d-flex align-items-start">
            <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2">Comment créer un transfert ?</h5>
                <ol class="mb-0 ps-3">
                    <li>Sélectionnez le <strong>dépôt source</strong> (d'où proviennent les articles)</li>
                    <li>Sélectionnez le <strong>dépôt destination</strong> (où vont les articles)</li>
                    <li>Cliquez sur <span class="badge bg-success"><i class="fas fa-plus"></i> Ajouter Article</span> pour ajouter des lignes</li>
                    <li>Pour chaque ligne, choisissez l'article et la quantité à transférer</li>
                    <li>Les stocks disponibles s'affichent automatiquement</li>
                </ol>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>

    <form action="<?php echo e(route('transferts.store')); ?>" method="POST" id="transfertForm">
        <?php echo csrf_field(); ?>
        
        <div class="row">
            <!-- Informations générales -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Informations Générales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="reference" class="form-label fw-bold">
                                <i class="fas fa-barcode text-muted"></i> Référence <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" 
                                       class="form-control <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="reference" 
                                       name="reference" 
                                       value="<?php echo e(old('reference', 'TRF-' . date('YmdHis'))); ?>" 
                                       required
                                       placeholder="Ex: TRF-20250111-001">
                                <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-lightbulb"></i> Référence unique du transfert
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="date_transfert" class="form-label fw-bold">
                                <i class="fas fa-calendar text-muted"></i> Date <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                                <input type="date" 
                                       class="form-control <?php $__errorArgs = ['date_transfert'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="date_transfert" 
                                       name="date_transfert" 
                                       value="<?php echo e(old('date_transfert', date('Y-m-d'))); ?>" 
                                       required>
                                <?php $__errorArgs = ['date_transfert'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="statut" class="form-label fw-bold">
                                <i class="fas fa-flag text-muted"></i> Statut <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-tasks"></i>
                                </span>
                                <select class="form-select <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="statut" 
                                        name="statut" 
                                        required>
                                    <option value="en_attente" <?php echo e(old('statut', 'en_attente') === 'en_attente' ? 'selected' : ''); ?>>
                                        🟡 En Attente
                                    </option>
                                    <option value="en_cours" <?php echo e(old('statut') === 'en_cours' ? 'selected' : ''); ?>>
                                        🔵 En Cours
                                    </option>
                                </select>
                                <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dépôts -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-warehouse"></i> Sélection des Dépôts
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="depot_source_id" class="form-label fw-bold">
                                <i class="fas fa-arrow-circle-up text-danger"></i> Dépôt Source <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger bg-opacity-10">
                                    <i class="fas fa-warehouse text-danger"></i>
                                </span>
                                <select class="form-select <?php $__errorArgs = ['depot_source_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="depot_source_id" 
                                        name="depot_source_id" 
                                        required>
                                    <option value="">-- Choisir le dépôt source --</option>
                                    <?php $__currentLoopData = $depots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($depot->id); ?>" <?php echo e(old('depot_source_id') == $depot->id ? 'selected' : ''); ?>>
                                            📦 <?php echo e($depot->nom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['depot_source_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-arrow-up"></i> D'où proviennent les articles
                            </small>
                        </div>

                        <div class="text-center mb-3">
                            <i class="fas fa-arrow-down fa-2x text-primary"></i>
                        </div>

                        <div class="mb-3">
                            <label for="depot_destination_id" class="form-label fw-bold">
                                <i class="fas fa-arrow-circle-down text-success"></i> Dépôt Destination <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-success bg-opacity-10">
                                    <i class="fas fa-warehouse text-success"></i>
                                </span>
                                <select class="form-select <?php $__errorArgs = ['depot_destination_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="depot_destination_id" 
                                        name="depot_destination_id" 
                                        required>
                                    <option value="">-- Choisir le dépôt destination --</option>
                                    <?php $__currentLoopData = $depots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($depot->id); ?>" <?php echo e(old('depot_destination_id') == $depot->id ? 'selected' : ''); ?>>
                                            📦 <?php echo e($depot->nom); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['depot_destination_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-arrow-down"></i> Où vont les articles
                            </small>
                        </div>

                        <div class="alert alert-warning mb-0 py-2">
                            <small>
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Important :</strong> Les dépôts source et destination doivent être différents
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-sticky-note text-warning"></i> Notes et Commentaires
                    <small class="text-muted">(Optionnel)</small>
                </h5>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-comment-alt"></i>
                    </span>
                    <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              id="notes" 
                              name="notes" 
                              rows="3" 
                              placeholder="Ajoutez des notes ou commentaires sur ce transfert (motif, observations, etc.)..."><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Articles -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center" 
                 style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="mb-0">
                    <i class="fas fa-boxes"></i> Articles à Transférer
                    <span class="badge bg-white text-dark ms-2" id="compteurLignes">0 article(s)</span>
                </h5>
                <button type="button" class="btn btn-light btn-sm shadow-sm" id="addLigne">
                    <i class="fas fa-plus-circle"></i> Ajouter un Article
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0" id="lignesTable">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="35%">
                                    <i class="fas fa-box"></i> Article
                                </th>
                                <th width="15%" class="text-center">
                                    <i class="fas fa-cubes text-danger"></i> Stock Source
                                </th>
                                <th width="15%" class="text-center">
                                    <i class="fas fa-sort-numeric-up"></i> Quantité
                                </th>
                                <th width="15%" class="text-center">
                                    <i class="fas fa-cubes text-success"></i> Stock Destination
                                </th>
                                <th width="10%" class="text-center">
                                    <i class="fas fa-cog"></i> Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="lignesContainer">
                            <?php if(old('lignes')): ?>
                                <?php $__currentLoopData = old('lignes'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="ligne-row" data-index="<?php echo e($index); ?>">
                                        <td>
                                            <select class="form-select form-select-sm article-select" 
                                                    name="lignes[<?php echo e($index); ?>][article_id]" 
                                                    required>
                                                <option value="">-- Sélectionner --</option>
                                                <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($article->id); ?>" 
                                                            data-reference="<?php echo e($article->reference); ?>"
                                                            <?php echo e($ligne['article_id'] == $article->id ? 'selected' : ''); ?>>
                                                        <?php echo e($article->nom); ?> (<?php echo e($article->reference); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm stock-source" readonly>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control form-control-sm quantite-input" 
                                                   name="lignes[<?php echo e($index); ?>][quantite]" 
                                                   value="<?php echo e($ligne['quantite']); ?>"
                                                   min="1" 
                                                   required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm stock-dest" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-ligne">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr id="emptyState">
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-inbox fa-4x mb-3 text-secondary opacity-50"></i>
                                        <h5 class="text-secondary">Aucun article ajouté</h5>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-info-circle"></i> Cliquez sur 
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-plus-circle"></i> Ajouter un Article
                                            </span> 
                                            pour commencer à constituer votre transfert.
                                        </p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?php echo e(route('transferts.index')); ?>" class="btn btn-lg btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i> Retour à la Liste
                    </a>
                    <div class="d-flex gap-3">
                        <button type="reset" class="btn btn-lg btn-outline-warning px-4">
                            <i class="fas fa-redo me-2"></i> Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-lg btn-primary px-5 shadow">
                            <i class="fas fa-save me-2"></i> Enregistrer le Transfert
                        </button>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Assurez-vous d'avoir ajouté au moins un article avant d'enregistrer
                    </small>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let ligneIndex = <?php echo e(old('lignes') ? count(old('lignes')) : 0); ?>;

// Ajouter une nouvelle ligne
document.getElementById('addLigne').addEventListener('click', function() {
    const container = document.getElementById('lignesContainer');
    
    // Supprimer le message "Aucun article" si présent
    const emptyRow = container.querySelector('tr td[colspan="5"]');
    if (emptyRow) {
        emptyRow.closest('tr').remove();
    }
    
    const newRow = document.createElement('tr');
    newRow.className = 'ligne-row';
    newRow.dataset.index = ligneIndex;
    newRow.innerHTML = `
        <td class="text-center align-middle">
            <span class="badge bg-secondary">${ligneIndex + 1}</span>
        </td>
        <td>
            <select class="form-select form-select-sm article-select" name="lignes[${ligneIndex}][article_id]" required>
                <option value="">-- Sélectionner un article --</option>
                <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($article->id); ?>" data-reference="<?php echo e($article->reference); ?>">
                        📦 <?php echo e($article->nom); ?> (<?php echo e($article->reference); ?>)
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
        <td class="text-center align-middle">
            <input type="text" class="form-control form-control-sm text-center stock-source" readonly>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" class="form-control text-center quantite-input" 
                       name="lignes[${ligneIndex}][quantite]" min="1" value="1" required>
                <span class="input-group-text bg-light">
                    <i class="fas fa-hashtag"></i>
                </span>
            </div>
        </td>
        <td class="text-center align-middle">
            <input type="text" class="form-control form-control-sm text-center stock-dest" readonly>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-sm btn-danger remove-ligne shadow-sm" title="Supprimer cette ligne">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    `;
    
    container.appendChild(newRow);
    ligneIndex++;
    
    // Mettre à jour le compteur
    updateCompteur();
    
    // Attacher les événements
    attachRowEvents(newRow);
});

// Supprimer une ligne
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-ligne')) {
        const row = e.target.closest('tr');
        row.remove();
        
        // Mettre à jour le compteur
        updateCompteur();
        
        // Afficher le message si plus aucune ligne
        const container = document.getElementById('lignesContainer');
        if (container.children.length === 0) {
            container.innerHTML = `
                <tr id="emptyState">
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-inbox fa-4x mb-3 text-secondary opacity-50"></i>
                        <h5 class="text-secondary">Aucun article ajouté</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle"></i> Cliquez sur 
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-plus-circle"></i> Ajouter un Article
                            </span> 
                            pour commencer à constituer votre transfert.
                        </p>
                    </td>
                </tr>
            `;
        }
    }
});

// Fonction pour mettre à jour le compteur d'articles
function updateCompteur() {
    const container = document.getElementById('lignesContainer');
    const lignesCount = container.querySelectorAll('.ligne-row').length;
    const compteur = document.getElementById('compteurLignes');
    
    if (compteur) {
        compteur.textContent = `${lignesCount} article(s)`;
        
        // Changer la couleur selon le nombre
        if (lignesCount === 0) {
            compteur.className = 'badge bg-white text-dark ms-2';
        } else if (lignesCount < 3) {
            compteur.className = 'badge bg-warning text-dark ms-2';
        } else {
            compteur.className = 'badge bg-success text-white ms-2';
        }
    }
}

// Mettre à jour les stocks quand on change l'article ou le dépôt
function updateStocks(row) {
    const articleId = row.querySelector('.article-select').value;
    const depotSourceId = document.getElementById('depot_source_id').value;
    const depotDestId = document.getElementById('depot_destination_id').value;
    
    // Réinitialiser les champs si l'article n'est pas sélectionné
    if (!articleId) {
        row.querySelector('.stock-source').value = '';
        row.querySelector('.stock-dest').value = '';
        return;
    }
    
    // Récupérer le stock source
    if (depotSourceId) {
        fetchStock(articleId, depotSourceId, row.querySelector('.stock-source'), 'source');
    } else {
        row.querySelector('.stock-source').value = '';
    }
    
    // Récupérer le stock destination
    if (depotDestId) {
        fetchStock(articleId, depotDestId, row.querySelector('.stock-dest'), 'destination');
    } else {
        row.querySelector('.stock-dest').value = '';
    }
}

function fetchStock(articleId, depotId, inputElement, type) {
    // Afficher un loader
    inputElement.value = 'Chargement...';
    inputElement.classList.remove('bg-danger-subtle', 'bg-warning-subtle', 'bg-success-subtle');
    
    fetch(`/api/stock/${articleId}/${depotId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            const quantite = data.quantite || 0;
            inputElement.value = quantite;
            
            // Appliquer des couleurs selon le stock
            inputElement.classList.remove('bg-danger-subtle', 'bg-warning-subtle', 'bg-success-subtle');
            
            if (type === 'source') {
                // Pour le stock source : rouge si insuffisant
                if (quantite === 0) {
                    inputElement.classList.add('bg-danger-subtle');
                    inputElement.title = 'Stock épuisé - Transfert impossible';
                } else if (quantite > 0 && quantite <= (data.stock_min || 0)) {
                    inputElement.classList.add('bg-warning-subtle');
                    inputElement.title = `Stock faible (Min: ${data.stock_min || 0})`;
                } else {
                    inputElement.classList.add('bg-success-subtle');
                    inputElement.title = `Stock disponible: ${quantite} ${data.unite || ''}`;
                }
            } else {
                // Pour le stock destination : juste informatif
                inputElement.title = `Stock actuel: ${quantite} ${data.unite || ''}`;
                if (quantite === 0) {
                    inputElement.classList.add('bg-info');
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération du stock:', error);
            inputElement.value = 'Erreur';
            inputElement.classList.add('bg-danger-subtle');
            inputElement.title = 'Impossible de récupérer le stock';
        });
}

function attachRowEvents(row) {
    row.querySelector('.article-select').addEventListener('change', () => updateStocks(row));
}

// Événements dépôts
document.getElementById('depot_source_id').addEventListener('change', function() {
    document.querySelectorAll('.ligne-row').forEach(updateStocks);
});

document.getElementById('depot_destination_id').addEventListener('change', function() {
    document.querySelectorAll('.ligne-row').forEach(updateStocks);
    
    // Vérifier que source != destination
    const sourceId = document.getElementById('depot_source_id').value;
    const destId = this.value;
    
    if (sourceId && destId && sourceId === destId) {
        alert('Le dépôt source et destination doivent être différents!');
        this.value = '';
    }
});

// Initialiser les lignes existantes
document.querySelectorAll('.ligne-row').forEach(row => {
    attachRowEvents(row);
    updateStocks(row);
});

// Validation avant soumission
document.getElementById('transfertForm').addEventListener('submit', function(e) {
    const lignes = document.querySelectorAll('.ligne-row');
    
    if (lignes.length === 0) {
        e.preventDefault();
        alert('Veuillez ajouter au moins un article au transfert.');
        return false;
    }
    
    const depotSource = document.getElementById('depot_source_id').value;
    const depotDest = document.getElementById('depot_destination_id').value;
    
    if (!depotSource || !depotDest) {
        e.preventDefault();
        alert('Veuillez sélectionner le dépôt source et destination.');
        return false;
    }
    
    if (depotSource === depotDest) {
        e.preventDefault();
        alert('Le dépôt source et destination doivent être différents!');
        return false;
    }
    
    // Vérifier que tous les stocks sources sont suffisants
    let stockInsuffisant = false;
    let messageErreur = '';
    
    lignes.forEach((row, index) => {
        const articleSelect = row.querySelector('.article-select');
        const quantiteInput = row.querySelector('.quantite-input');
        const stockSourceInput = row.querySelector('.stock-source');
        
        const articleNom = articleSelect.options[articleSelect.selectedIndex]?.text || 'Article';
        const quantiteDemandee = parseFloat(quantiteInput.value) || 0;
        const stockDisponible = parseFloat(stockSourceInput.value) || 0;
        
        if (quantiteDemandee > stockDisponible) {
            stockInsuffisant = true;
            messageErreur += `\n- ${articleNom}: Stock disponible ${stockDisponible}, demandé ${quantiteDemandee}`;
        }
    });
    
    if (stockInsuffisant) {
        e.preventDefault();
        
        // Afficher une alerte stylisée au lieu du simple alert
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <h5 class="alert-heading">
                    <i class="fas fa-exclamation-triangle"></i> Stock Insuffisant !
                </h5>
                <p class="mb-2"><strong>Les articles suivants ont un stock insuffisant :</strong></p>
                <ul class="mb-0">
                    ${messageErreur.split('\n').filter(m => m.trim()).map(m => `<li>${m.replace('-', '').trim()}</li>`).join('')}
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insérer l'alerte en haut du formulaire
        const form = document.querySelector('form');
        const existingAlert = form.querySelector('.alert-danger');
        if (existingAlert) existingAlert.remove();
        
        form.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Scroll vers le haut pour voir l'alerte
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        return false;
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/transferts/create.blade.php ENDPATH**/ ?>