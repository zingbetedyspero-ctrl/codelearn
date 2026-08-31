<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionReponse extends Model
{
    protected $table = 'options_reponse';

    protected $fillable = ['question_id', 'option_texte', 'is_correct'];

    protected function casts(): array
    {
        return ['is_correct' => 'boolean'];
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
