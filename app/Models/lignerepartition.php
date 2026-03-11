<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneRepartition extends Model
{
    protected $table = 'lignerepartitions';

    protected $fillable = [
        'repartition_id',
        'num_ligne',
        'service_id',
        'destinataire',
        'quantite',
        'lignebondelivraison_id'
    ];

    protected $casts = [
        'lignebondelivraison_id' => 'array'
    ];

    public function repartition()
    {
        return $this->belongsTo(Repartition::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function ligneBLs()
    {
        // Ici, si tu stockes les IDs dans un champ JSON comme "lignebondelivraison_id"
        return $this->hasMany(LigneBondelivraison::class, 'id', 'lignebondelivraison_id');
    }
}
