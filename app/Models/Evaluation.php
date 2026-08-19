<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'cour_id', 'chapitre_id', 'titre', 'type_evaluation', 'seuil_reussite', 'duree_max',
    ];

    public function cour()
    {
        return $this->belongsTo(Cour::class, 'cour_id');
    }

    public function chapitre()
    {
        return $this->belongsTo(Chapitre::class, 'chapitre_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
