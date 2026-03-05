<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Year;
use Illuminate\Support\Facades\DB;

class YearService
{
    public function renderYearList()
    {
        $years = Year::withCount('questions')->get();
        return view('teacher.pages.year_list', compact('years'));
    }
    public function renderYearForm($id = null)
    {
        $year = $id ? Year::findOrFail($id) : null;
        return view('teacher.pages.year_form', compact('year'));
    }
    public function handleYearsave($request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
            ]);
            Year::updateOrCreate(
                ['id' => $request->id],
                ['title' => $request->title]
            );
            return redirect()->route('yearList')->with('success', 'বছর সফলভাবে সংরক্ষণ করা হয়েছে।');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'বছর সংরক্ষণ করতে সমস্যা হয়েছে: ' . $th->getMessage())->withInput();
        }
    }
    public function handleYearDelete($id)
    {
        $year = Year::findOrFail($id);
        $year->delete();
        return redirect()->route('yearList')->with('success', 'বছর সফলভাবে মুছে ফেলা হয়েছে।');
    }
    public function renderYearAddQuestionForm($id)
    {
        $year = Year::with('questions:id')->findOrFail($id);

        $questions = Question::with('options')
            ->latest()
            ->paginate(40);

        $selectedQuestions = DB::table('year_question')
            ->where('year_id', $id)
            ->pluck('question_id')
            ->toArray();

        return view('teacher.pages.year_add_question_form', compact('id', 'year', 'questions', 'selectedQuestions'));
    }

    public function handleSaveYearQuestions($request, $id)
    {
        $year = Year::findOrFail($id);

        $selected = $request->questions ?? [];
        $currentPage = $request->page ?? 1;
        $perPage = 40;

        // Correctly get current page questions
        $pageQuestionIds = Question::latest()
            ->forPage($currentPage, $perPage)
            ->pluck('id')
            ->toArray();

        // Existing year questions
        $existing = $year->questions()->pluck('question_id')->toArray();

        // Remove current page questions from existing
        $remaining = array_diff($existing, $pageQuestionIds);

        // Merge remaining + newly selected
        $final = array_merge($remaining, $selected);

        $year->questions()->sync($final);

        // Redirect to the same page
        return redirect()->route('yearAddQuestionForm', [
            'id' => $id,
            'page' => $currentPage
        ]);
    }

    public function renderViewYearQuestions($id)
    {
        $year = Year::with('questions.options')->findOrFail($id);
        $questions = Question::whereIn('id', $year->questions->pluck('id'))->with('options')->paginate(20);
        return view('teacher.pages.view_year_questions', compact('year', 'questions'));
    }
}
