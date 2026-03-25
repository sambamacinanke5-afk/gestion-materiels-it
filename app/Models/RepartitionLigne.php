<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepartitionLigne extends Model
{
    protected $table = 'repartition_lignes';

    protected $fillable = [
        'repartition_id',
        'materiel_id',
        'quantite',
        'site_id',
        'statut', // en_attente, en_cours, livre
    ];

    /**
     * 🔗 Relation vers la répartition
     */
    public function repartition()
    {
        return $this->belongsTo(Repartition::class);
    }

    /**
     * 🔗 Relation vers le matériel
     */
    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }

    /**
     * 🔗 Relation vers le site
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * 🔎 Scope : en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * 🔎 Scope : en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * 🔎 Scope : livrées
     */
    public function scopeLivrees($query)
    {
        return $query->where('statut', 'livre');
    }
}