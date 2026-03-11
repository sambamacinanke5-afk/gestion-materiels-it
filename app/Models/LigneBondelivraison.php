<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneBondelivraison extends Model
{
    protected $fillable = ['bondelivraison_id', 'materiel_id', 'quantite'];

    public function bon()
    {
        return $this->belongsTo(BonDeLivraison::class, 'bondelivraison_id');
    }

    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'materiel_id');
    }

    // Les lignes de répartition associées
    public function lignesRepartition()
    {
        return $this->hasMany(LigneRepartition::class, 'lignebondelivraison_id');
    }

    // Lignes de déploiement déjà créées
    public function deploiements()
    {
        return $this->hasMany(LigneDeploiement::class, 'lignebondelivraison_id');
    }

}
