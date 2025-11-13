<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BonCommande;
use App\Models\Client;
use App\Models\Depot;
use App\Models\Devis;

class BonCommandeController extends Controller
{
    public function index(Request $request)
    {
        $query = BonCommande::with(['client', 'depot', 'devis']);

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('depot_id')) {
            $query->where('depot_id', $request->depot_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_commande', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_commande', '<=', $request->date_fin);
        }

        $bonsCommande = $query->orderBy('date_commande', 'desc')->orderBy('created_at', 'desc')->get();
        $clients = Client::orderBy('nom')->get();
        $depots = Depot::orderBy('nom')->get();

        return view('bons-commande.index', compact('bonsCommande', 'clients', 'depots'));
    }

    public function show(BonCommande $bonCommande)
    {
        $bonCommande->load(['client', 'depot', 'lignes.article', 'devis', 'createdBy']);
        
        return view('bons-commande.show', compact('bonCommande'));
    }

    public function edit(BonCommande $bonCommande)
    {
        $clients = Client::where('is_active', 1)->orderBy('nom')->get();
        $depots = Depot::where('is_active', 1)->orderBy('nom')->get();
        
        return view('bons-commande.edit', compact('bonCommande', 'clients', 'depots'));
    }

    public function update(Request $request, BonCommande $bonCommande)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'depot_id' => 'required|exists:depots,id',
            'date_commande' => 'required|date',
            'statut' => 'required|in:brouillon,validé,en_cours,livré,annulé',
            'notes' => 'nullable|string',
        ]);

        $bonCommande->update($validated);

        return redirect()
            ->route('bons-commande.show', $bonCommande)
            ->with('success', 'Bon de commande mis à jour!');
    }

    public function destroy(BonCommande $bonCommande)
    {
        if ($bonCommande->statut === 'validee') {
            return back()->with('error', 'Impossible de supprimer un bon de commande validé.');
        }

        $bonCommande->delete();

        return redirect()
            ->route('bons-commande.index')
            ->with('success', 'Bon de commande supprimé!');
    }

    public function valider(BonCommande $bonCommande)
    {
        if ($bonCommande->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les bons de commande en attente peuvent etre valides.');
        }

        try {
            \DB::beginTransaction();

            foreach ($bonCommande->lignes as $ligne) {
                $stock = \App\Models\Stock::where('article_id', $ligne->article_id)
                    ->where('depot_id', $bonCommande->depot_id)
                    ->first();

                $quantiteAvant = $stock ? $stock->quantite : 0;

                if ($stock) {
                    $stock->quantite -= $ligne->quantite;
                    $stock->save();
                } else {
                    \App\Models\Stock::create([
                        'article_id' => $ligne->article_id,
                        'depot_id' => $bonCommande->depot_id,
                        'quantite' => -$ligne->quantite,
                        'stock_min' => 0,
                        'stock_max' => 0,
                    ]);
                }

                \App\Models\MouvementStock::create([
                    'article_id' => $ligne->article_id,
                    'depot_id' => $bonCommande->depot_id,
                    'type' => 'sortie',
                    'quantite' => $ligne->quantite,
                    'quantite_avant' => $quantiteAvant,
                    'quantite_apres' => $quantiteAvant - $ligne->quantite,
                    'reference' => "BC {$bonCommande->reference}",
                    'created_by' => auth()->id(),
                ]);
            }

            $bonCommande->update(['statut' => 'validee']);

            \DB::commit();

            return back()->with('success', 'Bon de commande validé et stock mis à jour!');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function pdf(BonCommande $bonCommande)
    {
        $bonCommande->load(['client', 'depot', 'lignes.article', 'devis', 'createdBy']);

        // Calculer les totaux à partir des lignes
        $montant_ht = 0;
        $montant_tva = 0;
        
        foreach ($bonCommande->lignes as $ligne) {
            $montant_ht += $ligne->montant_ht;
            $montant_tva += $ligne->montant_tva;
        }
        
        $montant_ttc = $montant_ht + $montant_tva;
        
        // Assigner les totaux au bon de commande
        $bonCommande->montant_ht = $montant_ht;
        $bonCommande->montant_tva = $montant_tva;
        $bonCommande->montant_ttc = $montant_ttc;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bons-commande.pdf', compact('bonCommande'));
        
        return $pdf->download('bc_' . $bonCommande->reference . '.pdf');
    }

    /**
     * Afficher le formulaire de création d'un BC à partir d'un devis
     */
    public function createFromDevis(Devis $devis)
    {
        // Vérifier que le devis est validé
        if ($devis->statut !== 'valide') {
            return redirect()
                ->route('devis.show', $devis)
                ->with('error', 'Seuls les devis validés peuvent être transformés en bon de commande.');
        }

        // Vérifier qu'un BC n'a pas déjà été créé pour ce devis
        if ($devis->bonCommande) {
            return redirect()
                ->route('bons-commande.show', $devis->bonCommande)
                ->with('info', 'Un bon de commande existe déjà pour ce devis.');
        }

        $devis->load(['client', 'lignes.article']);
        $depots = Depot::where('is_active', 1)->orderBy('nom')->get();

        return view('bons-commande.create-from-devis', compact('devis', 'depots'));
    }

    /**
     * Créer un BC à partir d'un devis
     */
    public function storeFromDevis(Request $request, Devis $devis)
    {
        // Charger les relations nécessaires
        $devis->load(['client', 'lignes.article']);

        // Vérifier que le devis est validé
        if ($devis->statut !== 'valide') {
            return redirect()
                ->route('devis.show', $devis)
                ->with('error', 'Seuls les devis validés peuvent être transformés en bon de commande.');
        }

        // Vérifier qu'un BC n'a pas déjà été créé
        if ($devis->bonCommande) {
            return redirect()
                ->route('bons-commande.show', $devis->bonCommande)
                ->with('info', 'Un bon de commande existe déjà pour ce devis.');
        }

        $validated = $request->validate([
            'depot_id' => 'required|exists:depots,id',
            'date_commande' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            \DB::beginTransaction();

            // Générer la référence
            $lastBC = BonCommande::latest('id')->first();
            $numero = $lastBC ? ($lastBC->id + 1) : 1;
            $reference = 'BC-' . date('Y') . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);

            // Créer le bon de commande
            $bonCommande = BonCommande::create([
                'reference' => $reference,
                'client_id' => $devis->client_id,
                'nom_client' => $devis->client->nom ?? 'Client inconnu',
                'depot_id' => $validated['depot_id'],
                'devis_id' => $devis->id,
                'date_commande' => $validated['date_commande'],
                'statut' => 'en_attente',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Copier les lignes du devis vers le BC
            foreach ($devis->lignes as $ligneDevis) {
                $quantite = $ligneDevis->quantite;
                $prixUnitaire = $ligneDevis->prix_unitaire;
                $tauxTva = $ligneDevis->taux_tva ?? 0;
                
                $montantHt = $quantite * $prixUnitaire;
                $montantTva = $montantHt * ($tauxTva / 100);
                $montantTtc = $montantHt + $montantTva;
                
                \App\Models\LigneBonCommande::create([
                    'bon_commande_id' => $bonCommande->id,
                    'article_id' => $ligneDevis->article_id,
                    'quantite' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'taux_tva' => $tauxTva,
                    'montant_ht' => $montantHt,
                    'montant_tva' => $montantTva,
                    'montant_ttc' => $montantTtc,
                ]);
            }

            \DB::commit();

            return redirect()
                ->route('bons-commande.show', $bonCommande)
                ->with('success', 'Bon de commande créé avec succès à partir du devis ' . $devis->numero . '.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }
}
