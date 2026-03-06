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
    public function selectExamQuestion(Request $request, $questions = null)
    {
        return $this->questionBuilderService->renderSelectExamQuestion($request, $questions);
    }

    public function loadChapters(Request $request)
    {
        return $this->questionBuilderService->handleLoadChapters($request);
    }

    public function loadQuestions(Request $request)
    {
        return $this->questionBuilderService->handleLoadQuestions($request);
    }
    public function toggleQuestion(Request $request)
    {
        return $this->questionBuilderService->handleToggleQuestion($request);
    }
    public function createExam(Request $request)
    {
        return $this->questionBuilderService->handleCreateExam($request);
    }

    public function builderQuestionType(Request $request)
    {
        return $this->questionBuilderService->handleBuilderQuestionType($request);
    }
}
