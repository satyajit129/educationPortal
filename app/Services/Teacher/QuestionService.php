<?php


namespace App\Services\Teacher;

use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class QuestionService
{
    // ----------------------
    // Question Methods
    // ----------------------
    public function renderQuestions()
    {
        $questions = Question::with(['category', 'options'])->latest()->paginate(25);
        return view('teacher.pages.question_list', compact('questions'));
    }


    public function renderQuestionForm($id = null)
    {
        $question = $id
            ? Question::with(['options'])->findOrFail($id)
            : null;

        $categories = QuestionCategory::where('status', '1')->get();

        return view('teacher.pages.question_form', compact('question', 'categories'));
    }


    public function handleQuestionSave($request, $id = null)
    {
        $validated = $request->validate([
            'category_id'       => 'required|exists:question_categories,id',
            'question_text'     => 'required|string',
            'description'       => 'nullable|string',
            'options'           => 'required|array|size:4',
            'options.*'         => 'required|string',
            'correct_option'    => 'required|integer|min:0|max:3',
            'previous_exam_ids' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Question create/update
            $question = $id ? Question::findOrFail($id) : new Question();
            $question->category_id   = $validated['category_id'];
            $question->question_text = $validated['question_text'];
            $question->correct_option = $validated['correct_option'];
            $question->save();

            // // Description
            // if ($request->filled('description')) {
            //     $question->description()->updateOrCreate(
            //         ['question_id' => $question->id],
            //         ['description' => $validated['description']]
            //     );
            // }

            // Options (update or create)
            foreach ($validated['options'] as $index => $optionText) {
                $option = $question->options()->skip($index)->first();
                if ($option) {
                    $option->update([
                        'option_text' => $optionText,
                        'is_correct'  => $index == $validated['correct_option'],
                    ]);
                } else {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct'  => $index == $validated['correct_option'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('questionList')->with('success', 'প্রশ্ন সফলভাবে সংরক্ষণ করা হয়েছে।');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Question Save Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'কিছু ভুল হয়েছে: ' . $e->getMessage());
        }
    }

    public function handleQuestionDelete($id)
    {
        Question::findOrFail($id)->delete();
        return redirect()->route('questionList')->with('success', 'Question deleted successfully.');
    }
    public function renderQuestionImportExcel()
    {
        $categories = QuestionCategory::where('status', 'active')->get();
        return view('teacher.pages.question_import_excel', compact('categories'));
    }
    public function renderQuestionExcel()
    {
        $categories = QuestionCategory::latest()->get();
        return view('teacher.pages.question_excel_form', compact('categories'));
    }
    public function handleQuestionUploadExcel($request)
    {
        $request->validate([
            'category_id' => 'required|exists:question_categories,id',
            'excel_file'  => 'required|file|mimes:xlsx,xls'
        ]);

        $categoryId = $request->category_id;
        $file = $request->file('excel_file');

        DB::beginTransaction();

        try {
            $rows = Excel::toArray([], $file)[0];

            // Remove header row
            unset($rows[0]);

            foreach ($rows as $row) {

                // Skip empty rows
                if (
                    empty($row[0]) ||
                    empty($row[1]) ||
                    empty($row[2])
                ) {
                    continue;
                }

                $questionText   = trim(strip_tags($row[0]));
                $optionsRaw     = trim($row[1]);
                $correctAnswer  = trim(strip_tags($row[2]));

                // Split options by |
                $options = array_map('trim', explode('|', $optionsRaw));

                // Find correct option index
                $correctIndex = array_search($correctAnswer, $options);

                if ($correctIndex === false) {
                    // Skip if correct answer not found in options
                    continue;
                }

                // Save Question
                $question = Question::create([
                    'category_id'    => $categoryId,
                    'question_text'  => $questionText,
                    'correct_option' => $correctIndex,
                    'description'    => null,
                ]);

                // Save Options
                foreach ($options as $index => $optionText) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct'  => $index == $correctIndex,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('questionList')->with('success', 'Excel থেকে প্রশ্ন সফলভাবে সংরক্ষণ করা হয়েছে।');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with(
                'error',
                'Error: ' . $e->getMessage()
            );
        }
    }
    public function renderQuestionBuilder()
    {
        $categories = QuestionCategory::get();
        return view('teacher.pages.question_builder', compact('categories'));
    }
}
