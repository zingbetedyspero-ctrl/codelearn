<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TentativeEvaluation extends Model
{
    protected $table = 'tentatives_evaluation';

    protected $fillable = ['user_id', 'evaluation_id', 'temps_effectuer', 'score', 'statut', 'numero_tentative'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function reponses()
    {
        return $this->hasMany(ReponseUtilisateur::class, 'tentative_id');
    }
}
