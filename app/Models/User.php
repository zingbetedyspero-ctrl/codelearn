<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs assignables en masse.
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'statut_compte',
        'role',
    ];

    /**
     * Les attributs à cacher lors de la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs à caster.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function cours()
    {
        // Cours créés par cet utilisateur (si administrateur)
        return $this->hasMany(Cour::class);
    }

    public function payements()
    {
        return $this->hasMany(Payement::class);
    }

    public function tentativesEvaluation()
    {
        return $this->hasMany(TentativeEvaluation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers rôle / statut
    |--------------------------------------------------------------------------
    */

    public function estAdministrateur(): bool
    {
        return $this->role === 'administrateur';
    }

    public function estApprenant(): bool
    {
        return $this->role === 'apprenant';
    }

    public function estActif(): bool
    {
        return $this->statut_compte === 'actif';
    }

    public function nomComplet(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }
}