<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FamilleController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\BonCommandeController;
use App\Http\Controllers\BonLivraisonController;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\MouvementStockController;

// Page d'accueil - Redirection vers login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route pour rafraîchir le token CSRF (accessible sans authentification)
Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
});

// Route de test pour déboguer BC
Route::get('/test-bc', function() {
    $bc = \App\Models\BonCommande::first();
    if (!$bc) return response()->json(['error' => 'Aucun BC trouvé']);
    
    $devis = $bc->devis;
    
    return response()->json([
        'bc_id' => $bc->id,
        'bc_reference' => $bc->reference,
        'devis_id' => $devis->id,
        'devis_lignes_count' => $devis->lignes->count(),
        'bc_lignes_count' => $bc->lignes()->count(),
        'devis_lignes' => $devis->lignes->map(function($l) {
            return [
                'article_id' => $l->article_id,
                'quantite' => $l->quantite,
                'prix_unitaire' => $l->prix_unitaire,
            ];
        })
    ]);
});

// Route pour lister tous les devis avec lignes
Route::get('/test-devis', function() {
    $devis = \App\Models\Devis::with('lignes')->get();
    return response()->json($devis->map(function($d) {
        return [
            'id' => $d->id,
            'reference' => $d->reference,
            'statut' => $d->statut,
            'lignes_count' => $d->lignes->count(),
        ];
    }));
});

// Route pour supprimer et recréer le BC
Route::get('/fix-bc', function() {
    $bc = \App\Models\BonCommande::first();
    if (!$bc) return 'Aucun BC';
    
    $devis = $bc->devis;
    $depot_id = $bc->depot_id;
    
    // Supprimer le BC
    $bc->delete();
    
    // Recréer avec les lignes
    DB::beginTransaction();
    try {
        $newBC = \App\Models\BonCommande::create([
            'reference' => \App\Models\BonCommande::genererReference(),
            'client_id' => $devis->client_id,
            'nom_client' => $devis->nom_client,
            'prenom_client' => $devis->prenom_client,
            'devis_id' => $devis->id,
            'depot_id' => $depot_id,
            'date_commande' => now(),
            'statut' => 'en_attente',
            'created_by' => auth()->id() ?? 1,
        ]);
        
        foreach ($devis->lignes as $ligneDevis) {
            $newBC->lignes()->create([
                'article_id' => $ligneDevis->article_id,
                'quantite' => $ligneDevis->quantite,
                'prix_unitaire' => $ligneDevis->prix_unitaire,
                'taux_tva' => $ligneDevis->taux_tva ?? 0,
            ]);
        }
        
        $newBC->refresh();
        $newBC->load('lignes');
        $newBC->calculerTotaux();
        
        DB::commit();
        
        return redirect()->route('bons-commande.show', $newBC)->with('success', 'BC recréé avec succès!');
    } catch (\Exception $e) {
        DB::rollBack();
        return 'Erreur: ' . $e->getMessage();
    }
});

