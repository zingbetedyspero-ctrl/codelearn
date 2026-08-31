<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalActivite extends Model
{
    protected $table = 'journal_activites';

    protected $fillable = ['user_id', 'tentative_id', 'type_action', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tentative()
    {
        return $this->belongsTo(TentativeEvaluation::class, 'tentative_id');
    }
}
