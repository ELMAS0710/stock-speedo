<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon de Commande {{ $bonCommande->reference }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
        }
        .container { padding: 20px; }
        .header {
            margin-bottom: 30px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #28a745;
            font-size: 28pt;
            margin-bottom: 5px;
        }
        .header .company-name {
            font-size: 16pt;
            color: #666;
            font-weight: bold;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .info-box {
            background: #f8f9fa;
            padding: 12px;
            border-left: 3px solid #28a745;
        }
        .info-box h3 {
            color: #28a745;
            font-size: 12pt;
            margin-bottom: 8px;
        }
        .info-box p {
            margin: 3px 0;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #28a745;
            color: white;
        }
        thead th {
            padding: 10px;
            text-align: left;
            font-size: 10pt;
            font-weight: bold;
        }
        tbody tr {
            border-bottom: 1px solid #dee2e6;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tbody td {
            padding: 8px 10px;
            font-size: 10pt;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .totals table {
            margin: 0;
        }
        .totals td {
            padding: 5px 10px;
            font-size: 11pt;
        }
        .total-row {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header"><div style="text-align: center; margin-bottom: 20px;"><img src="{{ public_path('images/entete.png') }}" style="width: 100%; max-width: 800px; height: auto;"></div>
            <h1>BON DE COMMANDE</h1>
            <div class="company-name">Speedo Gestion Stock</div>
        </div>

        <!-- Informations -->
        <div class="info-row">
            <div class="info-col">
                <div class="info-box">
                    <h3>Client</h3>
                    <p><strong>{{ $bonCommande->nom_client }} {{ $bonCommande->prenom_client }}</strong></p>
                    @if($bonCommande->client->telephone)
                        <p>Tél: {{ $bonCommande->client->telephone }}</p>
                    @endif
                    @if($bonCommande->client->email)
                        <p>Email: {{ $bonCommande->client->email }}</p>
                    @endif
                </div>
            </div>
            <div class="info-col">
                <div class="info-box">
                    <h3>Détails Commande</h3>
                    <p><strong>Référence:</strong> {{ $bonCommande->reference }}</p>
                      @if($bonCommande->reference_marche)
                      <p><strong>Référence Marché:</strong> <span style="color: #28a745; font-weight: bold;">{{ $bonCommande->reference_marche }}</span></p>
                      @endif
                    <p><strong>Date:</strong> {{ $bonCommande->date_commande->format('d/m/Y') }}</p>
                    <p><strong>Dépôt:</strong> {{ $bonCommande->depot->nom }}</p>
                    @if($bonCommande->devis)
                        <p><strong>Devis:</strong> {{ $bonCommande->devis->reference }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table des articles -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 40%;">Article</th>
                    <th style="width: 15%;" class="text-center">Quantité</th>
                    <th style="width: 20%;" class="text-right">Prix Unitaire</th>
                    <th style="width: 10%;" class="text-center">TVA</th>
                    <th style="width: 20%;" class="text-right">Total HT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bonCommande->lignes as $index => $ligne)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $ligne->article->nom }}</strong>
                            @if($ligne->article->reference)
                                <br><small style="color: #666;">Réf: {{ $ligne->article->reference }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($ligne->quantite, 0) }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_unitaire, 2, ',', ' ') }} FCFA</td>
                        <td class="text-center">{{ number_format($ligne->taux_tva ?? 0, 0) }}%</td>
                        <td class="text-right">{{ number_format($ligne->total_ht, 2, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="totals">
            <table>
                <tr>
                    <td><strong>Total HT :</strong></td>
                    <td class="text-right">{{ number_format($bonCommande->montant_ht, 2, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td><strong>TVA :</strong></td>
                    <td class="text-right">{{ number_format($bonCommande->montant_tva, 2, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total TTC :</strong></td>
                    <td class="text-right"><strong>{{ number_format($bonCommande->montant_ttc, 2, ',', ' ') }} FCFA</strong></td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        @if($bonCommande->notes)
            <div style="margin-bottom: 20px; padding: 10px; background: #f8f9fa; border-left: 3px solid #28a745;">
                <h4 style="color: #28a745; margin-bottom: 5px;">Notes</h4>
                <p style="font-size: 10pt;">{{ $bonCommande->notes }}</p>
            </div>
        @endif

        <!-- Pied de page -->
        <div class="footer">
            <p>Bon de commande généré le {{ now()->format('d/m/Y à H:i') }}</p>
            <p>Speedo Gestion Stock - Système de gestion de stock</p>
        </div>
    </div>
</body>
</html>
