<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BonCommande;

echo "Verification des statuts des Bons de Commande...\n\n";

$bonsCommande = BonCommande::select('id', 'reference', 'statut', 'created_at')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Total: " . $bonsCommande->count() . " bons de commande\n\n";

foreach ($bonsCommande as $bc) {
    echo "ID: {$bc->id} | Ref: {$bc->reference} | Statut: {$bc->statut} | Date: {$bc->created_at}\n";
}

echo "\n\nRepartition par statut:\n";
$statuts = BonCommande::select('statut', \DB::raw('count(*) as total'))
    ->groupBy('statut')
    ->get();

foreach ($statuts as $stat) {
    echo "  - {$stat->statut}: {$stat->total}\n";
}
