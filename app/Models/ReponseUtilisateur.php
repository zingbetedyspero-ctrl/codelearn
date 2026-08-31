<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReponseUtilisateur extends Model
{
    protected $table = 'reponses_utilisateur';

    protected $fillable = ['tentative_id', 'question_id', 'reponse', 'note_obtenue', 'en_attente_ia', 'date_reponse'];

    protected $casts = [
        'en_attente_ia' => 'boolean',
    ];

    public function tentative()
    {
        return $this->belongsTo(TentativeEvaluation::class, 'tentative_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
