<?php

namespace App\Services\Teacher;

use App\Models\Question;
use App\Models\QuestionCategory;
use Illuminate\Support\Facades\Auth;

class QuestionBuilderService
{
    public function renderSelectExamQuestion($request, $questions = null)
    {
        if ($request->isMethod('get')) {
            session()->forget('selected_questions');
        }
        //dd($questions);
        $subjects = QuestionCategory::whereNull('parent_category_id')->get();
        return view('teacher.pages.question_builder_select', compact('subjects', 'questions'));
    }
    public function handleLoadChapters($request)
    {
        $subjectIds = $request->subject_ids ?? [];

        // Get categories where parent_category_id is one of the selected subjects
        $categories = QuestionCategory::whereIn('parent_category_id', $subjectIds)
            ->with('children') // Make sure 'children' relation is defined
            ->select('id', 'parent_category_id', 'name')
            ->get();

        // Convert to JSTree format
        $tree = $this->buildJSTree($categories);

        return response()->json($tree);
    }

    protected function buildJSTree($categories)
    {
        return $categories->map(function ($cat) {
            return [
                'id' => 'chapter_' . $cat->id,   // important: prefix for JS
                'text' => $cat->name,
                'children' => $cat->children->count() ? $this->buildJSTree($cat->children) : false,
            ];
        });
    }
    public function handleLoadQuestions($request)
    {
        $chapterIds = array_filter(explode(',', $request->child_chapter_ids));
        $questions = Question::with('options')
            ->whereIn('category_id', $chapterIds)
            ->paginate(25);
        $selectedQuestions = session('selected_questions', []);
        return view('teacher.pages.partials.question_list', compact('questions', 'selectedQuestions'));
    }
    public function handleToggleQuestion($request)
    {
        $checked = filter_var($request->checked, FILTER_VALIDATE_BOOLEAN);
        $selected = session()->get('selected_questions', []);
        $questionIds = $request->question_ids ?? [$request->question_id];
        foreach ($questionIds as $questionId) {
            $questionId = (int)$questionId;
            if ($checked) {
                if (!in_array($questionId, $selected)) {
                    $selected[] = $questionId;
                }
            } else {
                $selected = array_values(array_diff($selected, [$questionId]));
            }
        }
        session(['selected_questions' => $selected]);
        return response()->json([
            'count' => count($selected),
            'selected' => $selected
        ]);
    }

    public function handleCreateExam($request)
    {
        // dd($request->all());
        $selectedQuestions = session('selected_questions', []);
        // dd($selectedQuestions);
        if (empty($selectedQuestions)) {
            return redirect()->back()->with('error', 'No questions selected for the exam.');
        }
        $questions = Question::with('options')->whereIn('id', $selectedQuestions)->paginate(10);
        // dd($questions);
        $exam = new \App\Models\Exam();
        $exam->title = 'Exam ' . now()->format('Y-m-d H:is');
        $exam->created_by = Auth::id();
        $exam->save();
        return view('teacher.pages.exam_preview', compact('questions', 'exam'));
    }
}
