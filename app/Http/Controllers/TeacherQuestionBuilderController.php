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
        return $this->questionBuilderService->handleLoadChapters($request);
    }
}
