<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonCommande extends Model
{
    use HasFactory;

    protected $table = 'bons_commande';

    protected $fillable = [
        'reference',
        'reference_marche',
        'client_id',
        'nom_client',
        'prenom_client',
        'devis_id',
        'depot_id',
        'date_commande',
        'statut',
        'notes',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'created_by',
    ];

    protected $casts = [
        'date_commande' => 'date',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneBonCommande::class);
    }

    public function bonLivraison()
    {
        return $this->hasOne(BonLivraison::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Génération de référence automatique
    public static function genererReference()
    {
        $year = date('Y');
        $lastBC = self::whereYear('created_at', $year)->latest('id')->first();
        $numero = $lastBC ? intval(substr($lastBC->reference, -5)) + 1 : 1;
        return 'BC' . $year . str_pad($numero, 5, '0', STR_PAD_LEFT);
    }

    // Calcul des totaux
    public function calculerTotaux()
    {
        $montant_ht = 0;
        $montant_tva = 0;

        foreach ($this->lignes as $ligne) {
            $total_ligne = $ligne->quantite * $ligne->prix_unitaire;
            $montant_ht += $total_ligne;
            $montant_tva += $total_ligne * ($ligne->taux_tva / 100);
        }

        $this->update([
            'montant_ht' => $montant_ht,
            'montant_tva' => $montant_tva,
            'montant_ttc' => $montant_ht + $montant_tva,
        ]);
    }
}