<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['title'];
    protected $table = 'jobs_listing';

    public function program()
    {
        return $this->belongsTo(Program::class,'program_id');
    }

    public function careers()
    {
        return $this->hasMany(Career::class,'job_id');
    }
}
