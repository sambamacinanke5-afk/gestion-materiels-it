<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneRepartition extends Model
{
    use HasFactory;

    protected $table = 'lignerepartitions';

    protected $fillable = [
        'repartition_id',
        'service_id',
        'destinataire',
        'quantite',
        'num',
        'lignebondelivraison_id',
    ];

    // Relation vers les matériels (many-to-many)
    public function materiels()
    {
        return $this->belongsToMany(
            Materiel::class,
            'lignerepartition_materiel', // table pivot
            'lignerepartition_id',       // clé étrangère vers cette table
            'materiel_id'                // clé étrangère vers Materiel
        );
    }

    // Relation vers le service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
