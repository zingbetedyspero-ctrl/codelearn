<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['evaluation_id', 'enonce', 'type_question', 'temps_reponse', 'bareme'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function optionsReponse()
    {
        return $this->hasMany(OptionReponse::class);
    }

    public function estQcm(): bool
    {
        return $this->type_question === 'qcm';
    }
}
