

<?php $__env->startSection('title', 'Transfert #' . $transfert->reference); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Transfert #<?php echo e($transfert->reference); ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('transferts.index')); ?>">Transferts</a></li>
                    <li class="breadcrumb-item active"><?php echo e($transfert->reference); ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <?php if($transfert->statut === 'en_attente'): ?>
                <form action="<?php echo e(route('transferts.executer', $transfert)); ?>" method="POST" class="d-inline" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir exécuter ce transfert ? Les stocks seront mis à jour.')">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Exécuter
                    </button>
                </form>
            <?php endif; ?>
            
            <?php if(in_array($transfert->statut, ['en_attente', 'en_cours'])): ?>
                <a href="<?php echo e(route('transferts.edit', $transfert)); ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            <?php endif; ?>
            
            <a href="<?php echo e(route('transferts.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Informations générales -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Générales</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Référence:</th>
                            <td><strong><?php echo e($transfert->reference); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td><?php echo e($transfert->date_transfert->format('d/m/Y')); ?></td>
                        </tr>
                        <tr>
                            <th>Statut:</th>
                            <td>
                                <?php if($transfert->statut === 'en_attente'): ?>
                                    <span class="badge bg-warning text-dark">En Attente</span>
                                <?php elseif($transfert->statut === 'en_cours'): ?>
                                    <span class="badge bg-info">En Cours</span>
                                <?php elseif($transfert->statut === 'termine'): ?>
                                    <span class="badge bg-success">Terminé</span>
                                <?php elseif($transfert->statut === 'annule'): ?>
                                    <span class="badge bg-danger">Annulé</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($transfert->statut)); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Créé par:</th>
                            <td><?php echo e($transfert->createdBy->name ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Créé le:</th>
                            <td><?php echo e($transfert->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informations dépôts -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-warehouse"></i> Dépôts</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Dépôt Source:</th>
                            <td>
                                <a href="<?php echo e(route('depots.show', $transfert->depotSource)); ?>">
                                    <?php echo e($transfert->depotSource->nom); ?>

                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Adresse Source:</th>
                            <td><?php echo e($transfert->depotSource->adresse ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Dépôt Destination:</th>
                            <td>
                                <a href="<?php echo e(route('depots.show', $transfert->depotDestination)); ?>">
                                    <?php echo e($transfert->depotDestination->nom); ?>

                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Adresse Destination:</th>
                            <td><?php echo e($transfert->depotDestination->adresse ?? 'N/A'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <?php if($transfert->notes): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notes</h5>
        </div>
        <div class="card-body">
            <p class="mb-0"><?php echo e($transfert->notes); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Lignes du transfert -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Articles Transférés</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th>Référence</th>
                            <th width="120" class="text-center">Quantité</th>
                            <th width="150">Stock Source</th>
                            <th width="150">Stock Destination</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transfert->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($ligne->article): ?>
                                    <a href="<?php echo e(route('articles.show', $ligne->article)); ?>">
                                        <?php echo e($ligne->article->nom); ?>

                                    </a>
                                <?php else: ?>
                                    Article supprimé
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($ligne->article->reference ?? 'N/A'); ?></td>
                            <td class="text-center">
                                <strong><?php echo e($ligne->quantite); ?></strong>
                            </td>
                            <td>
                                <?php
                                    $stockSource = \App\Models\Stock::where('article_id', $ligne->article_id)
                                        ->where('depot_id', $transfert->depot_source_id)
                                        ->first();
                                ?>
                                <?php if($stockSource): ?>
                                    <span class="badge <?php echo e($stockSource->quantite >= 0 ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo e($stockSource->quantite); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">0</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $stockDest = \App\Models\Stock::where('article_id', $ligne->article_id)
                                        ->where('depot_id', $transfert->depot_destination_id)
                                        ->first();
                                ?>
                                <?php if($stockDest): ?>
                                    <span class="badge <?php echo e($stockDest->quantite >= 0 ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo e($stockDest->quantite); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">0</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun article</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if($transfert->lignes->count() > 0): ?>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="2" class="text-end">Total Articles:</th>
                            <th class="text-center"><?php echo e($transfert->lignes->count()); ?></th>
                            <th colspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-end">Quantité Totale:</th>
                            <th class="text-center"><?php echo e($transfert->lignes->sum('quantite')); ?></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/transferts/show.blade.php ENDPATH**/ ?>