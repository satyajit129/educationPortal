<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionCategory;
use App\Services\Teacher\QuestionBuilderService;
use Illuminate\Http\Request;

class TeacherQuestionBuilderController extends Controller
{
    protected $questionBuilderService;
    public function __construct(QuestionBuilderService $questionBuilderService)
    {
        $this->questionBuilderService = $questionBuilderService;
    }
    public function selectExamQuestion(Request $request)
    {
        return $this->questionBuilderService->renderSelectExamQuestion($request);
    }

    public function loadChapters(Request $request)
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

    private function buildTree($categories, $parentId = null)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category->parent_category_id == $parentId) {
                $node = [
                    'id' => $category->id,
                    'text' => $category->name,
                    'children' => $this->buildTree($categories, $category->id)
                ];
                $tree[] = $node;
            }
        }

        return $tree;
    }
}
