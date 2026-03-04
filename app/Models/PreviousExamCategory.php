<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreviousExamCategory extends Model
{
    protected $guarded = [];
    protected $table = 'previous_exam_categories';


    public function previousExam()
    {
        return $this->hasMany(PreviousExam::class, 'previous_exam_category_id');
    }
}
