<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('entreprise', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        // Filtre actif/inactif
        if ($request->filled('statut')) {
            $query->where('is_active', $request->statut === 'actif');
        }

        $clients = $query->orderBy('nom')->paginate(20);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'entreprise' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès!');
    }

    public function show(Client $client)
    {
        $client->load(['devis', 'bonsCommande', 'bonsLivraison']);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'entreprise' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès!');
    }

    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return redirect()->route('clients.index')
                ->with('success', 'Client supprimé avec succès!');
        } catch (\Exception $e) {
            return redirect()->route('clients.index')
                ->with('error', 'Impossible de supprimer ce client car il est lié à des documents.');
        }
    }
}
