<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bondelivraison extends Model
{
    use HasFactory;
    protected $fillable = [
        'fournisseur_id',
        'bondelivraison',
        'date_livraison',
        'statut',
    ];


    /**
     * 🔹 Fournisseur du bon de livraison
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    /**
     * 🔹 Lignes du bon de livraison
     */
    public function lignes(): HasMany
    {
        return $this->hasMany(LigneBondelivraison::class, 'bondelivraison_id');
    }

    /**
     * 🔹 Matériels via table pivot
     */
    public function materiels(): BelongsToMany
    {
        return $this->belongsToMany(
            Materiel::class,
            'bondelivraison_materiel',
            'bondelivraison_id',
            'materiel_id'
        )->withPivot('quantite');
    }

    /**
     * 🔹 Répartitions liées au bon de livraison
     * ✅ OBLIGATOIRE pour le dashboard
     */
    public function repartitions(): HasMany
    {
        return $this->hasMany(
            Repartition::class,
            'bondelivraison_id',
            'id'
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
