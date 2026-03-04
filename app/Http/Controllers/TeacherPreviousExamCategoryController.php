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

    public function previousExamListForm($categoryId)
    {
        return $this->previousExamService->renderPreviousExamListForm($categoryId);
    }
}
