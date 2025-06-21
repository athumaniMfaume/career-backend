<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name', 'type', 'education_level', 'location'];

public function combinations()
{
    return $this->belongsToMany(Combination::class, 'combination_school');
}

public function programs()
{
    return $this->belongsToMany(Program::class, 'program_school');
}

    public function locations()
{
    return $this->belongsToMany(Location::class, 'location_school');
}
}
