

<?php $__env->startSection('title', 'Bons de Commande'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cart"></i> Bons de Commande</h2>
    <a href="<?php echo e(route('bons-commande.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Bon de Commande
    </a>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="bi bi-funnel"></i> Filtres</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('bons-commande.index')); ?>">
            <div class="row g-3">
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
                    <label class="form-label">Dépôt</label>
                    <select name="depot_id" class="form-select">
                        <option value="">Tous les dépôts</option>
                        <?php $__currentLoopData = $depots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($depot->id); ?>" <?php echo e(request('depot_id') == $depot->id ? 'selected' : ''); ?>>
                                <?php echo e($depot->nom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="en_attente" <?php echo e(request('statut') == 'en_attente' ? 'selected' : ''); ?>>En attente</option>
                        <option value="validee" <?php echo e(request('statut') == 'validee' ? 'selected' : ''); ?>>Validee</option>
                        <option value="livree" <?php echo e(request('statut') == 'livree' ? 'selected' : ''); ?>>Livree</option>
                        <option value="annulee" <?php echo e(request('statut') == 'annulee' ? 'selected' : ''); ?>>Annulee</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date début</label>
                    <input type="date" name="date_debut" class="form-control" value="<?php echo e(request('date_debut')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date fin</label>
                    <input type="date" name="date_fin" class="form-control" value="<?php echo e(request('date_fin')); ?>">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrer
                </button>
                <a href="<?php echo e(route('bons-commande.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="bonsCommandeTable">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Date Commande</th>
                        <th>Dépôt</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bonsCommande; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($bc->reference); ?></strong></td>
                            <td><?php echo e($bc->client->nom); ?> <?php echo e($bc->client->prenom); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($bc->date_commande)->format('d/m/Y')); ?></td>
                            <td><?php echo e($bc->depot->nom); ?></td>
                            <td>
                                <?php if($bc->statut == 'en_attente'): ?>
                                    <span class="badge bg-warning">En attente</span>
                                <?php elseif($bc->statut == 'validee'): ?>
                                    <span class="badge bg-success">Validee</span>
                                <?php elseif($bc->statut == 'livree'): ?>
                                    <span class="badge bg-info">Livree</span>
                                <?php elseif($bc->statut == 'annulee'): ?>
                                    <span class="badge bg-danger">Annulee</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e($bc->statut); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('bons-commande.show', $bc)); ?>" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun bon de commande trouvé</td>
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
    $('#bonsCommandeTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
        },
        "order": [[2, "desc"]],
        "pageLength": 25
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/bons-commande/index.blade.php ENDPATH**/ ?>