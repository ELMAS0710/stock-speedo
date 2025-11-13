@extends('layouts.main')

@section('title', 'Familles d\'articles')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Familles d'articles</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('familles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle famille
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Nombre d'articles</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($familles as $famille)
                        <tr>
                            <td>{{ $famille->nom }}</td>
                            <td>{{ Str::limit($famille->description, 50) }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $famille->articles_count }} article(s)
                                </span>
                            </td>
                            <td>
                                @if($famille->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('familles.show', $famille) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('familles.edit', $famille) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('familles.destroy', $famille) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette famille ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucune famille d'articles trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
