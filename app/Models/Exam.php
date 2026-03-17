<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = [];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot(['mark', 'negative_mark', ])
            ->orderBy('exam_questions.id', 'asc');
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
    public function negativeMark()
    {
        return $this->belongsTo(NegativeMark::class, 'negative_mark_id');
    }
}
