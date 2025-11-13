<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    protected $table = 'mouvements_stock';
    
    const UPDATED_AT = null; // Pas de updated_at, seulement created_at
    
    protected $fillable = [
        'article_id',
        'depot_id',
        'type_mouvement',
        'quantite',
        'quantite_avant',
        'quantite_apres',
        'reference',
        'reference_type',
        'depot_destination_id',
        'motif',
        'created_by',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'quantite_avant' => 'decimal:2',
        'quantite_apres' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    // Relations
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function depotDestination()
    {
        return $this->belongsTo(Depot::class, 'depot_destination_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}