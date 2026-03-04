<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = [];

        public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot(['mark','negative_mark','question_order'])
            ->orderBy('exam_questions.question_order');
    }

    public function settings()
    {
        return $this->hasOne(ExamSetting::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
