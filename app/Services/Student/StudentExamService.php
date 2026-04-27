<?php

namespace App\Services\Student;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\NegativeMark;
use App\Models\Question;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentExamService
{
    public function renderStudentExam($code)
    {
        $exam = Exam::where('code', $code)->first();
        if (!$exam) {
            abort(404);
        }
        $questions = $exam->questions()->with('options')->get();
        return view('student.pages.exam_questions', compact('exam', 'questions'));
    }

    public function handleStudentExamSubmit($request)
    {
        try {
            // Step 1: Validate input
            $request->validate([
                'name' => 'required|string|max:255',
                'mobile' => 'required|string|max:20',
                'exam_id' => 'required|exists:exams,id',
                'answers' => 'required|array|min:1',
            ]);

            $exam = Exam::findOrFail($request->exam_id);
            $negativeMark = NegativeMark::findOrFail($exam->negative_mark_id);
            // Step 2: Get or create user and update name if changed
            $user = User::firstOrNew(['mobile' => $request->mobile]);
            $user->name = $request->name;
            // $user->

            // Only set password if user is new
            if (!$user->exists) {
                $user->password = '123456'; // will hash automatically
                $user->is_student = true;
            }

            $user->save();

            // Step 3: Get exam questions via ExamQuestion
            $questions = ExamQuestion::with('question.options')
                ->where('exam_id', $request->exam_id)
                ->get()
                ->pluck('question')
                ->filter()
                ->keyBy('id');

            if ($questions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No questions found for this exam.'
                ], 400);
            }

            // Step 4: Create or update user attempt
            $userAttempt = UserAttempt::firstOrNew([
                'user_id' => $user->id,
                'exam_id' => $request->exam_id,
            ]);

            $userAttempt->name = $user->name;
            $userAttempt->mobile = $user->mobile;
            $userAttempt->total_questions = $questions->count();
            $userAttempt->submitted_at = now();
            $userAttempt->save();

            // Step 5: Clear previous answers if retaking
            if (!$userAttempt->wasRecentlyCreated) {
                UserAnswer::where('user_attempt_id', $userAttempt->id)->delete();
            }

            // Step 6: Save answers and calculate results
            $correct = 0;
            $wrong = 0;

            $rightAnswerMark = $exam->marks_per_question;
            $wrongAnswerMark = $negativeMark->marks; // must be positive

            foreach ($request->answers as $questionId => $selectedOptionId) {

                $question = $questions[$questionId] ?? null;
                if (!$question) continue;

                $correctOption = $question->options->where('is_correct', 1)->first();
                if (!$correctOption) continue;

                $correctOptionId = $correctOption->id;

                $isCorrect = (int)$selectedOptionId === (int)$correctOptionId;

                $obtainedMark = $isCorrect
                    ? $rightAnswerMark
                    : -$wrongAnswerMark;

                if ($isCorrect) $correct++;
                else $wrong++;

                UserAnswer::create([
                    'user_attempt_id' => $userAttempt->id,
                    'exam_id' => $request->exam_id,
                    'question_id' => $question->id,
                    'selected_option_id' => $selectedOptionId,
                    'correct_option_id' => $correctOptionId,
                    'is_correct' => $isCorrect,
                    'question_mark' => $rightAnswerMark,
                    'obtained_mark' => $obtainedMark,
                ]);
            }

            // Step 7: Final calculations
            $answered = count($request->answers);
            $skipped = $questions->count() - $answered;

            // ✅ total marks
            $totalRightMarks = $correct * $rightAnswerMark;
            $totalWrongMarks = $wrong * $wrongAnswerMark;

            // ✅ final obtained marks (with negative marking)
            $obtainedMarks = $totalRightMarks - $totalWrongMarks;

            // ✅ Update attempt
            $userAttempt->update([
                'answered_questions' => $answered,
                'correct_answers' => $correct,
                'wrong_answers' => $wrong,
                'skipped_questions' => $skipped,

                // ✅ store TOTAL values
                'right_answer_mark' => $totalRightMarks,
                'wrong_answer_mark' => $totalWrongMarks,

                'obtained_marks' => $obtainedMarks,
            ]);

            // ✅ Auto login
            Auth::login($user);

            // Step 8: Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Exam submitted successfully!',
                'route' => route('studentDashboard') // 👈 send URL here
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function renderStudentExamQuestion($id)
    {
        $attempt = UserAttempt::with([
            'exam.questions.options',
            'answers.option'
        ])
            ->where('exam_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('student.pages.exam_question', [
            'exam' => $attempt->exam,
            'questions' => $attempt->exam->questions,
            'previousAnswers' => $attempt->answers->keyBy('question_id')
        ]);
    }
}
