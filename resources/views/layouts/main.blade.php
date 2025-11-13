<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Speedo Gestion Stock')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-brand { padding: 1.5rem; text-align: center; background: transparent; }
        .menu-item {
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .main-content {
            margin-left: 260px;
            background-color: #f8fafc;
            min-height: 100vh;
        }
        .top-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
        }
        .content-area {
            padding: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <div class="sidebar">
            <div class="sidebar-brand"><img src="{{ asset('images/logo.png') }}" alt="Groupe Speedo" style="max-width: 180px; height: auto; margin: 0 auto;"></div>
            <nav class="py-3">
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('clients.index') }}" class="menu-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Clients
                </a>
                <a href="{{ route('familles.index') }}" class="menu-item {{ request()->routeIs('familles.*') ? 'active' : '' }}">
                    <i class="bi bi-tag me-2"></i> Familles
                </a>
                <a href="{{ route('articles.index') }}" class="menu-item {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                    <i class="bi bi-box me-2"></i> Articles
                </a>
                <a href="{{ route('depots.index') }}" class="menu-item {{ request()->routeIs('depots.*') ? 'active' : '' }}">
                    <i class="bi bi-building me-2"></i> Dépôts
                </a>
                <a href="{{ route('mouvements-stock.index') }}" class="menu-item {{ request()->routeIs('mouvements-stock.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right me-2"></i> Mouvements
                </a>
                <a href="{{ route('transferts.index') }}" class="menu-item {{ request()->routeIs('transferts.*') ? 'active' : '' }}">
                    <i class="bi bi-truck me-2"></i> Transferts
                </a>
                <a href="{{ route('devis.index') }}" class="menu-item {{ request()->routeIs('devis.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Devis
                </a>
                <a href="{{ route('bons-commande.index') }}" class="menu-item {{ request()->routeIs('bons-commande.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check me-2"></i> Bons de Commande
                </a>
                <a href="{{ route('bons-livraison.index') }}" class="menu-item {{ request()->routeIs('bons-livraison.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-check me-2"></i> Bons de livraison
                </a>
            </nav>
        </div>

        <div class="main-content w-100">
            <nav class="top-navbar d-flex justify-content-between align-items-center">
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                <div class="d-flex align-items-center gap-3">
                    <span>{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </nav>

            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
                pageLength: 25
            });
        });
    </script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>



