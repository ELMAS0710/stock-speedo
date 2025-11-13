<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'entreprise',
        'email',
        'telephone',
        'adresse',
        'ville',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
    public function devis()
    {
        return $this->hasMany(Devis::class);
    }
    public function bonsCommande()
    {
        return $this->hasMany(BonCommande::class);
    }

    public function bonsLivraison()
    {
        return $this->hasMany(BonLivraison::class);
    }

    // Accesseurs
    public function getNomCompletAttribute()
    {
        return trim("{$this->prenom} {$this->nom}");
    }
}
