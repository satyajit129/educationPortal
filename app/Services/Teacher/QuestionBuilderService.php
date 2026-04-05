<?php

namespace App\Services\Teacher;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\NegativeMark;
use App\Models\PreviousExam;
use App\Models\PreviousExamCategory;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\UserAttempt;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;

class QuestionBuilderService
{
    public function renderQuestionBuilderIndex()
    {
        $exams = Exam::with('negativeMark')->withCount('userAttempts')->latest()->get();
        return view('teacher.pages.question_builder_index', compact('exams'));
    }
    public function renderQuestionBuilderExamForm($id = null)
    {
        $exam = $id ? Exam::findorFail($id) : null;
        $neagativemarks = NegativeMark::all();
        return view('teacher.pages.question_builder_exam_form', compact('exam', 'neagativemarks'));
    }
    public function handleQuestionBuilderExamSave($request, $id = null)
    {
        // dd($request->all());
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'totalQuestion' => 'required|integer|min:1',
                'marks_per_question' => 'required|numeric|min:0',
                'negative_mark_id' => 'required|exists:negative_marks,id',
                'duration' => 'nullable|integer|min:1',
                'status' => 'required|in:draft,published,archived',
                'watermark_text' => 'nullable|string|max:255',
            ]);

            $total_mark = $request->marks_per_question * $request->totalQuestion;

            $exam = $id ? Exam::findOrFail($id) : new Exam;

            $exam->title = $request->title;
            $exam->duration = $request->duration;
            $exam->marks_per_question = $request->marks_per_question;
            $exam->negative_mark_id = $request->negative_mark_id;
            $exam->total_mark = $total_mark;
            $exam->number_of_question_want_to_add = $request->totalQuestion;
            $exam->watermark_text = $request->watermark_text;
            $exam->status = $request->status;
            $exam->created_by = $exam->exists ? $exam->created_by : Auth::id();
            if (!$exam->code) {
                $exam->code = $exam->generateUniqueCode();
            }
            $exam->save();

            $msg = $id ? 'Exam updated successfully.' : 'Exam created successfully.';

            return redirect()->route('questionBuilderIndex')->with('success', $msg);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }
    public function renderSelectExamQuestion($request, $id)
    {
        try {
            $exam = Exam::with('questions.options', 'questions.years')->findOrFail($id);
            // dd($request->all());
            $questions = null;
            $tabData = [];

            // -----------------------------
            // Common Data for All Tabs
            // -----------------------------
            $tabData['subjects'] = QuestionCategory::whereNull('parent_category_id')->get();
            $tabData['years']    = Year::all();
            $tabData['categories'] = PreviousExamCategory::all();

            $categoryIds = $request->input('category_id', []);
            $examIds     = $request->input('exam_id', []);
            $subjectIds  = $request->input('subject_id', []);
            $yearIds     = $request->input('year_id', []);
            $chapterIds  = $request->input('child_chapter_ids', []);

            if (is_string($chapterIds) && !empty($chapterIds)) {
                $chapterIds = explode(',', $chapterIds);
            } elseif (!is_array($chapterIds)) {
                $chapterIds = [];
            }

            $tabData['selectedChapters'] = $chapterIds;

            // Only run query if any filter is selected
            if (!empty($categoryIds) || !empty($examIds) || !empty($subjectIds) || !empty($yearIds) || !empty($chapterIds)) {

                $questionsQuery = Question::with('options', 'years');

                // Step 1: Filter by Exam (direct) or Category → get exams first
                if (!empty($examIds)) {
                    $questionsQuery->whereHas('previousExams', function ($q) use ($examIds) {
                        $q->whereIn('previous_exam_id', $examIds);
                    });
                } elseif (!empty($categoryIds)) {
                    $exams = PreviousExam::whereIn('previous_exam_category_id', $categoryIds)->pluck('id');

                    // ✅ Even if $exams is empty, we still want to apply a "whereHas" with an empty array,
                    $questionsQuery->whereHas('previousExams', function ($q) use ($exams) {
                        if ($exams->isEmpty()) {
                            // Force an impossible condition
                            $q->whereRaw('1 = 0');
                        } else {
                            $q->whereIn('previous_exam_id', $exams);
                        }
                    });
                }

                // Step 2: Subject filter (recursive children)
                if (!empty($subjectIds)) {
                    $categories = QuestionCategory::with('childrenRecursive')->whereIn('id', $subjectIds)->get();

                    $getChildren = function ($categories) use (&$getChildren) {
                        return $categories->flatMap(function ($category) use (&$getChildren) {
                            return collect([$category->id])->merge($getChildren($category->childrenRecursive));
                        });
                    };

                    $categoryIdsRecursive = $getChildren($categories)->unique();
                    $questionsQuery->whereIn('category_id', $categoryIdsRecursive);
                }

                // Step 2.5: Chapter filter (specific chapters selected)
                $chapterIds = $request->input('child_chapter_ids', []);
                if (!empty($chapterIds)) {
                    $chapterIdsArray = explode(',', $chapterIds);
                    $questionsQuery->whereIn('category_id', $chapterIdsArray);
                }

                // Step 3: Year filter (same as year tab)
                if (!empty($yearIds)) {
                    $questionsQuery->whereHas('years', function ($q) use ($yearIds) {
                        $q->whereIn('year_id', $yearIds);
                    });
                }

                // Step 4: Get paginated questions
                $questions = $questionsQuery->paginate(10)->withQueryString();
            }
            if ($exam) {
                $selectedQuestions = $exam->questions()
                    ->pluck('questions.id')
                    ->toArray();
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }

        return view('teacher.pages.question_builder_select', compact('tabData', 'questions', 'exam', 'selectedQuestions'));
    }

    public function handleLoadChapters($request)
    {
        $subjectIds = $request->input('subject_ids', []);

        if (empty($subjectIds)) {
            return response()->json([]);
        }

        $subjects = QuestionCategory::whereIn('id', $subjectIds)
            ->with('childrenRecursive')
            ->get();

        $tree = [];
        foreach ($subjects as $subject) {
            $tree = array_merge($tree, $this->buildTreeFromCategory($subject->childrenRecursive));
        }

        return response()->json($tree);
    }
    private function buildTreeFromCategory($categories)
    {
        $tree = [];
        foreach ($categories as $category) {
            $tree[] = [
                'id' => $category->id,
                'text' => $category->name,
                'children' => $this->buildTreeFromCategory($category->childrenRecursive)
            ];
        }
        return $tree;
    }
    public function handleQuestionBuilderQuestionSave($request, $id)
    {
        // dd($request->all());
        try {
            $exam = Exam::findOrFail($id);
            $selected = $request->input('questions', []);
            $currentPage = $request->input('page', 1);
            $perPage = 10;

            // Get current page question IDs
            $pageQuestionIds = Question::latest()
                ->forPage($currentPage, $perPage)
                ->pluck('id')
                ->toArray();

            // Existing questions for the exam
            $existing = $exam->questions()->pluck('question_id')->toArray();

            // Remove current page questions from existing
            $remaining = array_diff($existing, $pageQuestionIds);

            // Merge remaining + newly selected
            $final = array_merge($remaining, $selected);

            $exam->questions()->sync($final);

            // Preserve filters for redirect
            $filters = [
                'category_id' => $request->input('category_id', []),
                'exam_id' => $request->input('exam_id', []),
                'subject_id' => $request->input('subject_id', []),
                'year_id' => $request->input('year_id', []),
                'child_chapter_ids' => $request->input('child_chapter_ids', ''),
                'page' => $request->input('page', 1),
            ];

            return redirect()->route('selectExamQuestion', ['id' => $id] + $filters)->with('success', 'Questions updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function renderQuestionBuilderQuestionView($id)
    {
        $exam = Exam::with(['questions.options'])
            ->withCount('questions')
            ->findOrFail($id);
        $questions = $exam->questions()->with('options')->paginate(10);
        return view('teacher.pages.question_builder_questions_view', compact('exam', 'questions'));
    }
    public function handleQuestionBuilderQuestionDelete($exam_id, $question_id)
    {
        $exam = Exam::findOrFail($exam_id);
        $exam->questions()->detach($question_id);
        return back()->with('success', 'Question removed!');
    }
    public function renderQuestionBuilderViewResult($id)
    {
        $exam = Exam::findOrFail($id);
        $userAttemts = UserAttempt::where('exam_id', $exam->id)->orderBy('obtained_marks')->get();
        return view('teacher.pages.exam_result', compact('exam', 'userAttemts'));
    }
}
