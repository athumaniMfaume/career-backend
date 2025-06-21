<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = ['name', 'job_id'];

    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }
}
