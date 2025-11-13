<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Client;
use App\Models\Article;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DevisController extends Controller
{
    public function index(Request $request)
    {
        $query = Devis::with(['client', 'lignes']);

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $devis = $query->orderByDesc('created_at')->get();
        $clients = Client::where('is_active', true)->orderBy('nom')->get();

        return view('devis.index', compact('devis', 'clients'));
    }

    public function create()
    {
        $clients = Client::where('is_active', true)->orderBy('nom')->get();
        $articles = Article::where('is_active', true)->orderBy('nom')->get();
        
        return view('devis.create', compact('clients', 'articles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date_devis' => 'required|date',
            'date_validite' => 'required|date|after_or_equal:date_devis',
            'conditions' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.article_id' => 'required|exists:articles,id',
            'lignes.*.quantite' => 'required|integer|min:1',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        $client = Client::findOrFail($validated['client_id']);

        $devis = Devis::create([
            'reference' => Devis::genererReference(),
            'client_id' => $validated['client_id'],
            'nom_client' => $client->nom,
            'prenom_client' => $client->prenom,
            'date_devis' => $validated['date_devis'],
            'date_validite' => $validated['date_validite'],
            'conditions' => $validated['conditions'] ?? null,
            'statut' => 'brouillon',
            'created_by' => auth()->id(),
        ]);

        foreach ($validated['lignes'] as $ligne) {
            $article = Article::find($ligne['article_id']);
            $devis->lignes()->create([
                'article_id' => $ligne['article_id'],
                'quantite' => $ligne['quantite'],
                'prix_unitaire' => $ligne['prix_unitaire'],
                'remise' => 0,
                'taux_tva' => $article->taux_tva,
            ]);
        }

        $devis->calculerTotaux();

        return redirect()->route('devis.show', $devis)
            ->with('success', 'Devis créé avec succès!');
    }

    public function show(Devis $devis)
    {
        $devis->load(['client', 'lignes.article', 'createdBy']);

        return view('devis.show', compact('devis'));
    }

    public function valider(Devis $devis)
    {
        if ($devis->statut !== 'brouillon') {
            return back()->with('error', 'Ce devis a déjà été validé.');
        }

        $devis->update(['statut' => 'valide']);

        return back()->with('success', 'Devis validé avec succès!');
    }

    public function generatePdf(Devis $devis)
    {
        $devis->load(['client', 'lignes.article']);

        $pdf = Pdf::loadView('devis.pdf', compact('devis'));
        return $pdf->download('devis_' . $devis->reference . '.pdf');
    }
}
