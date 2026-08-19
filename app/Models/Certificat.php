<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificat extends Model
{
    protected $fillable = ['inscriptions_cour_id', 'score_final', 'fichier_pdf', 'code_verification'];

    public function inscription()
    {
        return $this->belongsTo(InscriptionCours::class, 'inscriptions_cour_id');
    }
}
