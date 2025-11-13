@extends('layouts.main')

@section('title', 'Articles')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Articles</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvel article
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
                            <th>Référence</th>
                            <th>Nom</th>
                            <th>Famille</th>
                            <th>Unité</th>
                            <th>Prix d'achat</th>
                            <th>Prix de vente</th>
                            <th>TVA</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->reference }}</td>
                            <td>{{ $article->nom }}</td>
                            <td>{{ $article->famille ? $article->famille->nom : '-' }}</td>
                            <td>{{ $article->unite }}</td>
                            <td>{{ number_format($article->prix_achat, 2, ',', ' ') }} DH</td>
                            <td>{{ number_format($article->prix_vente, 2, ',', ' ') }} DH</td>
                            <td>{{ $article->taux_tva }}%</td>
                            <td>
                                @if($article->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('articles.show', $article) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('articles.edit', $article) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('articles.destroy', $article) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
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
                            <td colspan="9" class="text-center text-muted py-4">
                                Aucun article trouvé.
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
