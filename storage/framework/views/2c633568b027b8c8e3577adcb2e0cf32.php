

<?php $__env->startSection('title', 'Bons de Livraison'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <h2><i class="bi bi-truck"></i> Bons de Livraison</h2>
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

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="bi bi-funnel"></i> Filtres</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('bons-livraison.index')); ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Référence</label>
                    <input type="text" name="reference" class="form-control" value="<?php echo e(request('reference')); ?>" placeholder="Rechercher par référence">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Client</label>
                    <select name="client_id" class="form-select">
                        <option value="">Tous les clients</option>
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($client->id); ?>" <?php echo e(request('client_id') == $client->id ? 'selected' : ''); ?>>
                                <?php echo e($client->nom); ?> <?php echo e($client->prenom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control" value="<?php echo e(request('date_debut')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" value="<?php echo e(request('date_fin')); ?>">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                <a href="<?php echo e(route('bons-livraison.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="bonsLivraisonTable">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Date Livraison</th>
                        <th>BC</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bonsLivraison; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($bl->reference); ?></strong></td>
                            <td><?php echo e($bl->client->nom); ?> <?php echo e($bl->client->prenom); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($bl->date_livraison)->format('d/m/Y')); ?></td>
                            <td>
                                <?php if($bl->bon_commande_id): ?>
                                    <span class="badge bg-info"><?php echo e($bl->bonCommande->reference ?? 'N/A'); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($bl->statut == 'en_attente'): ?>
                                    <span class="badge bg-warning">En attente</span>
                                <?php elseif($bl->statut == 'livre'): ?>
                                    <span class="badge bg-success">Livré</span>
                                <?php elseif($bl->statut == 'annule'): ?>
                                    <span class="badge bg-danger">Annulé</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e($bl->statut); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('bons-livraison.show', $bl)); ?>" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun bon de livraison trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $('#bonsLivraisonTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
        },
        "order": [[2, "desc"]],
        "pageLength": 25
    });
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/bons-livraison/index.blade.php ENDPATH**/ ?>