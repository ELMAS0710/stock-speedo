<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MouvementStock;

echo "=== TEST COLONNE TYPE DASHBOARD ===\n\n";

$mouvements = MouvementStock::with(['article', 'depot', 'createdBy'])
    ->orderByDesc('created_at')
    ->limit(5)
    ->get();

foreach ($mouvements as $mouvement) {
    echo "ID: {$mouvement->id}\n";
    echo "  Quantite (brute): " . var_export($mouvement->quantite, true) . "\n";
    echo "  Quantite (type): " . gettype($mouvement->quantite) . "\n";
    echo "  Quantite > 0 ? " . ($mouvement->quantite > 0 ? 'OUI' : 'NON') . "\n";
    echo "  Type calculé: " . ($mouvement->quantite > 0 ? 'ENTREE' : 'SORTIE') . "\n";
    echo "  Article: " . ($mouvement->article->nom ?? 'N/A') . "\n";
    echo "  Depot: " . ($mouvement->depot->nom ?? 'N/A') . "\n";
    echo "  ---\n";
}
