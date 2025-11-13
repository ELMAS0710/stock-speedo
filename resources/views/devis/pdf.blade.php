<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis {{ $devis->reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
        }
        .container {
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #0066cc;
            font-size: 28pt;
            margin-bottom: 5px;
        }
        .header .company-name {
            font-size: 16pt;
            color: #666;
            font-weight: bold;
        }
        .info-section {
            margin-bottom: 25px;
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
        }
        .info-box {
            background: #f8f9fa;
            padding: 12px;
            border-left: 3px solid #0066cc;
        }
        .info-box h3 {
            color: #0066cc;
            font-size: 12pt;
            margin-bottom: 8px;
        }
        .info-box p {
            margin: 3px 0;
            font-size: 10pt;
        }
        .info-box strong {
            color: #333;
            display: inline-block;
            min-width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #0066cc;
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
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }
        .totals table {
            margin-bottom: 0;
        }
        .totals td {
            padding: 6px 10px;
            border: none;
        }
        .totals .total-row {
            background-color: #0066cc;
            color: white;
            font-weight: bold;
            font-size: 12pt;
        }
        .total-row td {
            padding: 10px !important;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #fff9e6;
            border-left: 3px solid #ffc107;
        }
        .notes h4 {
            color: #856404;
            margin-bottom: 8px;
        }
        .notes p {
            font-size: 9pt;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            font-size: 9pt;
            color: #666;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-brouillon {
            background: #6c757d;
            color: white;
        }
        .status-en_attente {
            background: #ffc107;
            color: #000;
        }
        .status-valide {
            background: #28a745;
            color: white;
        }
        .status-refuse {
            background: #dc3545;
            color: white;
        }
        .status-expire {
            background: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header"><div style="text-align: center; margin-bottom: 20px;"><img src="{{ public_path('images/entete.png') }}" style="width: 100%; max-width: 800px; height: auto;"></div>
            <h1>DEVIS</h1>
            <div class="company-name">SPEEDO GESTION STOCK</div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-col">
                    <div class="info-box">
                        <h3>Informations Devis</h3>
                        <p><strong>Référence :</strong> {{ $devis->reference }}</p>
                        @if($devis->reference_marche)
                        <p><strong>Référence Marché :</strong> <span style="color: #0066cc; font-weight: bold;">{{ $devis->reference_marche }}</span></p>
                        @endif
                        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($devis->date_devis)->format('d/m/Y') }}</p>
                        <p><strong>Validité :</strong> {{ \Carbon\Carbon::parse($devis->date_validite)->format('d/m/Y') }}</p>
                        <p>
                            <strong>Statut :</strong> 
                            @php
                                $statusLabels = [
                                    'brouillon' => 'Brouillon',
                                    'en_attente' => 'En Attente',
                                    'valide' => 'Validé',
                                    'refuse' => 'Refusé',
                                    'expire' => 'Expiré'
                                ];
                            @endphp
                            <span class="status-badge status-{{ $devis->statut }}">
                                {{ $statusLabels[$devis->statut] ?? $devis->statut }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="info-col" style="padding-left: 15px;">
                    <div class="info-box">
                        <h3>Client</h3>
                        <p><strong>Nom :</strong> {{ $devis->nom_client }} {{ $devis->prenom_client }}</p>
                        @if($devis->client)
                            <p><strong>Email :</strong> {{ $devis->client->email ?? '-' }}</p>
                            <p><strong>Téléphone :</strong> {{ $devis->client->telephone ?? '-' }}</p>
                            @if($devis->client->adresse)
                                <p><strong>Adresse :</strong> {{ $devis->client->adresse }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles Table -->
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
                @foreach($devis->lignes as $index => $ligne)
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
                        <td class="text-right">{{ number_format($ligne->montant_total, 2, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr>
                    <td><strong>Total HT :</strong></td>
                    <td class="text-right">{{ number_format($devis->montant_ht, 2, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td><strong>TVA ({{ number_format($devis->tva, 2, ',', ' ') }}%) :</strong></td>
                    <td class="text-right">{{ number_format($devis->montant_tva, 2, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total TTC :</strong></td>
                    <td class="text-right"><strong>{{ number_format($devis->montant_ttc, 2, ',', ' ') }} FCFA</strong></td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        @if($devis->notes)
            <div class="notes">
                <h4>Notes / Conditions</h4>
                <p>{{ $devis->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Merci pour votre confiance</p>
            <p style="margin-top: 5px;">
                Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}
                @if($devis->createdBy)
                    par {{ $devis->createdBy->name }}
                @endif
            </p>
        </div>
    </div>
</body>
</html>
