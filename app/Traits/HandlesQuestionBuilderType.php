<?php

namespace App\Traits;

use App\Models\PreviousExamCategory;
use App\Models\QuestionCategory;
use App\Models\Year;

trait HandlesQuestionBuilderType
{
    private function loadYear()
    {
        $years = Year::all();

        return view('teacher.pages.partials.question_builder_question_list', [
            'type' => 'year',
            'years' => $years
        ]);
    }
    private function loadJobSolution()
    {
        $categories = PreviousExamCategory::all();

        return view('teacher.pages.partials.question_builder_question_list', [
            'type' => 'job_solution',
            'categories' => $categories
        ]);
    }
    private function loadsubjectWise()
    {
        $subjects = QuestionCategory::whereNull('parent_category_id')->get();

        return view('teacher.pages.partials.question_builder_question_list', [
            'type' => 'subjectWise',
            'subjects' => $subjects
        ]);
    }
}
