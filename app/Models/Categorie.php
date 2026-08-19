<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categories';

    protected $fillable = ['nom', 'description'];

    public function cours()
    {
        return $this->hasMany(Cour::class, 'category_id');
    }
}
