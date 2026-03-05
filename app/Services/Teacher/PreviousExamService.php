<?php

namespace App\Services\Teacher;

use App\Models\PreviousExam;
use App\Models\PreviousExamCategory;
use App\Models\Question;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PreviousExamService
{
    public function renderPreviousExamCategoryList()
    {
        $pre_exam_categories = PreviousExamCategory::with(['previousExam.year', 'previousExam' => function ($query) {
            $query->withCount('questions'); // this adds questions_count to each exam
        }])->latest()->get();

        return view('teacher.pages.previous_exam_category_list', compact('pre_exam_categories'));
    }
    public function renderPreviousExamCategoryForm($id = null)
    {
        $category = $id ? PreviousExamCategory::findOrFail($id) : null;
        return view('teacher.pages.previous_exam_category_form', compact('category'));
    }
    public function handlePreviousExamCategorySave($request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            PreviousExamCategory::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'status' => $request->status ?? 1,
                    'created_by' => Auth::id(),
                ]
            );
            return redirect()->route('previousExamCategoryList')->with('success', 'Previous Exam Category saved successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }
    public function renderPreviousExamListForm($categoryId, $examId = null)
    {
        $category = PreviousExamCategory::findOrFail($categoryId);
        $years = Year::latest()->get();
        $exam = $examId ? PreviousExam::findOrFail($examId) : null;
        return view('teacher.pages.previous_exam_list_form', compact('category', 'years', 'exam'));
    }
    public function handlePreviousExamListSave($request)
    {
        try {
            $request->validate([
                'year_id' => 'required|exists:years,id',
                'name' => 'required|string|max:255',
            ]);
            PreviousExam::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'previous_exam_category_id' => $request->category_id,
                    'year_id' => $request->year_id,
                    'status' => $request->status ?? 1,
                    'created_by' => Auth::id(),
                ]
            );

            return redirect()->route('previousExamCategoryList')->with('success', 'Exam saved successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }
    public function handlePreviousExamListDelete($examId)
    {
        $exam = PreviousExam::findOrFail($examId);
        $exam->delete();
        return redirect()->back()->with('success', 'Exam deleted successfully.');
    }
    public function renderPreviousExamAddQuestionForm($categoryId, $examId)
    {
        $exam = PreviousExam::with('questions:id')->findOrFail($examId);
        $questions = Question::with('options')
            ->latest()
            ->paginate(40);

        $selectedQuestions = DB::table('previous_exam_questions')
            ->where('previous_exam_id', $examId)
            ->pluck('question_id')
            ->toArray();

        return view('teacher.pages.previous_exam_add_question_form', compact('categoryId', 'examId', 'exam', 'questions', 'selectedQuestions'));
    }

    public function handleSavePreviousExamQuestions($request, $examId)
    {
        $previousExam = PreviousExam::findOrFail($examId);

        $selected = $request->questions ?? [];
        $currentPage = $request->page ?? 1;
        $perPage = 40;

        // Correctly get current page questions
        $pageQuestionIds = Question::latest()
            ->forPage($currentPage, $perPage)
            ->pluck('id')
            ->toArray();

        // Existing year questions
        $existing = $previousExam->questions()->pluck('question_id')->toArray();

        // Remove current page questions from existing
        $remaining = array_diff($existing, $pageQuestionIds);

        // Merge remaining + newly selected
        $final = array_merge($remaining, $selected);

        $previousExam->questions()->sync($final);

        // Redirect to the same page
        return redirect()->route('previousExamAddQuestionForm', [
            'categoryId' => $previousExam->previous_exam_category_id,
            'examId' => $previousExam->id,
        ]);
    }
    public function renderViewPreviousExamQuestions($examId)
    {
        $exam = PreviousExam::with('questions.options')->findOrFail($examId);
        $questions = Question::whereIn('id', $exam->questions->pluck('id'))->with('options')->paginate(20);
        return view('teacher.pages.view_previous_exam_questions', compact('exam', 'questions'));
    }
}
