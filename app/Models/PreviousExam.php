<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreviousExam extends Model
{
    protected $guarded = [];
    protected $table = 'previous_exams';

    public function category()
    {
        return $this->belongsTo(PreviousExamCategory::class, 'previous_exam_category_id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class,'previous_exam_questions');
    }
}
