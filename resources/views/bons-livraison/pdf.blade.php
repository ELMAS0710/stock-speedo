<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Livraison {{ $bonLivraison->reference }}</title>
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
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
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
        .signature-section {
            margin-top: 50px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 15px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        .signature-box h4 {
            margin-bottom: 50px;
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
        .status-en_preparation {
            background: #ffc107;
            color: #000;
        }
        .status-livre {
            background: #28a745;
            color: white;
        }
        .status-annule {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header"><div style="text-align: center; margin-bottom: 20px;"><img src="{{ public_path('images/entete.png') }}" style="width: 100%; max-width: 800px; height: auto;"></div>
            <h1>BON DE LIVRAISON</h1>
            <div class="company-name">SPEEDO GESTION STOCK</div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-col">
                    <div class="info-box">
                        <h3>Informations Livraison</h3>
                        <p><strong>Référence :</strong> {{ $bonLivraison->reference }}</p>
                        @if($bonLivraison->reference_marche)
                        <p><strong>Référence Marché :</strong> <span style="color: #17a2b8; font-weight: bold;">{{ $bonLivraison->reference_marche }}</span></p>
                        @endif
                        <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($bonLivraison->date_livraison)->format('d/m/Y') }}</p>
                        @if($bonLivraison->devis)
                            <p><strong>Devis :</strong> {{ $bonLivraison->devis->reference }}</p>
                        @endif
                        <p><strong>Dépôt :</strong> {{ $bonLivraison->depot->nom }}</p>
                        <p>
                            <strong>Statut :</strong> 
                            @php
                                $statusLabels = [
                                    'en_preparation' => 'En Préparation',
                                    'livre' => 'Livré',
                                    'annule' => 'Annulé'
                                ];
                            @endphp
                            <span class="status-badge status-{{ $bonLivraison->statut }}">
                                {{ $statusLabels[$bonLivraison->statut] ?? $bonLivraison->statut }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="info-col" style="padding-left: 15px;">
                    <div class="info-box">
                        <h3>Client</h3>
                        <p><strong>Nom :</strong> {{ $bonLivraison->nom_client }} {{ $bonLivraison->prenom_client }}</p>
                        @if($bonLivraison->client)
                            <p><strong>Email :</strong> {{ $bonLivraison->client->email ?? '-' }}</p>
                            <p><strong>Téléphone :</strong> {{ $bonLivraison->client->telephone ?? '-' }}</p>
                            @if($bonLivraison->client->adresse)
                                <p><strong>Adresse :</strong> {{ $bonLivraison->client->adresse }}</p>
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
                    <th style="width: 10%;">#</th>
                    <th style="width: 60%;">Article</th>
                    <th style="width: 30%;" class="text-center">Quantité</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bonLivraison->lignes as $index => $ligne)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $ligne->article->nom }}</strong>
                            @if($ligne->article->reference)
                                <br><small style="color: #666;">Réf: {{ $ligne->article->reference }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($ligne->quantite, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Notes -->
        @if($bonLivraison->notes)
            <div class="notes">
                <h4>Notes</h4>
                <p>{{ $bonLivraison->notes }}</p>
            </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <h4>Signature du Livreur</h4>
                <p>Date : ________________</p>
            </div>
            <div class="signature-box" style="padding-left: 15px;">
                <h4>Signature du Client</h4>
                <p>Date : ________________</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Merci pour votre confiance</p>
            <p style="margin-top: 5px;">
                Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}
                @if($bonLivraison->createdBy)
                    par {{ $bonLivraison->createdBy->name }}
                @endif
            </p>
        </div>
    </div>
</body>
</html>
