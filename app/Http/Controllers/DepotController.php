<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depot;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;

class DepotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depots = Depot::withCount(['stocks'])
            ->orderBy('nom')
            ->get();

        // Calculer la quantité totale de stock
        $totalStock = Stock::sum('quantite');

        return view('depots.index', compact('depots', 'totalStock'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('depots.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'responsable' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20'
        ]);

        Depot::create($validated);

        return redirect()->route('depots.index')
            ->with('success', 'Depot cree avec succes');
    }

    /**
     * Display the specified resource.
     */
    public function show(Depot $depot)
    {
        $stocks = Stock::with(['article.famille'])
            ->where('depot_id', $depot->id)
            ->whereHas('article')
            ->orderBy('quantite', 'asc')
            ->get();

        // Calculer les statistiques
        $totalArticles = $stocks->count();
        $stockCritique = $stocks->filter(function($stock) {
            return $stock->quantite <= $stock->stock_min;
        })->count();
        
        $stockFaible = $stocks->filter(function($stock) {
            return $stock->quantite > $stock->stock_min && $stock->quantite <= ($stock->stock_min * 1.2);
        })->count();

        $valeurTotale = $stocks->sum(function($stock) {
            return $stock->quantite * ($stock->article->prix_vente ?? 0);
        });

        return view('depots.show', compact('depot', 'stocks', 'totalArticles', 'stockCritique', 'stockFaible', 'valeurTotale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Depot $depot)
    {
        return view('depots.edit', compact('depot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Depot $depot)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'responsable' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20'
        ]);

        $depot->update($validated);

        return redirect()->route('depots.index')
            ->with('success', 'Depot mis a jour avec succes');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depot $depot)
    {
        // Vérifier si le dépôt a des stocks
        $hasStock = Stock::where('depot_id', $depot->id)->exists();
        
        if ($hasStock) {
            return redirect()->route('depots.index')
                ->with('error', 'Impossible de supprimer ce depot car il contient des articles en stock.');
        }

        $depot->delete();

        return redirect()->route('depots.index')
            ->with('success', 'Depot supprime avec succes');
    }

    /**
     * Generate PDF for depot stock
     */
    public function generatePdf(Depot $depot)
    {
        $stocks = Stock::with(['article.famille'])
            ->where('depot_id', $depot->id)
            ->whereHas('article')
            ->orderBy('quantite', 'asc')
            ->get();

        $pdf = Pdf::loadView('depots.pdf', [
            'depot' => $depot,
            'stocks' => $stocks,
            'date' => now()->format('d/m/Y H:i')
        ]);

        $filename = 'stock_depot_' . str_replace(' ', '_', $depot->nom) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
