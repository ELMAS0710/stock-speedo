<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    protected $table = 'depots';

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'telephone',
        'responsable',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function transfertsSource()
    {
        return $this->hasMany(Transfert::class, 'depot_source_id');
    }

    public function transfertsDestination()
    {
        return $this->hasMany(Transfert::class, 'depot_destination_id');
    }
}
