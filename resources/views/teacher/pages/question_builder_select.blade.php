@extends('teacher.global.master')

@section('teacher_custom_style')
    <!-- JSTree CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />

    <!-- Select2 CSS -->
    {{--
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" /> --}}

    <style>
        .tree-select-wrapper {
            position: relative;
            width: 100%;
        }

        .tree-select-input {
            border: 1px solid #ced4da;
            padding: 8px;
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

    <style>
        #floatingSubmitWrapper {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
        }

        #submitSelectedQuestions {
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 16px;
        }

        .selected-question-item {
            border-bottom: 1px solid #eee;
            padding: 6px 0;
        }
    </style>
@endsection

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">প্রশ্ন বিল্ডার</h2>
            <a class="btn btn-sm btn-primary" href="{{ route('questionBuilderIndex') }}">তালিকা দেখুন </a>
        </div>
        <div class="card-body">
            <div id="tabContentArea">
                @include(
                    'teacher.pages.partials.question_builder_question_list',
                    compact('tabData', 'exam'))
            </div>
        </div>
    </div>
    <div class="row">

        {{-- LEFT SIDE : QUESTION LIST --}}
        <div class="col-lg-12">

            <div class="card card-default">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">প্রশ্ন সমূহ</h4>

                    <div>
                        <button type="button" class="btn btn-sm btn-primary" id="selectAllBtn">
                            সব নির্বাচন করুন
                        </button>

                        <button type="button" class="btn btn-sm btn-secondary" id="deselectAllBtn">
                            সব বাতিল করুন
                        </button>
                    </div>

                </div>

                <div class="card-body">
                    <form action="{{ route('questionBuilderQuestionSave', $exam->id) }}" method="POST">
                        @csrf
                        {{-- Hidden inputs for filters --}}
                        @foreach (request()->category_id ?? [] as $id)
                            <input type="hidden" name="category_id[]" value="{{ $id }}">
                        @endforeach
                        @foreach (request()->exam_id ?? [] as $id)
                            <input type="hidden" name="exam_id[]" value="{{ $id }}">
                        @endforeach
                        @foreach (request()->subject_id ?? [] as $id)
                            <input type="hidden" name="subject_id[]" value="{{ $id }}">
                        @endforeach
                        @foreach (request()->year_id ?? [] as $id)
                            <input type="hidden" name="year_id[]" value="{{ $id }}">
                        @endforeach
                        <input type="hidden" name="child_chapter_ids" value="{{ request()->child_chapter_ids ?? '' }}">
                        <input type="hidden" name="page" value="{{ $questions ? $questions->currentPage() : 1 }}">
                        @if ($questions && $questions->count() > 0)
                            @foreach ($questions as $question)
                                <div class="mb-3 p-2 border rounded">
                                    <div class="d-flex align-items-start">

                                        <!-- ✅ Question Checkbox -->
                                        <div class=" mr-2">
                                            <input type="checkbox" name="questions[]" value="{{ $question->id }}"
                                                {{ in_array($question->id, $selectedQuestions) ? 'checked' : '' }}>
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
                                                <label class="d-flex align-items-center" style="gap: 10px;">
                                                    <input type="radio" name="q{{ $question->id }}"
                                                        value="{{ $option->id }}" disabled
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
                            @endforeach
                            <button class="btn btn-primary mb-2">Submit</button>
                            <div>
                                <div>
                                    {{ $questions->links() }}
                                </div>

                            </div>
                        @else
                            <div class="alert alert-warning">
                                কোনো প্রশ্ন পাওয়া যায়নি
                            </div>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    @php
        $selectedCategoryIds = request()->category_id ?? [];
        $selectedExamIds = request()->exam_id ?? [];
        $selectedSubjectIds = request()->subject_id ?? [];
    @endphp

    <!-- JSTree JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "নির্বাচন করুন",
                width: '100%'
            });

            // Selected exam IDs from request
            let selectedExamIds = @json($selectedExamIds);

            // Trigger AJAX if categories already selected
            if ($('#categorySelect').val() && $('#categorySelect').val().length > 0) {
                loadExams($('#categorySelect').val());
            }

            $('#categorySelect').on('change', function() {
                let categoryIds = $(this).val();
                loadExams(categoryIds);
            });

            function loadExams(categoryIds) {
                if (!categoryIds || categoryIds.length === 0) {
                    $('#examSelect').empty().append(`<option value="">-- আগে ক্যাটাগরি নির্বাচন করুন --</option>`)
                        .trigger('change');
                    return;
                }

                $.ajax({
                    url: "{{ route('loadPreviousExams') }}",
                    type: "GET",
                    data: {
                        category_ids: categoryIds
                    },
                    success: function(response) {
                        let examSelect = $('#examSelect');
                        examSelect.empty().append(
                            `<option value="">-- পরীক্ষা নির্বাচন করুন --</option>`);

                        response.forEach(function(exam) {
                            let selected = selectedExamIds.includes(exam.id) ? 'selected' : '';
                            examSelect.append(
                                `<option value="${exam.id}" ${selected}>${exam.name}</option>`
                            );
                        });

                        examSelect.val(selectedExamIds).trigger('change');
                    }
                });
            }

            // Load chapters based on subject selection
            $('#subjectSelect').on('change', function() {
                let subjectIds = $(this).val();
                loadChapters(subjectIds);
            });

            // Load chapters if subjects are already selected
            if ($('#subjectSelect').val() && $('#subjectSelect').val().length > 0) {
                loadChapters($('#subjectSelect').val());
            }

            function loadChapters(subjectIds) {
                if (!subjectIds || subjectIds.length === 0) {
                    $('#chapterTree').jstree('destroy').empty();
                    $('#treeSelectText').text('Select Chapters');
                    return;
                }

                $.ajax({
                    url: "{{ route('loadChapters') }}",
                    type: "GET",
                    data: {
                        subject_ids: subjectIds
                    },
                    success: function(data) {
                        console.log('Loaded chapters:', data);
                        $('#chapterTree').jstree('destroy').empty();
                        $('#chapterTree').jstree({
                            'core': {
                                'data': data,
                                'themes': {
                                    'icons': false
                                }
                            },
                            'plugins': ['checkbox', 'search'],
                            'checkbox': {
                                'keep_selected_style': false
                            }
                        });

                        $('#chapterTree').on('changed.jstree', function(e, data) {
                            let selectedIds = data.selected;
                            $('#selectedChapters').val(selectedIds.join(','));
                            updateTreeSelectText(data.selected);
                        });

                        $('#chapterTree').on('loaded.jstree', function() {
                            // Pre-select chapters if any
                            let preSelected = $('#selectedChapters').val();
                            if (preSelected) {
                                let ids = preSelected.split(',').filter(id => id);
                                $('#chapterTree').jstree('select_node', ids);
                                updateTreeSelectText(ids);
                            } else {
                                updateTreeSelectText([]);
                            }
                        });

                        // Do not show dropdown here, only on toggle click
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading chapters:', error);
                        alert('Error loading chapters: ' + error);
                    }
                });
            }

            function updateTreeSelectText(selected) {
                if (selected.length === 0) {
                    $('#treeSelectText').text('Select Chapters');
                } else {
                    $('#treeSelectText').text(selected.length + ' Chapters Selected');
                }
            }

            // Toggle dropdown
            $('#treeSelectToggle').on('click', function() {
                $('#treeSelectDropdown').toggle();
                if ($('#treeSelectDropdown').is(':visible')) {
                    $('#chapterTree').jstree('open_all');
                }
            });

            // Hide dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.tree-select-wrapper').length) {
                    $('#treeSelectDropdown').hide();
                }
            });
            // ✅ Select All
            $('#selectAllBtn').on('click', function () {
                $('input[name="questions[]"]').prop('checked', true);
            });

            // ✅ Deselect All
            $('#deselectAllBtn').on('click', function () {
                $('input[name="questions[]"]').prop('checked', false);
            });
        });
    </script>
@endsection
