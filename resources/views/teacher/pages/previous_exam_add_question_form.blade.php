@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">{{ $exam->name ?? '' }} - এর প্রশ্ন যুক্ত করুন </h2>
        </div>
        <div class="card-body">
            <form id="questionForm" action="{{ route('savePreviousExamQuestions', ['examId' => $examId]) }}" method="POST">
                @csrf

                <input type="hidden" name="page" id="pageInput" value="{{ request('page', 1) }}">
                <input type="hidden" name="examId" value="{{ $examId }}">

                @foreach ($questions as $question)
                    @include('teacher.pages.partials.year_question_list', [
                        'question' => $question,
                        'selectedQuestions' => $selectedQuestions,
                    ])
                @endforeach

                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div>
                        {{ $questions->appends(['examId' => $examId])->links() }}
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Just navigate to the pagination link without form submission
            document.body.addEventListener('click', function(e) {
                if (e.target.closest('.pagination a')) {
                    e.preventDefault();
                    let link = e.target.closest('a');
                    window.location.href = link.href;
                }
            });
        });
    </script>
@endsection
