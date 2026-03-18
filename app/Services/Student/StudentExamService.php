<?php

namespace App\Services\Student;

use App\Models\Exam;

class StudentExamService
{
    public function renderStudentExam($code)
{
    $exam = Exam::where('code', $code)->first();

    if (!$exam) {
        abort(404);
    }

    // Eager load options for each question to avoid N+1 query
    $questions = $exam->questions()->with('options')->get();

    return view('student.pages.exam_questions', compact('exam', 'questions'));
}
}
