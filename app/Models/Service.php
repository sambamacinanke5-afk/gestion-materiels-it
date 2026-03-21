<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // 🔹 Nom de la table (optionnel si le nom suit la convention)
    protected $table = 'services';

    // 🔹 Champs pouvant être remplis en masse
    protected $fillable = [
        'Designation',
    ];

    // 🔹 Exemple de relation (à activer selon ton besoin)
    // Si un service a plusieurs employés ou agents :
    // public function employes()
    // {
    //     return $this->hasMany(Employe::class);
    // }
    public function lignerepartitions()
{
    return $this->hasMany(Lignerepartition::class, 'services_id');
}

}
