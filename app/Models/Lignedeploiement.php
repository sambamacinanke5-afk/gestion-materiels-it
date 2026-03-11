<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneDeploiement extends Model
{
    use HasFactory;

    protected $table = 'ligne_deploiements';

    protected $fillable = [
        'deploiement_id',
        'lignebondelivraison_id',
    ];

    /**
     * 🔗 Relation vers le déploiement
     */
    public function deploiement()
    {
        return $this->belongsTo(Deploiement::class, 'deploiement_id');
    }

    /**
     * 🔗 Relation vers la ligne du bon de livraison
     */
    public function ligneBondeLivraison()
    {
        return $this->belongsTo(LigneBondeLivraison::class, 'lignebondelivraison_id');
    }
    // Relation vers la ligne de bon de livraison
    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'materiel_id');
    }
}
