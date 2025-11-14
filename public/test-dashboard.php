<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MouvementStock;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST DASHBOARD TYPE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">🔍 TEST DIAGNOSTIC - COLONNE TYPE</h1>
        
        <div class="alert alert-info">
            <strong>Instructions :</strong> Cette page teste directement le code du dashboard.
            Si vous voyez les badges "Entrée" et "Sortie" ici, le problème est définitivement le cache de votre navigateur.
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Derniers mouvements de stock</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Article</th>
                            <th>Dépôt</th>
                            <th>TYPE (COLONNE PROBLÉMATIQUE)</th>
                            <th>Quantité</th>
                            <th>Utilisateur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $derniersMouvements = MouvementStock::with(['article', 'depot', 'createdBy'])
                            ->orderByDesc('created_at')
                            ->limit(10)
                            ->get();
                        
                        foreach($derniersMouvements as $mouvement):
                        ?>
                            <tr>
                                <td><?= $mouvement->id ?></td>
                                <td>
                                    <small><?= $mouvement->created_at->format('d/m/Y H:i') ?></small>
                                </td>
                                <td>
                                    <strong><?= $mouvement->article->nom ?? 'N/A' ?></strong>
                                </td>
                                <td>
                                    <?= $mouvement->depot->nom ?? 'N/A' ?>
                                </td>
                                <td style="background-color: #fff3cd;">
                                    <!-- EXACTEMENT LE MÊME CODE QUE DASHBOARD.BLADE.PHP -->
                                    <?php if($mouvement->quantite > 0): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-arrow-down"></i> Entrée
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-arrow-up"></i> Sortie
                                        </span>
                                    <?php endif; ?>
                                    
                                    <!-- DEBUG INFO -->
                                    <br>
                                    <small class="text-muted">
                                        Quantité brute: <?= var_export($mouvement->quantite, true) ?><br>
                                        Type: <?= gettype($mouvement->quantite) ?><br>
                                        Test > 0: <?= $mouvement->quantite > 0 ? 'TRUE' : 'FALSE' ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">
                                        <?= number_format(abs($mouvement->quantite), 2, ',', ' ') ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $mouvement->createdBy->name ?? 'Inconnu' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="alert alert-success mt-4">
            <h5>✅ Si vous voyez les badges "Entrée" (vert) et "Sortie" (rouge) ci-dessus :</h5>
            <ul>
                <li>Le code PHP fonctionne parfaitement</li>
                <li>Le problème est 100% le cache de votre navigateur sur la page dashboard</li>
                <li>Solution : Ouvrez le dashboard en navigation privée (Ctrl+Shift+N)</li>
            </ul>
        </div>

        <div class="alert alert-warning mt-4">
            <h5>❌ Si vous ne voyez PAS les badges :</h5>
            <ul>
                <li>Il y a un problème plus profond (peu probable car le test CLI fonctionne)</li>
                <li>Faites une capture d'écran et envoyez-la moi</li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="/" class="btn btn-primary">Retour au Dashboard</a>
        </div>
    </div>
</body>
</html>
