@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">{{ $year->title }} সাল ভিত্তিক প্রশ্ন সমূহ </h2>
        </div>
        <div class="card-body">
            @forelse ($questions as $question)
                <div class="mb-3 p-2 border rounded">
                    <div class="d-flex align-items-start">
                        <!-- Question Text -->
                        <h5 class="mb-0 d-flex align-items-center">
                            <span
                                class="badge badge-primary badge-pill">Q{{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}</span>
                            {!! $question->question_text !!}
                        </h5>
                    </div>

                    <hr>

                    <!-- Options -->
                    <div class="row mt-2">
                        @foreach ($question->options as $option)
                            <div class="col-lg-6 col-md-12">
                                <label class="d-flex align-items-center" style="gap: 10px;">
                                    <input type="radio" name="q{{ $question->id }}" value="{{ $option->id }}" disabled
                                        {{ $option->is_correct == 1 ? 'checked' : '' }}>
                                    <span
                                        style="
                                            font-weight: bold; 
                                            color: {{ $option->is_correct == 1 ? 'green' : '#333' }};
                                            font-size: 16px;
                                        ">
                                        {!! $option->option_text !!}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p>কোন প্রশ্ন পাওয়া যায়নি।</p>
            @endforelse

            {{ $questions->links() }}
        </div>
    </div>
@endsection
