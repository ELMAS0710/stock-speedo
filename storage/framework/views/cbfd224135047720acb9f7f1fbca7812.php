<?php $__env->startSection('title', 'Mouvements de Stock'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-exchange-alt text-primary"></i> Mouvements de Stock
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('home')); ?>"><i class="fas fa-home"></i> Accueil</a>
                    </li>
                    <li class="breadcrumb-item active">Mouvements de Stock</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('stock.export-excel')); ?>" class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel"></i> Exporter Excel
            </a>
            <a href="<?php echo e(route('mouvements-stock.create')); ?>" class="btn btn-primary shadow">
                <i class="fas fa-plus-circle"></i> Nouveau Mouvement
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filtres -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="bi bi-funnel"></i> Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('mouvements-stock.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Type de mouvement</label>
                        <select name="type" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="entree" <?php echo e(request('type') == 'entree' ? 'selected' : ''); ?>>Entrée</option>
                            <option value="sortie" <?php echo e(request('type') == 'sortie' ? 'selected' : ''); ?>>Sortie</option>
                            <option value="transfert_entree" <?php echo e(request('type') == 'transfert_entree' ? 'selected' : ''); ?>>Transfert Entrée</option>
                            <option value="transfert_sortie" <?php echo e(request('type') == 'transfert_sortie' ? 'selected' : ''); ?>>Transfert Sortie</option>
                            <option value="ajustement" <?php echo e(request('type') == 'ajustement' ? 'selected' : ''); ?>>Ajustement</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Article</label>
                        <select name="article_id" class="form-select">
                            <option value="">Tous les articles</option>
                            <?php $__currentLoopData = \App\Models\Article::orderBy('nom')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($article->id); ?>" <?php echo e(request('article_id') == $article->id ? 'selected' : ''); ?>>
                                    <?php echo e($article->nom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dépôt</label>
                        <select name="depot_id" class="form-select">
                            <option value="">Tous les dépôts</option>
                            <?php $__currentLoopData = \App\Models\Depot::orderBy('nom')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($depot->id); ?>" <?php echo e(request('depot_id') == $depot->id ? 'selected' : ''); ?>>
                                    <?php echo e($depot->nom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Rechercher
                        </button>
                        <a href="<?php echo e(route('mouvements-stock.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Historique des Mouvements
                <span class="badge bg-primary ms-2"><?php echo e($mouvements->total()); ?> mouvement(s)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if($mouvements->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center"><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-tag"></i> Type</th>
                                <th><i class="fas fa-box"></i> Article</th>
                                <th><i class="fas fa-warehouse"></i> Dépôt</th>
                                <th class="text-center"><i class="fas fa-sort-numeric-up"></i> Quantité</th>
                                <th class="text-center"><i class="fas fa-boxes"></i> Stock Actuel</th>
                                <th><i class="fas fa-user"></i> Utilisateur</th>
                                <th><i class="fas fa-comment-dots"></i> Motif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $mouvements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mouvement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt"></i> <?php echo e($mouvement->created_at->format('d/m/Y')); ?><br>
                                            <i class="far fa-clock"></i> <?php echo e($mouvement->created_at->format('H:i')); ?>

                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <?php
                                            $isTransfert = $mouvement->reference_document && str_starts_with($mouvement->reference_document, 'TR');
                                            $isEntree = $mouvement->quantite > 0;
                                            $isSortie = $mouvement->quantite < 0;
                                        ?>
                                        
                                        <?php if($isEntree && $isTransfert): ?>
                                            <span class="badge bg-info">
                                                <i class="fas fa-exchange-alt"></i> Transfert Entrée
                                            </span>
                                        <?php elseif($isSortie && $isTransfert): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exchange-alt"></i> Transfert Sortie
                                            </span>
                                        <?php elseif($isEntree): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-down"></i> Entrée
                                            </span>
                                        <?php elseif($isSortie): ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-up"></i> Sortie
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-question"></i> Non défini
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <strong><?php echo e($mouvement->article->nom ?? 'N/A'); ?></strong><br>
                                        <small class="text-muted"><i class="fas fa-hashtag"></i> <?php echo e($mouvement->article->reference ?? 'N/A'); ?></small>
                                    </td>
                                    <td class="align-middle">
                                        <i class="fas fa-warehouse text-primary"></i> <?php echo e($mouvement->depot->nom ?? 'N/A'); ?>

                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-primary fs-6"><?php echo e(number_format(abs($mouvement->quantite), 2, ',', ' ')); ?></span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <strong class="text-success"><?php echo e(number_format($mouvement->quantite_apres, 2, ',', ' ')); ?></strong>
                                    </td>
                                    <td class="align-middle">
                                        <i class="fas fa-user-circle text-muted"></i> <?php echo e($mouvement->user_name ?? 'Inconnu'); ?>

                                    </td>
                                    <td class="align-middle">
                                        <?php if($mouvement->motif): ?>
                                            <small class="text-muted"><?php echo e($mouvement->motif); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-secondary opacity-50 mb-3"></i>
                    <h5 class="text-secondary">Aucun mouvement de stock</h5>
                    <p class="text-muted"><i class="fas fa-info-circle"></i> Commencez par créer un nouveau mouvement de stock.</p>
                    <a href="<?php echo e(route('mouvements-stock.create')); ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle"></i> Créer un Mouvement
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if($mouvements->hasPages()): ?>
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-center">
                        <?php echo e($mouvements->links('pagination::bootstrap-5')); ?>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/mouvements-stock/index.blade.php ENDPATH**/ ?>