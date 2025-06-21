<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
   protected $fillable = ['name', 'level', 'school_id', 'combination_id'];

public function schools()
{
    return $this->belongsToMany(School::class, 'program_school');
}


    public function combinations()
    {
        return $this->belongsToMany(Combination::class, 'combination_program');
    }


public function jobList()
{
    return $this->belongsToMany(JobList::class, 'job_program');
}
}
