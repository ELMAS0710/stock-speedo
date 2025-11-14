

<?php $__env->startSection('title', 'Dashboard - Speedo Gestion Stock'); ?>
<?php $__env->startSection('page-title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Clients</h6>
                        <h2 class="mb-0 fw-bold"><?php echo e($totalClients); ?></h2>
                    </div>
                    <div class="rounded p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="bi bi-people text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Bons de livraison</h6>
                        <h2 class="mb-0 fw-bold"><?php echo e($totalBonsLivraison); ?></h2>
                    </div>
                    <div class="rounded p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="bi bi-truck text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Devis validés</h6>
                        <h2 class="mb-0 fw-bold"><?php echo e($devisValides); ?></h2>
                    </div>
                    <div class="rounded p-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="bi bi-check-circle text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Stock Total</h6>
                        <h2 class="mb-0 fw-bold"><?php echo e(number_format($stockTotal, 0, ',', ' ')); ?></h2>
                    </div>
                    <div class="rounded p-3" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                        <i class="bi bi-box-seam text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Stock par Dépôt -->
        <div class="col-md-6">
            <div class="stat-card">
                <h5 class="mb-4"><i class="bi bi-building me-2"></i>Stock par Dépôt</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Dépôt</th>
                                <th>Ville</th>
                                <th class="text-end">Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $stockParDepot; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($depot->nom); ?></strong></td>
                                <td><?php echo e($depot->ville); ?></td>
                                <td class="text-end">
                                    <span class="badge bg-primary">
                                        <?php echo e(number_format($depot->stocks_sum_quantite ?? 0, 0, ',', ' ')); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun dépôt</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Articles les plus sortis -->
        <div class="col-md-6">
            <div class="stat-card">
                <h5 class="mb-4"><i class="bi bi-graph-up me-2"></i>Articles les plus sortis (30 derniers jours)</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Référence</th>
                                <th class="text-end">Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $articlesPlusSortis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mouvement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($mouvement->article->nom ?? 'N/A'); ?></strong></td>
                                <td><?php echo e($mouvement->article->reference ?? ''); ?></td>
                                <td class="text-end">
                                    <span class="badge bg-danger">
                                        <?php echo e(number_format($mouvement->total_sortie, 0, ',', ' ')); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun mouvement</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Derniers mouvements -->
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Derniers mouvements de stock</h5>
                    <a href="<?php echo e(route('mouvements-stock.index')); ?>" class="btn btn-sm btn-primary">
                        Voir tous les mouvements <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Article</th>
                                <th>Dépôt</th>
                                <th>Type</th>
                                <th class="text-end">Quantité</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $derniersMouvements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mouvement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($mouvement->created_at->format('d/m/Y H:i')); ?></td>
                                <td><strong><?php echo e($mouvement->article->nom ?? 'N/A'); ?></strong></td>
                                <td><?php echo e($mouvement->depot->nom ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($mouvement->quantite > 0): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-arrow-down"></i> Entrée
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-arrow-up"></i> Sortie
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-primary">
                                        <?php echo e(number_format(abs($mouvement->quantite), 0, ',', ' ')); ?>

                                    </span>
                                </td>
                                <td><?php echo e($mouvement->createdBy->name ?? 'Inconnu'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Aucun mouvement</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/dashboard-new.blade.php ENDPATH**/ ?>