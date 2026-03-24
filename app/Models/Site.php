<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';

    protected $fillable = [
        'nom',
        'code',
        'ville',
    ];

    /**
     * 🔗 Un site a plusieurs lignes de répartition
     */
    public function repartitionLignes()
    {
        return $this->hasMany(RepartitionLigne::class);
    }

    /**
     * 🔗 Accès aux matériels via répartition
     */
    public function materiels()
    {
        return $this->hasManyThrough(
            Materiel::class,
            RepartitionLigne::class,
            'site_id',      // FK sur repartition_lignes
            'id',           // PK sur materiels
            'id',           // PK sur sites
            'materiel_id'   // FK sur repartition_lignes
        );
    }
}