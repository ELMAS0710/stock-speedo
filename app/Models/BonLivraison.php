<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BonLivraison extends Model
{
    use HasFactory;

    protected $table = 'bons_livraison';

    protected $fillable = [
        'reference',
        'reference_marche',
        'devis_id',
        'bon_commande_id',
        'client_id',
        'nom_client',
        'prenom_client',
        'depot_id',
        'date_livraison',
        'statut',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date_livraison' => 'date',
    ];

    // Relations
    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneBonLivraison::class);
    }

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Méthodes utilitaires
    public static function genererReference()
    {
        $date = Carbon::now()->format('Ymd');
        $dernierBon = self::whereDate('created_at', Carbon::today())->latest()->first();
        $numero = $dernierBon ? (int) substr($dernierBon->reference, -4) + 1 : 1;
        
        return 'BL' . $date . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
