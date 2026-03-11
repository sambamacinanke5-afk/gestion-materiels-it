<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    protected $fillable = [
        'designation',
        'marque_id',
        'typemateriel_id',
        'numero_serie',
    ];

    public function marque()
    {
        return $this->belongsTo(Marque::class, 'marque_id');
    }

    public function typemateriel()
    {
        return $this->belongsTo(TypeMateriel::class, 'typemateriel_id');
    }

    public function ligneBL()
    {
        return $this->hasOne(LigneBondelivraison::class, 'materiel_id');
    }
// App\Models\Materiel.php
public function lignes()
{
    return $this->hasMany(LigneBondelivraison::class, 'materiel_id');
}

public function type()
    {
        return $this->belongsTo(TypeMateriel::class, 'type_materiel_id');
    }
    public function repartitions()
{
    return $this->hasMany(Repartition::class);
}

}
