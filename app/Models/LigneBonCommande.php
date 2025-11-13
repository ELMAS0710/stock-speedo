<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneBonCommande extends Model
{
    protected $table = 'lignes_bon_commande';
    
    public $timestamps = false;

    protected $fillable = [
        'bon_commande_id',
        'article_id',
        'quantite',
        'prix_unitaire',
        'taux_tva',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
    ];

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
