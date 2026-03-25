<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'route',
        'icon',
        'permission_name',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    // Parent
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Enfants (sous-menus)
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
                    ->orderBy('sort_order');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes utiles
    |--------------------------------------------------------------------------
    */

    // Menus principaux
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // Actifs uniquement
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


}