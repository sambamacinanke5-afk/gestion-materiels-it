<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 🔗 Relation : un utilisateur a UN SEUL rôle
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * ✅ Vérifier si l'utilisateur a un rôle précis
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * 🔐 Vérifier une permission via le rôle
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role
            && $this->role->permissions
            && $this->role->permissions->contains('name', $permission);
    }
}
