<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementMateriel extends Model
{
    protected $table = 'mouvements_materiel';

    protected $fillable = [
        'materiel_id',
        'type_mouvement',
        'ancien_statut',
        'nouveau_statut',
        'user_id',
        'commentaire',
    ];

    /**
     * Le matériel concerné par le mouvement
     */
    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'materiel_id');
    }

    /**
     * L'utilisateur qui a effectué l'action
     */
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}