<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Stock::with(['article.famille', 'depot'])
            ->whereHas('article')
            ->whereHas('depot')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Depot',
            'Article',
            'Reference',
            'Famille',
            'Quantite',
            'Stock Min',
            'Stock Max',
            'Valeur Unitaire',
            'Valeur Totale',
            'Statut'
        ];
    }

    /**
     * @var Stock $stock
     */
    public function map($stock): array
    {
        $valeurUnitaire = $stock->article->prix_vente ?? 0;
        $valeurTotale = $stock->quantite * $valeurUnitaire;
        
        // Determiner le statut
        $statut = 'OK';
        if ($stock->quantite <= $stock->stock_min) {
            $statut = 'Critique';
        } elseif ($stock->quantite <= ($stock->stock_min * 1.2)) {
            $statut = 'Faible';
        } elseif ($stock->quantite >= $stock->stock_max) {
            $statut = 'Surstock';
        }

        return [
            $stock->depot->nom ?? 'N/A',
            $stock->article->nom ?? 'N/A',
            $stock->article->reference ?? 'N/A',
            $stock->article->famille->nom ?? 'N/A',
            $stock->quantite,
            $stock->stock_min,
            $stock->stock_max,
            number_format($valeurUnitaire, 2, ',', ' '),
            number_format($valeurTotale, 2, ',', ' '),
            $statut
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style de l'en-tete
            1 => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'], 
                    'bold' => true,
                    'size' => 12
                ]
            ],
        ];
    }
}