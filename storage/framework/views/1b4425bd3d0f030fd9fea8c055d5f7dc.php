

<?php $__env->startSection('title', 'Détails Bon de Livraison'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-truck"></i> Bon de Livraison <?php echo e($bonsLivraison->reference); ?></h2>
    <div>
        <?php if($bonsLivraison->statut == 'en_preparation'): ?>
            <form action="<?php echo e(route('bons-livraison.livrer', $bonsLivraison)); ?>" method="POST" class="d-inline"
                  onsubmit="return confirm('Confirmer la livraison ?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Marquer comme Livré
                </button>
            </form>
        <?php endif; ?>
        <a href="<?php echo e(route('bons-livraison.pdf', $bonsLivraison)); ?>" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-pdf"></i> PDF
        </a>
        <a href="<?php echo e(route('bons-livraison.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Client:</th>
                        <td>
                            <a href="<?php echo e(route('clients.show', $bonsLivraison->client)); ?>">
                                <?php echo e($bonsLivraison->client->nom); ?>

                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Dépôt:</th>
                        <td><?php echo e($bonsLivraison->depot->nom); ?></td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td><?php echo e($bonsLivraison->date_livraison->format('d/m/Y')); ?></td>
                    </tr>
                    <?php if($bonsLivraison->date_livraison_effective): ?>
                        <tr>
                            <th>Livré le:</th>
                            <td><?php echo e($bonsLivraison->date_livraison_effective->format('d/m/Y H:i')); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Statut:</th>
                        <td>
                            <?php if($bonsLivraison->statut == 'en_preparation'): ?>
                                <span class="badge bg-warning">En préparation</span>
                            <?php elseif($bonsLivraison->statut == 'livre'): ?>
                                <span class="badge bg-success">Livré</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Annulé</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if($bonsLivraison->reference_marche): ?>
                    <tr>
                        <th>Référence Marché:</th>
                        <td><span class="badge bg-info"><?php echo e($bonsLivraison->reference_marche); ?></span></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($bonsLivraison->devis): ?>
                        <tr>
                            <th>Devis:</th>
                            <td>
                                <a href="<?php echo e(route('devis.show', $bonsLivraison->devis)); ?>">
                                    <?php echo e($bonsLivraison->devis->reference); ?>

                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lignes du Bon</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Quantité</th>
                                                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bonsLivraison->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($ligne->article->reference); ?></strong><br>
                                        <small><?php echo e($ligne->article->nom); ?></small>
                                    </td>
                                    <td><?php echo e($ligne->quantite); ?> <?php echo e($ligne->article->unite); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/bons-livraison/show.blade.php ENDPATH**/ ?>