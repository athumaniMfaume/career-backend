<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combination extends Model
{
    protected $fillable = ['name','shortname'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'combination_subject');
    }

public function schools()
{
    return $this->belongsToMany(School::class, 'combination_school');
}

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'combination_program');
    }
}
