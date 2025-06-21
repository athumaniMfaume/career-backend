<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

     protected $fillable = ['city'];

public function schools()
{
    return $this->belongsToMany(School::class, 'location_school');
}
}
