<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

   protected $fillable = [
    'nom',
    'contact_nom',
    'telephone',
    'email',
    'adresse',
    'actif',
];

    public function bondelivraisons()
    {
        return $this->hasMany(Bondelivraison::class);
    }
}
