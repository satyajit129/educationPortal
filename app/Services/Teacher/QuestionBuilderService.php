<?php

namespace App\Services\Teacher;

use App\Models\PreviousExam;
use App\Models\PreviousExamCategory;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;

class QuestionBuilderService
{
    public function renderSelectExamQuestion($request)
    {
        try {
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
            $yearIds     = $request->input('year_id', []); // ✅ Add year filter
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
                // Step 1: Filter by Exam (direct) or Category → get exams first
                if (!empty($examIds)) {
                    $questionsQuery->whereHas('previousExams', function ($q) use ($examIds) {
                        $q->whereIn('previous_exam_id', $examIds);
                    });
                } elseif (!empty($categoryIds)) {
                    $exams = PreviousExam::whereIn('previous_exam_category_id', $categoryIds)->pluck('id');

                    // ✅ Even if $exams is empty, we still want to apply a "whereHas" with an empty array,
                    // which will return 0 results instead of all questions.
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
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }

        return view('teacher.pages.question_builder_select', compact('tabData', 'questions'));
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
}
