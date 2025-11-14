

<?php $__env->startSection('title', 'Transferts'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-arrow-left-right"></i> Transferts de Stock</h2>
    <a href="<?php echo e(route('transferts.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Transfert
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover data-table" id="transfertsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>De</th>
                        <th>Vers</th>
                        <th>Nb Articles</th>
                        <th>Statut</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transferts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong>#<?php echo e($transfert->id); ?></strong></td>
                            <td><?php echo e($transfert->date_transfert->format('d/m/Y')); ?></td>
                            <td><?php echo e($transfert->depotSource->nom); ?></td>
                            <td><?php echo e($transfert->depotDestination->nom); ?></td>
                            <td><?php echo e($transfert->lignes->count()); ?></td>
                            <td>
                                <?php if($transfert->statut == 'en_attente'): ?>
                                    <span class="badge bg-warning">En attente</span>
                                <?php elseif($transfert->statut == 'en_cours'): ?>
                                    <span class="badge bg-info">En cours</span>
                                <?php elseif($transfert->statut == 'termine'): ?>
                                    <span class="badge bg-success">Terminé</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Annulé</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($transfert->createdBy->name ?? 'N/A'); ?></td>
                            <td>
                                <a href="<?php echo e(route('transferts.show', $transfert)); ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="bi bi-inbox"></i> Aucun transfert trouvé
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/transferts/index.blade.php ENDPATH**/ ?>