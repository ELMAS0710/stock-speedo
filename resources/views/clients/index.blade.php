@extends('layouts.main')

@section('title', 'Clients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Clients</h2>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Client
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Ville</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td><strong>{{ $client->nom }}</strong></td>
                            <td>{{ $client->prenom }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->telephone }}</td>
                            <td>{{ $client->ville }}</td>
                            <td>
                                @if($client->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="bi bi-inbox"></i> Aucun client trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
