<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapitre extends Model
{
    protected $fillable = ['cour_id', 'titre', 'contenu', 'ordre_affichage'];

    public function cour()
    {
        return $this->belongsTo(Cour::class, 'cour_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'chapitre_id');
    }
}
