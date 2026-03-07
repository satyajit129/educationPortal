<?php

namespace App\Services\Teacher;

use App\Models\PreviousExam;
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
        try {

            $questions = null;
            $type = $request->input('type', 'year');
            $tabData = [];

            // -----------------------------
            // Common Data for All Tabs
            // -----------------------------
            $tabData['subjects'] = QuestionCategory::whereNull('parent_category_id')->get();
            $tabData['years']    = Year::all();

            if ($type == 'year') {

                $tabData['years'] = Year::all();
                $tabData['subjects'] = QuestionCategory::whereNull('parent_category_id')->get();

                $yearIds    = $request->input('year_id', []);
                $subjectIds = $request->input('subject_id', []);

                if (!empty($yearIds)) {

                    $questionsQuery = Question::with('years');

                    // Year filter
                    $questionsQuery->whereHas('years', function ($q) use ($yearIds) {
                        $q->whereIn('year_id', $yearIds);
                    });

                    // Subject filter (recursive)
                    if (!empty($subjectIds)) {
                        $categories = QuestionCategory::with('childrenRecursive')->whereIn('id', $subjectIds)->get();

                        $getChildren = function ($categories) use (&$getChildren) {
                            return $categories->flatMap(function ($category) use (&$getChildren) {
                                return collect([$category->id])->merge($getChildren($category->childrenRecursive));
                            });
                        };

                        $categoryIds = $getChildren($categories)->unique();
                        $questionsQuery->whereIn('category_id', $categoryIds);
                    }

                    $questions = $questionsQuery->paginate(2)->withQueryString();
                }
            } elseif ($type == 'job_solution') {

                $tabData['categories'] = PreviousExamCategory::all();
                $tabData['subjects'] = QuestionCategory::whereNull('parent_category_id')->get();

                $categoryIds = $request->input('category_id', []);
                $examIds     = $request->input('exam_id', []);
                $subjectIds  = $request->input('subject_id', []);
                $yearIds     = $request->input('year_id', []); // ✅ Add year filter

                // Only run query if any filter is selected
                if (!empty($categoryIds) || !empty($examIds) || !empty($subjectIds) || !empty($yearIds)) {

                    $questionsQuery = Question::with('options', 'years');

                    // Step 1: Filter by Exam (direct) or Category → get exams first
                    if (!empty($examIds)) {
                        $questionsQuery->whereHas('previousExams', function ($q) use ($examIds) {
                            $q->whereIn('previous_exam_id', $examIds);
                        });
                    } elseif (!empty($categoryIds)) {
                        $exams = PreviousExam::whereIn('previous_exam_category_id', $categoryIds)->pluck('id');
                        if ($exams->count() > 0) {
                            $questionsQuery->whereHas('previousExams', function ($q) use ($exams) {
                                $q->whereIn('previous_exam_id', $exams);
                            });
                        }
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
            } elseif ($type == 'subjectWise') {
                $tabData['subjects'] = QuestionCategory::whereNull('parent_category_id')->get();
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }

        return view('teacher.pages.question_builder_select', compact('type', 'tabData', 'questions'));
    }
}
