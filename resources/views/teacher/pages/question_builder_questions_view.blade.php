@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="card-title mb-0">{{ $exam->name }} প্রশ্ন সমূহ </h2>
                <span>মোট প্রশ্ন : {{ $exam->questions_count }} টি</span>
            </div>
            <a class="btn btn-sm btn-primary" href="{{ route('questionBuilderIndex') }}">তালিকা দেখুন </a>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse ($questions as $question)
                    <div class="col-lg-6">
                        <div class="mb-3 p-2 border rounded">

                            <!-- Top Row: Question + Delete -->
                            <div class="d-flex justify-content-between align-items-start">

                                <!-- Question Text -->
                                <h5 class="mb-0 d-flex align-items-center">
                                    <span class="badge badge-primary badge-pill">
                                        Q{{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}
                                    </span>
                                    {!! $question->question_text !!}
                                </h5>

                                <!-- ✅ Delete Button -->
                                <a href="{{ route('questionBuilderQuestionDelete', [$exam->id, $question->id]) }}" class="text-danger"
                                    onclick="return confirm('আপনি কি নিশ্চিতভাবে এই প্রশ্নটি মুছতে চান?')">
                                    <i class="mdi mdi-delete-outline" style="font-size: 20px;"></i>
                                </a>

                            </div>

                            <div class="border-top mt-1"></div>

                            <!-- Options -->
                            <div class="row mt-2">
                                @foreach ($question->options as $option)
                                    <div class="col-lg-6 col-md-12">
                                        <label class="d-flex align-items-center" style="gap: 10px;">
                                            <input type="radio" name="q{{ $question->id }}" value="{{ $option->id }}"
                                                disabled {{ $option->is_correct == 1 ? 'checked' : '' }}>

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
                    </div>
                @empty
                    <p>কোন প্রশ্ন পাওয়া যায়নি।</p>
                @endforelse
            </div>


            {{ $questions->links() }}
        </div>
    </div>
@endsection
