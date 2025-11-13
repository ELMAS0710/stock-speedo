<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneDevis extends Model
{
    use HasFactory;

    protected $table = 'lignes_devis';
    public $timestamps = false;
    
    protected $fillable = [
        'devis_id',
        'article_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
    ];

    // Relations
    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // Accesseur pour le total HT
    public function getTotalHtAttribute()
    {
        return $this->montant_total;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($ligne) {
            $ligne->montant_total = $ligne->quantite * $ligne->prix_unitaire;
        });

        static::saved(function ($ligne) {
            $ligne->devis->calculerTotaux();
        });

        static::deleted(function ($ligne) {
            $ligne->devis->calculerTotaux();
        });
    }
}
