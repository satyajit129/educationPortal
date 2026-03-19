<?php

namespace App\Models;

use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasUniqueCode;

    protected $guarded = [];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
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

    public function userAttempts()
    {
        return $this->hasMany(UserAttempt::class, 'exam_id', 'id');
    }
}
