<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deploiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'bondelivraison_id',
        'Datecreation',
        'Direction',
        'Poste',
        'Utilisateur',
        'etat',
        'Nomordinateur',
        'Systeme',
        'Ram',
        'Disque',
        'deploiements'
    ];

    // 🔹 Relation vers le bon de livraison
    public function bondelivraison()
    {
        return $this->belongsTo(Bondelivraison::class);
    }

    // 🔹 Relation vers les lignes de déploiement
    public function ligneDeploiements()
    {
        return $this->hasMany(LigneDeploiement::class, 'deploiement_id');
    }
    // Relation vers les lignes de déploiement

    // Relation vers l'utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Relation vers les lignes de déploiement
    public function lignes()
    {
        // Assurez-vous que le modèle LigneDeploiement existe et la clé étrangère est correcte
        return $this->hasMany(LigneDeploiement::class, 'deploiement_id');
    }

}
