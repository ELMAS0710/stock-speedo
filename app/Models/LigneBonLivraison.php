<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneBonLivraison extends Model
{
    use HasFactory;

    protected $table = 'lignes_bon_livraison';
    
    public $timestamps = false;
    
    protected $fillable = [
        'bon_livraison_id',
        'article_id',
        'quantite',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
    ];

    // Relations
    public function bonLivraison()
    {
        return $this->belongsTo(BonLivraison::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

}
