<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Famille;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('famille');

        // Filtrer par famille
        if ($request->filled('famille_id')) {
            $query->where('famille_id', $request->famille_id);
        }

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('is_active', $request->statut);
        }

        $articles = $query->orderBy('nom')->get();
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $familles = Famille::where('is_active', 1)->orderBy('nom')->get();
        return view('articles.create', compact('familles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:articles',
            'nom' => 'required|string|max:200',
            'code_barre' => 'nullable|string|max:50|unique:articles',
            'description' => 'nullable|string',
            'famille_id' => 'required|exists:familles,id',
            'unite' => 'required|string|max:20',
            'prix_achat' => 'nullable|numeric|min:0',
            'prix_vente' => 'nullable|numeric|min:0',
            'taux_tva' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        Article::create($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Article créé avec succès!');
    }

    public function show(Article $article)
    {
        $article->load(['famille', 'stocks.depot']);
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $familles = Famille::where('is_active', 1)->orderBy('nom')->get();
        return view('articles.edit', compact('article', 'familles'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:articles,reference,' . $article->id,
            'nom' => 'required|string|max:200',
            'code_barre' => 'nullable|string|max:50|unique:articles,code_barre,' . $article->id,
            'description' => 'nullable|string',
            'famille_id' => 'required|exists:familles,id',
            'unite' => 'required|string|max:20',
            'prix_achat' => 'nullable|numeric|min:0',
            'prix_vente' => 'nullable|numeric|min:0',
            'taux_tva' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $article->update($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Article mis à jour avec succès!');
    }

    public function destroy(Article $article)
    {
        if ($article->stocks()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cet article car il existe en stock.');
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article supprimé avec succès!');
    }
}
