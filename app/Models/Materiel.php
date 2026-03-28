<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    protected $fillable = [
        'bon_livraison_id',
        'categorie_id',
        'marque_id',
        'typemateriel_id',
        'code_inventaire',
        'numero_serie',
        'modele',
        'statut',
        'site_id',
        'service_id',
        'beneficiaire_id',
        'responsable_actuel',
        'date_reception',
        'date_validation',
        'date_repartition',
        'date_deploiement',
        'created_by'
    ];

    public function marque()
    {
        return $this->belongsTo(Marque::class);
    }

    public function typemateriel()
    {
        return $this->belongsTo(TypeMateriel::class, 'typemateriel_id');
    }

   public function categorie()
{
    return $this->belongsTo(CategorieMateriel::class, 'categorie_id');
}

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function beneficiaire()
    {
        return $this->belongsTo(User::class, 'beneficiaire_id');
    }

    public function repartitions()
    {
        return $this->hasMany(Repartition::class);
    }
}
