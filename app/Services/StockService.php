<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\MouvementStock;
use App\Models\Article;
use App\Models\Depot;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Ajuster le stock d'un article dans un dépôt
     */
    public function ajusterStock(Article $article, Depot $depot, float $quantite, string $type, ?string $reference = null, ?string $notes = null)
    {
        DB::beginTransaction();
        
        try {
            // Récupérer ou créer le stock
            $stock = Stock::firstOrCreate(
                [
                    'article_id' => $article->id,
                    'depot_id' => $depot->id,
                ],
                [
                    'quantite' => 0,
                    'stock_min' => 0,
                    'stock_max' => 0,
                ]
            );

            // Calculer la nouvelle quantité
            $ancienneQuantite = $stock->quantite;
            $nouvelleQuantite = $ancienneQuantite + $quantite;

            // Mettre à jour le stock
            $stock->quantite = $nouvelleQuantite;
            $stock->save();

            // Créer le mouvement de stock
            MouvementStock::create([
                'article_id' => $article->id,
                'depot_id' => $depot->id,
                'type' => $type,
                'quantite' => abs($quantite),
                'quantite_avant' => $ancienneQuantite,
                'quantite_apres' => $nouvelleQuantite,
                'reference' => $reference,
                'notes' => $notes,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            
            return $stock;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Entrée de stock
     */
    public function entreeStock(Article $article, Depot $depot, float $quantite, ?string $reference = null, ?string $notes = null)
    {
        return $this->ajusterStock($article, $depot, $quantite, 'entree', $reference, $notes);
    }

    /**
     * Sortie de stock
     */
    public function sortieStock(Article $article, Depot $depot, float $quantite, ?string $reference = null, ?string $notes = null)
    {
        return $this->ajusterStock($article, $depot, -$quantite, 'sortie', $reference, $notes);
    }

    /**
     * Transfert entre dépôts
     */
    public function transfererStock(Article $article, Depot $depotSource, Depot $depotDestination, float $quantite, ?string $reference = null, ?string $notes = null)
    {
        DB::beginTransaction();
        
        try {
            // Sortie du dépôt source
            $this->ajusterStock($article, $depotSource, -$quantite, 'transfert_sortie', $reference, $notes);
            
            // Entrée dans le dépôt destination
            $this->ajusterStock($article, $depotDestination, $quantite, 'transfert_entree', $reference, $notes);
            
            DB::commit();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Inventaire - Ajustement direct
     */
    public function inventaire(Article $article, Depot $depot, float $nouvelleQuantite, ?string $notes = null)
    {
        DB::beginTransaction();
        
        try {
            $stock = Stock::firstOrCreate(
                [
                    'article_id' => $article->id,
                    'depot_id' => $depot->id,
                ],
                [
                    'quantite' => 0,
                    'stock_min' => 0,
                    'stock_max' => 0,
                ]
            );

            $ancienneQuantite = $stock->quantite;
            $difference = $nouvelleQuantite - $ancienneQuantite;

            // Mettre à jour le stock
            $stock->quantite = $nouvelleQuantite;
            $stock->save();

            // Créer le mouvement
            MouvementStock::create([
                'article_id' => $article->id,
                'depot_id' => $depot->id,
                'type' => 'ajustement',
                'quantite' => abs($difference),
                'quantite_avant' => $ancienneQuantite,
                'quantite_apres' => $nouvelleQuantite,
                'notes' => $notes ?? 'Ajustement inventaire',
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            
            return $stock;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtenir le stock d'un article dans un dépôt
     */
    public function getStock(Article $article, Depot $depot)
    {
        return Stock::where('article_id', $article->id)
            ->where('depot_id', $depot->id)
            ->first();
    }

    /**
     * Vérifier si le stock est disponible
     */
    public function stockDisponible(Article $article, Depot $depot, float $quantite): bool
    {
        $stock = $this->getStock($article, $depot);
        
        if (!$stock) {
            return false;
        }
        
        return $stock->quantite >= $quantite;
    }
}
