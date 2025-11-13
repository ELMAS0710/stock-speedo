

<?php $__env->startSection('title', 'Bon de Commande ' . $bonCommande->reference); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cart-check"></i> Bon de Commande <?php echo e($bonCommande->reference); ?></h2>
    <div>
        <a href="<?php echo e(route('bons-commande.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <a href="<?php echo e(route('bons-commande.pdf', $bonCommande)); ?>" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> PDF
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
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations Client</h5>
            </div>
            <div class="card-body">
                <p><strong>Client:</strong> <?php echo e($bonCommande->nom_client); ?> <?php echo e($bonCommande->prenom_client); ?></p>
                <p><strong>Date Commande:</strong> <?php echo e($bonCommande->date_commande->format('d/m/Y')); ?></p>
                <p><strong>Dépôt:</strong> <?php echo e($bonCommande->depot->nom); ?></p>
                <p>
                    <strong>Statut:</strong>
                    <?php if($bonCommande->statut === 'en_attente'): ?>
                        <span class="badge bg-warning">En Attente</span>
                    <?php elseif($bonCommande->statut === 'validee'): ?>
                        <span class="badge bg-success">Validée</span>
                    <?php elseif($bonCommande->statut === 'livree'): ?>
                        <span class="badge bg-info">Livrée</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Annulée</span>
                    <?php endif; ?>
                </p>
                <?php if($bonCommande->devis): ?>
                    <p><strong>Devis:</strong> 
                        <a href="<?php echo e(route('devis.show', $bonCommande->devis)); ?>"><?php echo e($bonCommande->devis->reference); ?></a>
                    </p>
                <?php endif; ?>
                <?php if($bonCommande->reference_marche): ?>
                    <p><strong>Référence Marché:</strong> <span class="badge bg-primary"><?php echo e($bonCommande->reference_marche); ?></span></p>
                <?php endif; ?>
                <?php if($bonCommande->notes): ?>
                    <p><strong>Notes:</strong><br><?php echo e($bonCommande->notes); ?></p>
                <?php endif; ?>
                <p class="text-muted mb-0"><small>Créé par <?php echo e($bonCommande->createdBy->name); ?> le <?php echo e($bonCommande->created_at->format('d/m/Y H:i')); ?></small></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <?php if($bonCommande->statut === 'en_attente'): ?>
                    <form method="POST" action="<?php echo e(route('bons-commande.valider', $bonCommande)); ?>" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Confirmer la validation? Le stock sera mis à jour.')">
                            <i class="bi bi-check-circle"></i> Valider et Impacter Stock
                        </button>
                    </form>
                <?php endif; ?>

                <?php if($bonCommande->statut === 'validee' && !$bonCommande->bonLivraison): ?>
                    <a href="<?php echo e(route('bons-livraison.from-bc', $bonCommande)); ?>" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-truck"></i> Générer Bon de Livraison
                    </a>
                <?php endif; ?>

                <?php if($bonCommande->bonLivraison): ?>
                    <a href="<?php echo e(route('bons-livraison.show', $bonCommande->bonLivraison)); ?>" class="btn btn-info w-100">
                        <i class="bi bi-eye"></i> Voir Bon de Livraison
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Articles Commandés</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Total HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bonCommande->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($ligne->article->reference); ?></strong><br>
                                        <small><?php echo e($ligne->article->nom); ?></small>
                                    </td>
                                    <td><?php echo e($ligne->quantite); ?></td>
                                    <td><?php echo e(number_format($ligne->prix_unitaire, 2, ',', ' ')); ?> FCFA</td>
                                    <td><strong><?php echo e(number_format($ligne->total_ht, 2, ',', ' ')); ?> FCFA</strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total HT:</th>
                                <th><?php echo e(number_format($bonCommande->montant_ht, 2, ',', ' ')); ?> FCFA</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">TVA:</th>
                                <th><?php echo e(number_format($bonCommande->montant_tva, 2, ',', ' ')); ?> FCFA</th>
                            </tr>
                            <tr class="table-primary">
                                <th colspan="3" class="text-end">Total TTC:</th>
                                <th><?php echo e(number_format($bonCommande->montant_ttc, 2, ',', ' ')); ?> FCFA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\GESTION STOCK\gestion_stock_speedo\resources\views/bons-commande/show.blade.php ENDPATH**/ ?>