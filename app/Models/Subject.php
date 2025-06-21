<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name'];

    public function combinations()
    {
         return $this->belongsToMany(Combination::class, 'combination_subject');
    }
}
