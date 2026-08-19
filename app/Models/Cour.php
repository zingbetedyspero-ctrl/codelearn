<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    use HasFactory;

    protected $table = 'cours';

    protected $fillable = [
        'titre', 'description', 'niveau', 'prix', 'image_couverture',
        'statut', 'user_id', 'category_id',
    ];

    protected function casts(): array
    {
        return ['prix' => 'decimal:2'];
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }

    public function chapitres()
    {
        return $this->hasMany(Chapitre::class, 'cour_id')->orderBy('ordre_affichage');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'cour_id');
    }

    public function payements()
    {
        return $this->hasMany(Payement::class, 'cour_id');
    }

    public function estPublie(): bool
    {
        return $this->statut === 'publie';
    }
}
