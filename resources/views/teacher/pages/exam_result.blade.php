@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">{{ $exam->title ?? '' }} - এর ফলাফল এর তালিকা </h2>
            <a href="{{ route('downloadResult', $exam->id) }}" class="btn btn-sm btn-success">
                <i class="mdi mdi-download me-1"></i> PDF ডাউনলোড
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>নাম</td>
                            <td>মোবাইল নং</td>
                            <td>মোট প্রশ্ন</td>
                            <td>প্রশ্নের উত্তর</td>
                            <td>সঠিক উত্তর</td>
                            <td>ভুল উত্তর</td>
                            <td>সঠিক উত্তরের মার্ক</td>
                            <td>নেগেটিভ মার্ক</td>
                            <td>চূড়ান্ত প্রাপ্ত মার্ক</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userAttemts as $key => $userAttemt)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $userAttemt->name }}</td>
                                <td>{{ $userAttemt->mobile }}</td>
                                <td>{{ $userAttemt->total_questions }}</td>
                                <td>{{ $userAttemt->answered_questions }}</td>
                                <td>{{ $userAttemt->correct_answers }}</td>
                                <td>{{ $userAttemt->wrong_answers }}</td>
                                <td>{{ $userAttemt->right_answer_mark }}</td>
                                <td>{{ $userAttemt->wrong_answer_mark }}</td>
                                <td>{{ $userAttemt->obtained_marks }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">কোনো তথ্য পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('teacher_custom_js')
@endsection
