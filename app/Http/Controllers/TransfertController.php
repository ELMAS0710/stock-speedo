<?php

namespace App\Http\Controllers;

use App\Models\Transfert;
use App\Models\Article;
use App\Models\Depot;
use App\Models\Stock;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransfertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transfert::with(['depotSource', 'depotDestination', 'createdBy']);

        // Filtres
        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        if ($request->filled('depot_source_id')) {
            $query->where('depot_source_id', $request->depot_source_id);
        }

        if ($request->filled('depot_destination_id')) {
            $query->where('depot_destination_id', $request->depot_destination_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_transfert', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_transfert', '<=', $request->date_fin);
        }

        $transferts = $query->orderBy('date_transfert', 'desc')->paginate(15);
        $depots = Depot::where('is_active', true)->get();

        return view('transferts.index', compact('transferts', 'depots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $depots = Depot::where('is_active', true)->orderBy('nom')->get();
        $articles = Article::where('is_active', true)->orderBy('nom')->get();

        return view('transferts.create', compact('depots', 'articles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'depot_source_id' => 'required|exists:depots,id',
            'depot_destination_id' => 'required|exists:depots,id|different:depot_source_id',
            'date_transfert' => 'required|date',
            'statut' => 'required|in:en_attente,en_cours,termine,annule',
            'notes' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.article_id' => 'required|exists:articles,id',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $validated['reference'] = Transfert::genererReference();
            $validated['created_by'] = auth()->id();

            $transfert = Transfert::create($validated);

            foreach ($request->lignes as $ligne) {
                $transfert->lignes()->create([
                    'article_id' => $ligne['article_id'],
                    'quantite' => $ligne['quantite'],
                ]);
            }

            DB::commit();
            return redirect()->route('transferts.index')
                ->with('success', 'Transfert créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transfert $transfert)
    {
        $transfert->load(['depotSource', 'depotDestination', 'lignes.article', 'createdBy']);

        // Récupérer les stocks pour chaque ligne
        foreach ($transfert->lignes as $ligne) {
            $ligne->stock_source = Stock::where('article_id', $ligne->article_id)
                ->where('depot_id', $transfert->depot_source_id)
                ->first()?->quantite ?? 0;

            $ligne->stock_destination = Stock::where('article_id', $ligne->article_id)
                ->where('depot_id', $transfert->depot_destination_id)
                ->first()?->quantite ?? 0;
        }

        return view('transferts.show', compact('transfert'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transfert $transfert)
    {
        if (!in_array($transfert->statut, ['en_attente', 'en_cours'])) {
            return back()->with('error', 'Seuls les transferts en attente ou en cours peuvent être modifiés.');
        }

        $depots = Depot::where('is_active', true)->orderBy('nom')->get();
        $articles = Article::where('is_active', true)->orderBy('nom')->get();
        $transfert->load('lignes');

        return view('transferts.edit', compact('transfert', 'depots', 'articles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transfert $transfert)
    {
        if (!in_array($transfert->statut, ['en_attente', 'en_cours'])) {
            return back()->with('error', 'Seuls les transferts en attente ou en cours peuvent être modifiés.');
        }

        $validated = $request->validate([
            'depot_source_id' => 'required|exists:depots,id',
            'depot_destination_id' => 'required|exists:depots,id|different:depot_source_id',
            'date_transfert' => 'required|date',
            'statut' => 'required|in:en_attente,en_cours,termine,annule',
            'notes' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.article_id' => 'required|exists:articles,id',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $transfert->update($validated);

            // Supprimer les anciennes lignes et recréer
            $transfert->lignes()->delete();

            foreach ($request->lignes as $ligne) {
                $transfert->lignes()->create([
                    'article_id' => $ligne['article_id'],
                    'quantite' => $ligne['quantite'],
                ]);
            }

            DB::commit();
            return redirect()->route('transferts.show', $transfert)
                ->with('success', 'Transfert modifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transfert $transfert)
    {
        if ($transfert->statut === 'termine') {
            return back()->with('error', 'Impossible de supprimer un transfert terminé.');
        }

        DB::beginTransaction();
        try {
            $transfert->lignes()->delete();
            $transfert->delete();

            DB::commit();
            return redirect()->route('transferts.index')
                ->with('success', 'Transfert supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Exécuter le transfert (valider et mettre à jour les stocks)
     */
    public function executer(Transfert $transfert)
    {
        if ($transfert->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les transferts en attente peuvent être exécutés.');
        }

        DB::beginTransaction();
        try {
            foreach ($transfert->lignes as $ligne) {
                // Vérifier le stock source
                $stockSource = Stock::where('article_id', $ligne->article_id)
                    ->where('depot_id', $transfert->depot_source_id)
                    ->first();

                if (!$stockSource || $stockSource->quantite < $ligne->quantite) {
                    throw new \Exception("Stock insuffisant pour l'article " . $ligne->article->nom);
                }

                // Décrémenter stock source
                $quantiteAvantSource = $stockSource->quantite;
                $stockSource->quantite -= $ligne->quantite;
                $stockSource->save();

                // Mouvement sortie
                MouvementStock::create([
                    'article_id' => $ligne->article_id,
                    'depot_id' => $transfert->depot_source_id,
                    'type' => 'sortie_transfert',
                    'quantite' => -$ligne->quantite,
                    'quantite_avant' => $quantiteAvantSource,
                    'quantite_apres' => $stockSource->quantite,
                    'reference_document' => $transfert->reference,
                    'date_mouvement' => $transfert->date_transfert,
                    'created_by' => auth()->id(),
                ]);

                // Incrémenter stock destination
                $stockDest = Stock::firstOrCreate(
                    [
                        'article_id' => $ligne->article_id,
                        'depot_id' => $transfert->depot_destination_id,
                    ],
                    ['quantite' => 0, 'stock_min' => 0, 'stock_max' => 0]
                );

                $quantiteAvantDest = $stockDest->quantite;
                $stockDest->quantite += $ligne->quantite;
                $stockDest->save();

                // Mouvement entrée
                MouvementStock::create([
                    'article_id' => $ligne->article_id,
                    'depot_id' => $transfert->depot_destination_id,
                    'type' => 'entree_transfert',
                    'quantite' => $ligne->quantite,
                    'quantite_avant' => $quantiteAvantDest,
                    'quantite_apres' => $stockDest->quantite,
                    'reference_document' => $transfert->reference,
                    'date_mouvement' => $transfert->date_transfert,
                    'created_by' => auth()->id(),
                ]);
            }

            $transfert->update(['statut' => 'termine']);

            DB::commit();
            return redirect()->route('transferts.show', $transfert)
                ->with('success', 'Transfert exécuté avec succès. Les stocks ont été mis à jour.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'exécution : ' . $e->getMessage());
        }
    }
}