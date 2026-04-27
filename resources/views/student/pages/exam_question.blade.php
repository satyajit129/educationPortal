@extends('student.global.master')


@section('student_custom_style')
@endsection


@section('student_content')
    <div class="card card-default">
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
                                        Q{{ $loop->iteration }}
                                    </span>
                                    {!! $question->question_text !!}
                                </h5>

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
        </div>
    </div>
@endsection

@section('student_custom_js')
@endsection
