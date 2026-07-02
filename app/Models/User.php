<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Récupérer le rôle de l'employé
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Vérifier si l'utilisateur possède un rôle spécifique (ou plusieurs)
     * Exemple : $user->hasRole('admin') ou $user->hasRole(['admin', 'styliste'])
     */
    public function hasRole($roles)
    {
        // Si l'utilisateur n'a pas de relation de rôle, on refuse d'office
        if (!$this->role) {
            return false;
        }

        // On nettoie et récupère le nom du rôle en minuscules (ex: "admin")
        $userRoleName = strtolower($this->role->name);

        // Si on passe un tableau de rôles à vérifier
        if (is_array($roles)) {
            return in_array($userRoleName, array_map('strtolower', $roles));
        }

        // Si on passe une simple chaîne de caractères
        return $userRoleName === strtolower($roles);
    }

    /**
     * Obtenir toutes les fiches de mesures/commandes assignées à cet artisan.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}