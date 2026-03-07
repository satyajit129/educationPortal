@extends('teacher.global.master')

@section('teacher_custom_style')
    <!-- JSTree CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />

    <!-- Select2 CSS -->
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" /> --}}

    <style>
        .tree-select-wrapper {
            position: relative;
            width: 100%;
        }

        .tree-select-input {
            border: 1px solid #ced4da;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            background: #fff;
        }

        .tree-select-dropdown {
            position: absolute;
            width: 100%;
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px;
            max-height: 320px;
            overflow-y: auto;
            display: none;
            z-index: 999;
        }

        .tree-search {
            margin-bottom: 10px;
        }

        /* Make radio and label content inline */
        ul.list-unstyled li label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        ul.list-unstyled li label p {
            margin: 0;
        }

        .nav-item:hover {
            cursor: pointer;
        }
    </style>
@endsection

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">প্রশ্ন বিল্ডার</h2>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills mb-3">

                <li class="nav-item">
                    <a href="?type=year" class="nav-link {{ $type == 'year' ? 'active' : '' }}">
                        <i class="mdi mdi-calendar"></i> সাল ভিত্তিক
                    </a>
                </li>

                <li class="nav-item">
                    <a href="?type=job_solution" class="nav-link {{ $type == 'job_solution' ? 'active' : '' }}">
                        <i class="mdi mdi-briefcase-outline"></i> জবসলুশন ভিত্তিক
                    </a>
                </li>

                <li class="nav-item">
                    <a href="?type=subjectWise" class="nav-link {{ $type == 'subjectWise' ? 'active' : '' }}">
                        <i class="mdi mdi-book-open-page-variant"></i> বিষয় ভিত্তিক
                    </a>
                </li>

            </ul>

            <div id="tabContentArea">
                @include(
                    'teacher.pages.partials.question_builder_question_list',
                    array_merge(['type' => $type], $tabData))
            </div>
        </div>
    </div>
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title mb-0">প্রশ্ন সমূহ</h2>
        </div>
        <div class="card-body">
            @if ($questions)
                @foreach ($questions as $question)
                    <div class="mb-3 p-2 border rounded">
                        <div class="d-flex align-items-start">

                            <!-- ✅ Question Checkbox -->
                            <div class="form-check me-2">
                                <input class="form-check-input select-question-checkbox" type="checkbox"
                                    id="q{{ $question->id }}" value="{{ $question->id }}">
                            </div>

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
                                    <label class="d-flex align-items-center " style="gap: 5px;">
                                        <input type="radio" name="q{{ $question->id }}" value="{{ $option->id }}">
                                        {!! $option->option_text !!}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="mt-3">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="alert alert-warning">
                    কোনো প্রশ্ন পাওয়া যায়নি
                </div>
            @endif
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    <script>
        $(document).ready(function() {
            // Select2 init
            $('.select2').select2({
                placeholder: "নির্বাচন করুন",
                width: '100%'
            });
        });
    </script>
@endsection
