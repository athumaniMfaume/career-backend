<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobList extends Model
{
    protected $fillable = ['title'];
    protected $table = 'jobs_listing';

public function programs()
{
    return $this->belongsToMany(Program::class, 'job_program', 'job_id', 'program_id');
}


    public function careers()
    {
        return $this->hasMany(Career::class,'job_id');
    }
}
