<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'nom',
        'code_barre',
        'description',
        'famille_id',
        'unite',
        'prix_achat',
        'prix_vente',
        'taux_tva',
        'is_active',
    ];

    protected $casts = [
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function famille()
    {
        return $this->belongsTo(Famille::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }
}
