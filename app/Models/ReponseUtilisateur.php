<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReponseUtilisateur extends Model
{
    protected $table = 'reponses_utilisateur';

    protected $fillable = ['tentative_id', 'question_id', 'reponse', 'note_obtenue', 'date_reponse'];

    public function tentative()
    {
        return $this->belongsTo(TentativeEvaluation::class, 'tentative_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
