<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MouvementStock;
use App\Models\Article;
use App\Models\Depot;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockExport;

class MouvementStockController extends Controller
{

    public function index(Request $request)
    {
        $query = MouvementStock::with(['article', 'depot']);

        // Filtrer par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtrer par article
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }

        // Filtrer par dépôt
        if ($request->filled('depot_id')) {
            $query->where('depot_id', $request->depot_id);
        }

        $mouvements = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Charger les utilisateurs manuellement
        $userIds = $mouvements->pluck('created_by')->unique()->filter();
        $users = \App\Models\User::whereIn('id', $userIds)->get()->keyBy('id');
        
        foreach ($mouvements as $mouvement) {
            $mouvement->user_name = $users->get($mouvement->created_by)->name ?? 'Système';
        }

        return view('mouvements-stock.index', compact('mouvements'));
    }

    public function create()
    {
        $articles = Article::orderBy('nom')->get();
        $depots = Depot::orderBy('nom')->get();

        return view('mouvements-stock.create', compact('articles', 'depots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_mouvement' => 'required|in:entree,sortie',
            'article_id' => 'required|exists:articles,id',
            'depot_id' => 'required|exists:depots,id',
            'quantite' => 'required|numeric|min:0.01',
            'motif' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Récupérer ou créer le stock
            $stock = Stock::firstOrCreate(
                [
                    'article_id' => $validated['article_id'],
                    'depot_id' => $validated['depot_id'],
                ],
                [
                    'quantite' => 0,
                ]
            );

            $quantiteAvant = $stock->quantite;
            
            if ($validated['type_mouvement'] === 'entree') {
                // Ajout au stock
                $stock->quantite += $validated['quantite'];
                $type = 'entree';
                $quantiteMouvement = $validated['quantite']; // Quantité positive pour entrée
            } else {
                // Retrait du stock - vérifier la disponibilité
                if ($stock->quantite < $validated['quantite']) {
                    throw new \Exception('Stock insuffisant. Disponible: ' . $stock->quantite);
                }
                $stock->quantite -= $validated['quantite'];
                $type = 'sortie';
                $quantiteMouvement = -$validated['quantite']; // Quantité négative pour sortie
            }

            $stock->save();

            // Créer le mouvement de stock
            MouvementStock::create([
                'article_id' => $validated['article_id'],
                'depot_id' => $validated['depot_id'],
                'type' => $type,
                'quantite' => $quantiteMouvement,
                'quantite_avant' => $quantiteAvant,
                'quantite_apres' => $stock->quantite,
                'reference_document' => null,
                'motif' => $request->input('motif'),
                'date_mouvement' => now(),
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            
            return redirect()->route('mouvements-stock.index')
                ->with('success', 'Mouvement de stock enregistré avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        return Excel::download(new StockExport, 'stock_' . date('Y-m-d_His') . '.xlsx');
    }

    public function exportPdf()
    {
        // TODO: Implémenter l'export PDF
        return redirect()->back()->with('info', 'Export PDF en cours de développement');
    }
}
