<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscriptionCours extends Model
{
    protected $table = 'inscriptions_cours';

    protected $fillable = ['payement_id', 'statut', 'progression'];

    public function payement()
    {
        return $this->belongsTo(Payement::class);
    }

    public function chapitreDebloqueJusqua(): int
    {
        return $this->progression ?? 2;
    }

    public function certificat()
    {
        return $this->hasOne(Certificat::class, 'inscriptions_cour_id');
    }

    public function apprenant()
    {
        return $this->payement->user ?? null;
    }
}
