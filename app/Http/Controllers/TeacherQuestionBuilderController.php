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
    public function questionBuilderIndex()
    {
        return $this->questionBuilderService->renderQuestionBuilderIndex();
    }
    public function questionBuilderExamForm($id = null)
    {
        return $this->questionBuilderService->renderQuestionBuilderExamForm($id);
    }
    public function questionBuilderExamSave(Request $request, $id = null)
    {
        return $this->questionBuilderService->handleQuestionBuilderExamSave($request, $id);
    }
    public function selectExamQuestion(Request $request, $id)
    {
        return $this->questionBuilderService->renderSelectExamQuestion($request, $id);
    }

    public function loadChapters(Request $request)
    {
        return $this->questionBuilderService->handleLoadChapters($request);
    }

    public function questionBuilderQuestionSave(Request $request , $id)
    {
        return $this->questionBuilderService->handleQuestionBuilderQuestionSave($request , $id);
    }
}
