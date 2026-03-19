@extends('teacher.global.master')


@section('teacher_custom_style')
    <style>
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #e5e9f2;
        }
    </style>
@endsection
@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">পরীক্ষার তালিকা </h2>
            <a href="{{ route('questionBuilderExamForm') }}" class="btn btn-primary btn-sm">নতুন পরীক্ষা যোগ করুন</a>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse ($exams as $exam)
                    <div class="col-lg-6">
                        <div class="card card-default">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <h2>{{ $exam->title }}</h2>
                                    <span>প্রতি ভুল উত্তরের জন্য {{ $exam->negativeMark->marks }} মার্ক কাটা যাবে </span>

                                </div>
                                <div>
                                    <!-- Edit Button -->
                                    <a href="{{ route('questionBuilderExamForm', $exam->id) }}"
                                        class="btn btn-sm btn-primary">
                                        Edit
                                    </a>


                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </div>
                            <div class="card-body">
                                @php
                                    $totalQuestions = $exam->number_of_question_want_to_add ?? 0;
                                    $addedQuestions = $exam->questions->count() ?? 0;
                                    $remainingQuestions = $totalQuestions - $addedQuestions;
                                    $progress = $totalQuestions > 0 ? ($addedQuestions / $totalQuestions) * 100 : 0;
                                @endphp

                                <div class="progress progress-sm rounded-0 mb-1">
                                    <div class="progress-bar bg-secondary" role="progressbar"
                                        style="width: {{ $progress }}%" aria-valuenow="{{ $addedQuestions }}"
                                        aria-valuemin="0" aria-valuemax="{{ $totalQuestions }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-dark text-capitalize">প্রশ্নের অগ্রগতি</span>
                                    <span class="text-dark text-capitalize">{{ $addedQuestions }} / {{ $totalQuestions }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('selectExamQuestion', $exam->id) }}" class="btn btn-sm btn-success">
                                        <i class="mdi mdi-plus-circle-outline"></i>
                                        প্রশ্ন যোগ করুন
                                    </a>

                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('questionBuilderQuestionView', $exam->id) }}">
                                        <i class="mdi mdi-eye-outline"></i>
                                        নির্বাচিত প্রশ্ন দেখুন
                                    </a>
                                    <button type="button" 
                                            class="btn btn-secondary copy-link-btn btn-sm" 
                                            data-link="{{ route('studentExam', $exam->code) }}">
                                        <i class="mdi mdi-content-copy"></i> কপি লিঙ্ক
                                    </button>

                                    <a class="btn btn-sm btn-warning" href="{{ route('questionBuilderViewResult', $exam->id) }}">
    <i class="mdi mdi-eye-outline me-1"></i> ফলাফল দেখুন
</a>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">কোন পরীক্ষা পাওয়া যায়নি।</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    <script>
        $(document).ready(function() {
            $('.copy-link-btn').on('click', function() {
                var link = $(this).data('link');

                // Create a temporary input to copy the text
                var $temp = $('<input>');
                $('body').append($temp);
                $temp.val(link).select();
                try {
                    document.execCommand('copy');
                    alert('Link copied to clipboard: ' + link);
                } catch (err) {
                    alert('Failed to copy link');
                }
                $temp.remove();
            });
        });
    </script>
@endsection
