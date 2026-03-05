<?php

namespace App\Services\Teacher;

use App\Models\PreviousExam;
use App\Models\PreviousExamCategory;
use App\Models\Year;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PreviousExamService
{
    public function renderPreviousExamCategoryList()
    {
        $pre_exam_categories = PreviousExamCategory::with('previousExam.year')->latest()->get();
        return view('teacher.pages.previous_exam_category_list', compact('pre_exam_categories'));
    }
    public function renderPreviousExamCategoryForm($id = null)
    {
        $category = $id ? PreviousExamCategory::findOrFail($id) : null;
        return view('teacher.pages.previous_exam_category_form', compact('category'));
    }
    public function handlePreviousExamCategorySave($request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            PreviousExamCategory::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'status' => $request->status ?? 1,
                    'created_by' => Auth::id(),
                ]
            );
            return redirect()->route('previousExamCategoryList')->with('success', 'Previous Exam Category saved successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }
    public function renderPreviousExamListForm($categoryId, $examId = null)
    {
        $category = PreviousExamCategory::findOrFail($categoryId);
        $years = Year::latest()->get();
        $exam = $examId ? PreviousExam::findOrFail($examId) : null;
        return view('teacher.pages.previous_exam_list_form', compact('category', 'years', 'exam'));
    }
    public function handlePreviousExamListSave($request)
    {
        try {
            $request->validate([
                'year_id' => 'required|exists:years,id',
                'name' => 'required|string|max:255',
            ]);
            PreviousExam::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                    'previous_exam_category_id' => $request->category_id,
                    'year_id' => $request->year_id,
                    'status' => $request->status ?? 1,
                    'created_by' => Auth::id(),
                ]
            );

            return redirect()->route('previousExamCategoryList')->with('success', 'Exam saved successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }
    public function handlePreviousExamListDelete($examId)
    {
        $exam = PreviousExam::findOrFail($examId);
        $exam->delete();
        return redirect()->back()->with('success', 'Exam deleted successfully.');
    }
}
