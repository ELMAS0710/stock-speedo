<?php

namespace App\Http\Controllers;

use App\Models\BonLivraison;
use App\Models\Devis;
use App\Models\Client;
use App\Services\StockService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BonLivraisonController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $query = BonLivraison::with(['client', 'devis', 'lignes']);

        // Filtre par client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par date début
        if ($request->filled('date_debut')) {
            $query->whereDate('date_livraison', '>=', $request->date_debut);
        }

        // Filtre par date fin
        if ($request->filled('date_fin')) {
            $query->whereDate('date_livraison', '<=', $request->date_fin);
        }

        $bonsLivraison = $query->orderByDesc('created_at')->get();
        $clients = Client::orderBy('nom')->get();

        return view('bons-livraison.index', compact('bonsLivraison', 'clients'));
    }

    public function create(Request $request)
    {
        $clients = Client::where('is_active', true)->orderBy('nom')->get();
        $devisId = $request->input('devis_id');
        $devis = $devisId ? Devis::with('lignes.article')->find($devisId) : null;
        
        return view('bons-livraison.create', compact('clients', 'devis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'devis_id' => 'nullable|exists:devis,id',
            'date_livraison' => 'required|date',
            'depot_id' => 'required|exists:depots,id',
            'lignes' => 'required|array|min:1',
            'lignes.*.article_id' => 'required|exists:articles,id',
            'lignes.*.quantite' => 'required|integer|min:1',
        ]);

        $client = Client::findOrFail($validated['client_id']);

        $bonLivraison = BonLivraison::create([
            'reference' => BonLivraison::genererReference(),
            'client_id' => $validated['client_id'],
            'nom_client' => $client->nom,
            'prenom_client' => $client->prenom,
            'devis_id' => $validated['devis_id'] ?? null,
            'depot_id' => $validated['depot_id'],
            'date_livraison' => $validated['date_livraison'],
            'statut' => 'en_preparation',
            'created_by' => auth()->id(),
        ]);

        foreach ($validated['lignes'] as $ligne) {
            $bonLivraison->lignes()->create([
                'article_id' => $ligne['article_id'],
                'quantite' => $ligne['quantite'],
            ]);
        }

        return redirect()->route('bons-livraison.show', $bonLivraison)
            ->with('success', 'Bon de livraison créé avec succès!');
    }

    public function show(BonLivraison $bonLivraison)
    {
        $bonLivraison->load(['client', 'devis', 'depot', 'lignes.article', 'createdBy']);

        // Alias for backward compatibility with view
        $bonsLivraison = $bonLivraison;
        return view('bons-livraison.show', compact('bonsLivraison'));
    }

    public function livrer(BonLivraison $bonLivraison)
    {
        if ($bonLivraison->statut === 'livre') {
            return back()->with('error', 'Ce bon de livraison a déjà été livré.');
        }

        try {
            // Pas d'impact stock ici car déjà fait lors de la validation du BC
            $bonLivraison->update([
                'statut' => 'livre',
                'date_livraison_effective' => now(),
            ]);

            return back()->with('success', 'Bon de livraison marqué comme livré!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function generatePdf(BonLivraison $bonLivraison)
    {
        $bonLivraison->load(['client', 'depot', 'lignes.article', 'createdBy', 'devis']);

        $pdf = Pdf::loadView('bons-livraison.pdf', compact('bonLivraison'));
        return $pdf->download('bl_' . $bonLivraison->reference . '.pdf');
    }

    // Créer un BL à partir d'un BC (sans impact stock car déjà fait lors validation BC)
    public function createFromBonCommande(\App\Models\BonCommande $bonCommande)
    {
        if ($bonCommande->statut !== 'validee') {
            return back()->with('error', 'Seuls les bons de commande validés peuvent générer un bon de livraison.');
        }

        if ($bonCommande->bonLivraison) {
            return back()->with('error', 'Ce bon de commande a déjà un bon de livraison.');
        }

        return view('bons-livraison.create-from-bc', compact('bonCommande'));
    }

    public function storeFromBonCommande(Request $request, \App\Models\BonCommande $bonCommande)
    {
        $validated = $request->validate([
            'date_livraison' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            \DB::beginTransaction();

            // Créer le bon de livraison SANS IMPACT STOCK
            $bonLivraison = BonLivraison::create([
                'reference' => BonLivraison::genererReference(),
                'reference_marche' => $bonCommande->reference_marche,
                'client_id' => $bonCommande->client_id,
                'nom_client' => $bonCommande->nom_client,
                'prenom_client' => $bonCommande->prenom_client,
                'devis_id' => $bonCommande->devis_id,
                'bon_commande_id' => $bonCommande->id,
                'depot_id' => $bonCommande->depot_id,
                'date_livraison' => $validated['date_livraison'],
                'statut' => 'en_preparation',
                'notes' => $validated['notes'],
                'created_by' => auth()->id(),
            ]);

            // Copier les lignes du BC
            foreach ($bonCommande->lignes as $ligneBC) {
                $bonLivraison->lignes()->create([
                    'article_id' => $ligneBC->article_id,
                    'quantite' => $ligneBC->quantite,
                ]);
            }

            // Mettre à jour le statut du BC
            $bonCommande->update(['statut' => 'livree']);

            \DB::commit();

            return redirect()->route('bons-livraison.show', $bonLivraison)
                ->with('success', 'Bon de livraison créé avec succès!');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}