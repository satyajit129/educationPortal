<?php

namespace App\Http\Controllers;

use App\Services\Teacher\PreviousExamService;
use Illuminate\Http\Request;

class TeacherPreviousExamCategoryController extends Controller
{
    protected $previousExamService;

    public function __construct(PreviousExamService $previousExamService)
    {
        $this->previousExamService = $previousExamService;
    }
    public function previousExamCategoryList()
    {
        return $this->previousExamService->renderPreviousExamCategoryList();
    }
    public function previousExamCategoryForm($id = null)
    {
        return $this->previousExamService->renderPreviousExamCategoryForm($id);
    }
    public function previousExamCategorySave(Request $request){
        return $this->previousExamService->handlePreviousExamCategorySave($request);
    }
    public function previousExamListForm($categoryId, $examId = null)
    {
        return $this->previousExamService->renderPreviousExamListForm($categoryId, $examId);
    }
    public function previousExamListSave(Request $request)
    {
        return $this->previousExamService->handlePreviousExamListSave($request);
    }
    public function previousExamListDelete($examId)
    {
        return $this->previousExamService->handlePreviousExamListDelete($examId);
    }
    public function previousExamAddQuestionForm($categoryId, $examId)
    {
        return $this->previousExamService->renderPreviousExamAddQuestionForm($categoryId, $examId);
    }
    public function savePreviousExamQuestions(Request $request, $examId)
    {
        return $this->previousExamService->handleSavePreviousExamQuestions($request, $examId);
    }
    public function viewPreviousExamQuestions($examId)
    {
        return $this->previousExamService->renderViewPreviousExamQuestions($examId);
    }
}
