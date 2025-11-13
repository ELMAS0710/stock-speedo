<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock - {{ $depot->nom }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .logo {
            width: 100%;
            margin-bottom: 20px;
        }
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 20px 0;
            padding: 10px;
            background-color: #e0e7ff;
        }
        .info-section {
            background-color: #f3f4f6;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #1e3a8a;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            color: #1e3a8a;
            font-size: 16px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 150px;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        .stats-container {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 15px;
            background-color: #1e3a8a;
            color: white;
            width: 50%;
        }
        .stat-box:first-child {
            margin-right: 10px;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            display: block;
        }
        .stat-label {
            font-size: 14px;
            display: block;
            margin-top: 5px;
        }
        .table-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #1e3a8a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #1e3a8a;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .article-name {
            font-weight: bold;
            color: #111827;
        }
        .article-category {
            color: #6b7280;
            font-size: 10px;
            display: block;
            margin-top: 2px;
        }
        .status-badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .status-disponible {
            background-color: #dcfce7;
            color: #166534;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('images/Entete.png') }}" alt="Groupe Speedo" class="logo">
    
    <div class="title">SPEEDO GESTION STOCK</div>

    <div class="info-section">
        <h3>Informations Dépôt</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom :</div>
                <div class="info-value">{{ $depot->nom }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Adresse :</div>
                <div class="info-value">{{ $depot->adresse ?: 'Non renseignée' }}</div>
            </div>
        </div>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <span class="stat-number">{{ $stocks->count() }}</span>
            <span class="stat-label">Produits Différents</span>
        </div>
        <div class="stat-box">
            <span class="stat-number">{{ $stocks->sum('quantite') }}</span>
            <span class="stat-label">Quantité Totale</span>
        </div>
    </div>

    <div class="table-title">Détail du Stock</div>

    <table>
        <thead>
            <tr>
                <th width="20%">Référence</th>
                <th width="50%">Article</th>
                <th width="15%">Quantité</th>
                <th width="15%">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stocks as $stock)
                <tr>
                    <td>{{ $stock->article->reference }}</td>
                    <td>
                        <span class="article-name">{{ $stock->article->nom }}</span>
                        @if($stock->article->famille)
                            <span class="article-category">{{ $stock->article->famille->nom }}</span>
                        @endif
                    </td>
                    <td>{{ number_format($stock->quantite, 2, ',', ' ') }}</td>
                    <td>
                        <span class="status-badge status-disponible">Disponible</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #6b7280;">
                        Aucun article en stock
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Document généré le {{ $date }} - Groupe Speedo - Gestion de Stock</p>
    </div>
</body>
</html>
