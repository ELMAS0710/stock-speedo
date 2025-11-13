<?php

namespace App\Http\Controllers;

use App\Models\Famille;
use Illuminate\Http\Request;

class FamilleController extends Controller
{
    public function index()
    {
        $familles = Famille::withCount('articles')->orderBy('nom')->get();
        return view('familles.index', compact('familles'));
    }

    public function create()
    {
        return view('familles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:191',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Famille::create($validated);

        return redirect()->route('familles.index')
            ->with('success', 'Famille créée avec succès!');
    }

    public function show(Famille $famille)
    {
        $famille->load('articles');
        return view('familles.show', compact('famille'));
    }

    public function edit(Famille $famille)
    {
        return view('familles.edit', compact('famille'));
    }

    public function update(Request $request, Famille $famille)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:191',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $famille->update($validated);

        return redirect()->route('familles.index')
            ->with('success', 'Famille mise à jour avec succès!');
    }

    public function destroy(Famille $famille)
    {
        if ($famille->articles()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cette famille car elle contient des articles.');
        }

        $famille->delete();

        return redirect()->route('familles.index')
            ->with('success', 'Famille supprimée avec succès!');
    }
}
