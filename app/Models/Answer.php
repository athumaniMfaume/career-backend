<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question_id', 'answer_text', 'career_id'];

    public function question()
    {
        return $this->belongsTo(Question::class,'question_id');
    }

    public function career()
    {
        return $this->belongsTo(Career::class,'career_id');
    }
}
