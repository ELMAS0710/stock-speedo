@extends('layouts.main')

@section('title', 'Détails de la famille')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Détails de la famille</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('familles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('familles.edit', $famille) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations générales</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nom:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $famille->nom }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $famille->description ?? 'Aucune description' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Statut:</strong>
                        </div>
                        <div class="col-md-8">
                            @if($famille->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Date de création:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $famille->created_at ? $famille->created_at->format('d/m/Y H:i') : '-' }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>Dernière modification:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $famille->updated_at ? $famille->updated_at->format('d/m/Y H:i') : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-primary mb-0">{{ $famille->articles->count() }}</h3>
                                <small class="text-muted">Articles</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-success mb-0">{{ $famille->articles->where('is_active', 1)->count() }}</h3>
                                <small class="text-muted">Articles actifs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Articles de cette famille</h5>
                </div>
                <div class="card-body">
                    @if($famille->articles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover data-table">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Nom</th>
                                        <th>Unité</th>
                                        <th>Prix d'achat</th>
                                        <th>Prix de vente</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($famille->articles as $article)
                                    <tr>
                                        <td>{{ $article->reference }}</td>
                                        <td>{{ $article->nom }}</td>
                                        <td>{{ $article->unite }}</td>
                                        <td>{{ number_format($article->prix_achat, 2, ',', ' ') }} DH</td>
                                        <td>{{ number_format($article->prix_vente, 2, ',', ' ') }} DH</td>
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
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Aucun article dans cette famille.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
