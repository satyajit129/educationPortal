<?php

namespace App\Http\Controllers;

use App\Services\Student\StudentExamService;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    protected $studentExamService;

    public function __construct(StudentExamService $studentExamService) {
        $this->studentExamService = $studentExamService;
    }
    public function studentExam($code)
    {
        return $this->studentExamService->renderStudentExam($code);
    }
    public function studentExamSubmit(Request $request)
    {
        return $this->studentExamService->handleStudentExamSubmit($request);
    }
    public function studentExamQuestion($id)
    {
        return $this->studentExamService->renderStudentExamQuestion($id);
    }
}
