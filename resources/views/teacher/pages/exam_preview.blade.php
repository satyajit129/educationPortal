@extends('teacher.global.master')

@section('teacher_custom_style')
@endsection

@section('teacher_content')
    <div class="container mt-4">
        <h3 class="mb-4">পরীক্ষার প্রশ্নপত্রের পূর্বরূপ</h3>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $exam->name ?? 'পরীক্ষার নাম নেই' }}</h5>
                <p class="card-text"><strong>বিষয়:</strong> {{ $exam->subject->name ?? 'বিষয় নেই' }}</p>
                <p class="card-text"><strong>শ্রেণী:</strong> {{ $exam->class->name ?? 'শ্রেণী নেই' }}</p>
                <p class="card-text"><strong>মোট প্রশ্ন:</strong> {{ $exam->questions()->count() ?? 0 }}</p>
            </div>
        </div>

        @include('teacher.pages.partials.question_list', ['questions' => $questions])
    </div>
@endsection

@section('teacher_custom_js')
@endsection
