<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $guarded = [];

    public function question()
{
    return $this->belongsTo(Question::class, 'question_id');
}
}
