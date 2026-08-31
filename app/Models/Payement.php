<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payement extends Model
{
    protected $table = 'payements';

    protected $fillable = ['user_id', 'cour_id', 'montant', 'reference', 'transaction_id', 'statut_paiement'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cour()
    {
        return $this->belongsTo(Cour::class, 'cour_id');
    }

    public function estApprouve(): bool
    {
        return $this->statut_paiement === 'approved';
    }
}
