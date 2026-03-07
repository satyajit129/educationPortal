<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function category()
    {
        return $this->belongsTo(QuestionCategory::class, 'category_id');
    }
    public function years()
    {
        return $this->belongsToMany(Year::class, 'year_question');
    }
    public function previousExams()
    {
        return $this->belongsToMany(PreviousExam::class, 'previous_exam_questions');
    }
}
