<?php

namespace App\Http\Controllers;

use App\Services\Teacher\QuestionCategoryService;
use Illuminate\Http\Request;

class TeacherQuestionCategoryController extends Controller
{
    protected $questionCategoryService;
    public function __construct(QuestionCategoryService $questionCategoryService)
    {
        $this->questionCategoryService = $questionCategoryService;
    }

    public function questionCategoryList()
    {
        return $this->questionCategoryService->renderQuestionCategoryList();
    }

    public function questionCategoryForm($id = null)
    {
        return $this->questionCategoryService->renderQuestionCategoryForm($id);
    }

    public function questionCategorySave(Request $request, $id = null)
    {
        $id = $id ?? $request->input('id');
        return $this->questionCategoryService->handleQuestionCategorySave($request, $id);
    }

    public function questionCategoryDelete($id)
    {
        return $this->questionCategoryService->handleQuestionCategoryDelete($id);
    }

}
