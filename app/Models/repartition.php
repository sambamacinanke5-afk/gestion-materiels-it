<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repartition extends Model
{
    protected $fillable = [
        'bondelivraison_id', 'date_repartition',
        'ordinateur_complets','ordinateur_portables',
        'imprimantes','scanners','user_id'
    ];

    public function lignes()
    {
        return $this->hasMany(LigneRepartition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bondelivraison()
    {
        return $this->belongsTo(Bondelivraison::class);
    }
    
}