// Routes d'authentification (sans inscription ni réinitialisation de mot de passe)
Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Clients
    Route::resource('clients', ClientController::class);

    // Familles d'articles
    Route::resource('familles', FamilleController::class);

    // Dépôts
    Route::resource('depots', DepotController::class);
    Route::get('depots/{depot}/pdf', [DepotController::class, 'generatePdf'])->name('depots.pdf');

    // Articles
    Route::resource('articles', ArticleController::class);

    // Devis
    Route::resource('devis', DevisController::class)->parameters([
        'devis' => 'devis'
    ]);
    Route::post('devis/{devis}/valider', [DevisController::class, 'valider'])->name('devis.valider');
    Route::get('devis/{devis}/pdf', [DevisController::class, 'generatePdf'])->name('devis.pdf');

    // Bons de commande - Routes spécifiques AVANT resource
    Route::get('bons-commande/create', function() {
        $clients = \App\Models\Client::where('is_active', 1)->orderBy('nom')->get();
        $depots = \App\Models\Depot::where('is_active', 1)->orderBy('nom')->get();
        $articles = \App\Models\Article::where('is_active', 1)->orderBy('nom')->get();
        return view('bons-commande.create', compact('clients', 'depots', 'articles'));
    })->name('bons-commande.create');
    
    Route::post('bons-commande', function(\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'depot_id' => 'required|exists:depots,id',
            'date_commande' => 'required|date',
            'notes' => 'nullable|string',
            'articles' => 'nullable|array',
            'articles.*.article_id' => 'required|exists:articles,id',
            'articles.*.quantite' => 'required|numeric|min:0.01',
            'articles.*.prix_unitaire' => 'required|numeric|min:0',
            'articles.*.taux_tva' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $client = \App\Models\Client::findOrFail($validated['client_id']);
            
            $bonCommande = \App\Models\BonCommande::create([
                'reference' => \App\Models\BonCommande::genererReference(),
                'client_id' => $validated['client_id'],
                'nom_client' => $client->nom,
                'prenom_client' => $client->prenom,
                'depot_id' => $validated['depot_id'],
                'date_commande' => $validated['date_commande'],
                'statut' => 'en_attente',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Ajouter les articles (lignes)
            if (isset($validated['articles']) && is_array($validated['articles'])) {
                foreach ($validated['articles'] as $article) {
                    $bonCommande->lignes()->create([
                        'article_id' => $article['article_id'],
                        'quantite' => $article['quantite'],
                        'prix_unitaire' => $article['prix_unitaire'],
                        'taux_tva' => $article['taux_tva'] ?? 0,
                    ]);
                }
            }

            $bonCommande->calculerTotaux();
            
            DB::commit();

            return redirect()
                ->route('bons-commande.show', $bonCommande)
                ->with('success', 'Bon de commande enregistré avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    })->name('bons-commande.store');
    
    Route::get('bons-commande/from-devis/{devis}', [BonCommandeController::class, 'createFromDevis'])->name('bons-commande.from-devis');
    Route::post('bons-commande/from-devis/{devis}', [BonCommandeController::class, 'storeFromDevis'])->name('bons-commande.store-from-devis');
    Route::post('bons-commande/{bonCommande}/valider', [BonCommandeController::class, 'valider'])->name('bons-commande.valider');
    Route::get('bons-commande/{bonCommande}/pdf', [BonCommandeController::class, 'pdf'])->name('bons-commande.pdf');
    Route::get('bons-commande/{bonCommande}/create-bl', [BonCommandeController::class, 'createBonLivraison'])->name('bons-commande.create-bl');
    Route::resource('bons-commande', BonCommandeController::class)->except(['create', 'store'])->parameters([
        'bons-commande' => 'bonCommande'
    ]);

    // Bons de livraison - Routes spécifiques AVANT resource
    Route::get('bons-livraison/from-bc/{bonCommande}', [BonLivraisonController::class, 'createFromBonCommande'])->name('bons-livraison.from-bc');
    Route::post('bons-livraison/from-bc/{bonCommande}', [BonLivraisonController::class, 'storeFromBonCommande'])->name('bons-livraison.store-from-bc');
    Route::post('bons-livraison/{bonLivraison}/livrer', [BonLivraisonController::class, 'livrer'])->name('bons-livraison.livrer');
    Route::get('bons-livraison/{bonLivraison}/pdf', [BonLivraisonController::class, 'generatePdf'])->name('bons-livraison.pdf');
    Route::resource('bons-livraison', BonLivraisonController::class)->parameters([
        'bons-livraison' => 'bonLivraison'
    ]);

    // Transferts
    Route::resource('transferts', TransfertController::class);
    Route::post('transferts/{transfert}/executer', [TransfertController::class, 'executer'])->name('transferts.executer');

    // Mouvements de stock
    Route::get('mouvements-stock', [MouvementStockController::class, 'index'])->name('mouvements-stock.index');
    Route::get('mouvements-stock/create', [MouvementStockController::class, 'create'])->name('mouvements-stock.create');
    Route::post('mouvements-stock', [MouvementStockController::class, 'store'])->name('mouvements-stock.store');
    Route::get('stock/export-excel', [MouvementStockController::class, 'exportExcel'])->name('stock.export-excel');
    Route::get('stock/export-pdf', [MouvementStockController::class, 'exportPdf'])->name('stock.export-pdf');
    
    // API pour obtenir le stock actuel
    Route::get('api/stock-actuel', function(Illuminate\Http\Request $request) {
        $stock = \App\Models\Stock::where('article_id', $request->article_id)
            ->where('depot_id', $request->depot_id)
            ->first();
        return response()->json([
            'quantite' => $stock ? $stock->quantite : 0,
            'unite' => $stock && $stock->article ? $stock->article->unite : 'Pièce'
        ]);
    });
    
    // API pour transferts - route avec paramètres d'URL
    Route::get('api/stock/{article}/{depot}', function($articleId, $depotId) {
        $stock = \App\Models\Stock::where('article_id', $articleId)
            ->where('depot_id', $depotId)
            ->first();
        
        return response()->json([
            'quantite' => $stock ? $stock->quantite : 0,
            'unite' => $stock && $stock->article ? $stock->article->unite : 'Pièce',
            'stock_min' => $stock ? $stock->stock_min : 0,
            'stock_max' => $stock ? $stock->stock_max : 0,
        ]);
    });
});
