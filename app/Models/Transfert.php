<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'depot_source_id',
        'depot_destination_id',
        'date_transfert',
        'statut',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date_transfert' => 'date',
    ];

    public function depotSource()
    {
        return $this->belongsTo(Depot::class, 'depot_source_id');
    }

    public function depotDestination()
    {
        return $this->belongsTo(Depot::class, 'depot_destination_id');
    }

    public function lignes()
    {
        return $this->hasMany(LigneTransfert::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function genererReference()
    {
        $dernier = static::orderBy('id', 'desc')->first();
        $numero = $dernier ? $dernier->id + 1 : 1;
        return 'TRF-' . date('Ymd') . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
