<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneTransfert extends Model
{
    use HasFactory;

    protected $table = 'lignes_transfert';

    public $timestamps = false;

    protected $fillable = [
        'transfert_id',
        'article_id',
        'quantite',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
    ];

    public function transfert()
    {
        return $this->belongsTo(Transfert::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
