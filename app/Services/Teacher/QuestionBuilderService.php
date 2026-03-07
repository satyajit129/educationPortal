<?php

namespace App\Services\Teacher;

use App\Models\PreviousExamCategory;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\Year;
use App\Traits\HandlesQuestionBuilderType;
use Illuminate\Support\Facades\Auth;

class QuestionBuilderService
{
    use HandlesQuestionBuilderType;
    public function renderSelectExamQuestion($request)
    {
        $questions = null;
        $type = $request->input('type', 'year');
        $tabData = [];
        if ($type == 'year') {
            $tabData['years'] = Year::all();

            if ($request->has('year_id') && !empty($request->input('year_id'))) {
                $yearIds = $request->input('year_id');
                $questions = Question::with('years')
                    ->whereHas('years', function ($query) use ($yearIds) {
                        $query->whereIn('year_id', $yearIds);
                    })
                    ->paginate(2)
                    ->withQueryString();
            }
        } elseif ($type == 'job_solution') {
            $tabData['categories'] = PreviousExamCategory::all();
        } elseif ($type == 'subjectWise') {
            $tabData['subjects'] = QuestionCategory::whereNull('parent_category_id')->get();
        }

        return view('teacher.pages.question_builder_select', compact('type', 'tabData', 'questions'));
    }
}
