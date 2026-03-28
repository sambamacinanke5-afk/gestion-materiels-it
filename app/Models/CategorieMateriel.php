<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieMateriel extends Model
{
    protected $table = 'categories_materiel';

    protected $fillable = [
        'nom'
    ];
}
