<?php

namespace App\Http\Controllers;

use App\Services\Teacher\QuestionService;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeacherQuestionController extends Controller
{
    protected $questionService;
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }
    // Question
    public function questionList()
    {
        return $this->questionService->renderQuestions();
    }
    public function questionForm($id = null)
    {
        return $this->questionService->renderQuestionForm($id);
    }
    public function questionSave(Request $request, $id = null)
    {
        return $this->questionService->handleQuestionSave($request, $id);
    }
    public function questionDelete($id)
    {
        return $this->questionService->handleQuestionDelete($id);
    }
    public function questionImportExcel()
    {
        return $this->questionService->renderQuestionImportExcel();
    }
    public function questionExcel()
    {
        return $this->questionService->renderQuestionExcel();
    }
    public function questionUploadExcel(Request $request)
    {
        return $this->questionService->handleQuestionUploadExcel($request);
    }
    public function viewSelectedQuestions(Request $request)
    {
        $ids = explode(',', $request->ids ?? []);
        $questions = Question::whereIn('id', $ids)->with(['options', 'correctOption'])->get();

        return view('teacher.pages.selected_questions', compact('questions'));
    }
}
