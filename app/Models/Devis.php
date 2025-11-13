<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'reference_marche',
        'client_id',
        'nom_client',
        'prenom_client',
        'date_devis',
        'date_validite',
        'statut',
        'montant_ht',
        'tva',
        'montant_tva',
        'montant_ttc',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date_devis' => 'date',
        'date_validite' => 'date',
        'montant_ht' => 'decimal:2',
        'tva' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneDevis::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bonLivraison()
    {
        return $this->hasOne(BonLivraison::class);
    }

    public function bonCommande()
    {
        return $this->hasOne(BonCommande::class);
    }

    // Méthodes utilitaires
    public function calculerTotaux()
    {
        $montantHt = $this->lignes->sum('montant_total');
        $montantTva = $montantHt * ($this->tva / 100);
        $montantTtc = $montantHt + $montantTva;

        $this->update([
            'montant_ht' => $montantHt,
            'montant_tva' => $montantTva,
            'montant_ttc' => $montantTtc,
        ]);
    }

    public static function genererReference()
    {
        $date = Carbon::now()->format('Ymd');
        $dernierDevis = self::whereDate('created_at', Carbon::today())->latest()->first();
        $numero = $dernierDevis ? (int) substr($dernierDevis->reference, -4) + 1 : 1;
        
        return 'DEV' . $date . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}