<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\BonLivraison;
use App\Models\Stock;
use App\Models\Depot;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Nombre total de clients
        $totalClients = Client::where('is_active', true)->count();

        // Statistiques des devis
        $totalBonsLivraison = BonLivraison::count();
        $devisValides = Devis::where('statut', 'valide')->count();
        $devisTotal = Devis::count();

        // Stock total par dépôt
        $stockParDepot = Depot::withSum('stocks', 'quantite')
            ->where('is_active', true)
            ->get();

        // Articles les plus sortis (derniers 30 jours)
        $articlesPlusSortis = MouvementStock::select('article_id', DB::raw('ABS(SUM(quantite)) as total_sortie'))
            ->where('quantite', '<', 0)
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('article_id')
            ->orderByDesc('total_sortie')
            ->with('article')
            ->limit(10)
            ->get();

        // Stock total
        $stockTotal = Stock::sum('quantite');

        // Derniers mouvements
        $derniersMouvements = MouvementStock::select('*')
            ->with(['article', 'depot', 'createdBy'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard-new', compact(
            'totalClients',
            'totalBonsLivraison',
            'devisValides',
            'devisTotal',
            'stockParDepot',
            'articlesPlusSortis',
            'stockTotal',
            'derniersMouvements'
        ));
    }
}
