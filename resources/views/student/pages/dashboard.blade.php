@extends('student.global.master')

@section('student_content')
    <h1>Dashboard</h1>
    <div class="row">
        @foreach ($attempts as $attempt)
            <div class="col-md-6 col-xl-4">
                <div class="card card-default mb-1" style="overflow: hidden;">

                    <!-- Header -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $attempt->exam->title }}</h6>
                        <span class="badge bg-light text-dark">
                            {{ $attempt->created_at->format('d M Y H:i A') }}
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        
                        <p class="mb-2">
                            <strong>প্রাপ্ত নম্বর:</strong>
                            <span class="text-success">{{ $attempt->obtained_marks }}</span>
                        </p>

                        <p class="mb-2">
                            <strong>পূর্ণমান:</strong>
                            {{ $attempt->exam->total_mark }}
                        </p>

                        <!-- Progress Bar -->
                        @php
                            $percentage = ($attempt->obtained_marks / $attempt->exam->total_mark) * 100;
                        @endphp

                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <small class="text-muted">
                            সময়: {{ $attempt->exam->duration }} মিনিট
                        </small>

                        <a href="{{ route('studentExamQuestion', $attempt->exam_id) }}" class="btn btn-sm btn-outline-primary">
                            ফলাফল দেখুন
                        </a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endsection
