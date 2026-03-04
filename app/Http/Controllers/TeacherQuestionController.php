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
    // Fetch questions by category (AJAX request)
    public function getQuestionsByCategory(Request $request)
    {
        $category = QuestionCategory::findOrFail($request->category_id);
        $categoryIds = $this->getAllCategoryIds($category);

        $questions = Question::whereIn('category_id', $categoryIds)
            ->with('options')
            ->paginate(10);

        // Receive previously selected question IDs
        $selectedIds = $request->selected_ids ?? [];

        // Return partial view
        return view('teacher.pages.partials.questions_list', compact('questions', 'selectedIds'))->render();
    }
    public function viewSelectedQuestions(Request $request)
    {
        $ids = explode(',', $request->ids ?? []);
        $questions = Question::whereIn('id', $ids)->with(['options', 'correctOption'])->get();

        return view('teacher.pages.selected_questions', compact('questions'));
    }
}
