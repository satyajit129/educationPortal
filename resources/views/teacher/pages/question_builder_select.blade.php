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
        </div>
        <div class="card-body">
            <div id="tabContentArea">
                @include('teacher.pages.partials.question_builder_question_list', $tabData)
            </div>
        </div>
    </div>
    <div class="row">

        {{-- LEFT SIDE : QUESTION LIST --}}
        <div class="col-md-7">

            <div class="card card-default">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">প্রশ্ন সমূহ</h4>

                    <div>
                        <button class="btn btn-sm btn-primary" id="selectAllBtn">
                            Select All
                        </button>

                        <button class="btn btn-sm btn-secondary" id="deselectAllBtn">
                            Deselect All
                        </button>
                    </div>

                </div>

                <div class="card-body">

                    @if ($questions && $questions->count() > 0)
                        @foreach ($questions as $question)
                            <div class="mb-3 p-2 border rounded">

                                <div class="d-flex align-items-start">

                                    <div class="form-check me-2">
                                        <input type="checkbox" class="form-check-input select-question-checkbox"
                                            value="{{ $question->id }}" id="q{{ $question->id }}">
                                    </div>

                                    <h5 class="mb-0">

                                        <span class="badge badge-primary">

                                            Q{{ ($questions->currentPage() - 1) * $questions->perPage() + $loop->iteration }}

                                        </span>

                                        {!! $question->question_text !!}

                                    </h5>

                                </div>

                                <hr>

                                <div class="row">

                                    @foreach ($question->options as $option)
                                        <div class="col-md-6">

                                            <label class="d-flex">

                                                <input type="radio" name="q{{ $question->id }}">

                                                <span class="ms-2">
                                                    {!! $option->option_text !!}
                                                </span>

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

        </div>



        {{-- RIGHT SIDE : SELECTED QUESTIONS PANEL --}}
        <div class="col-md-5">

            <div class="card card-default">

                <div class="card-header">
                    <strong>Selected Questions</strong>
                </div>

                <div class="card-body" id="selectedQuestionsList">

                    <p class="text-muted">
                        No questions selected
                    </p>

                </div>

                <div class="card-footer">

                    Total:
                    <span id="selectedCountSide">0</span>

                </div>

            </div>

        </div>

    </div>
    {{-- FLOATING SUBMIT BUTTON --}}
    <div id="floatingSubmitWrapper">

        <button class="btn btn-success" id="submitSelectedQuestions">

            Submit
            (<span id="selectedCount">0</span>)

        </button>

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

            // Search functionality
            $('#treeSearch').on('keyup', function() {
                let searchString = $(this).val();
                $('#chapterTree').jstree('search', searchString);
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            let selectedQuestions =
                JSON.parse(localStorage.getItem('selectedQuestions')) || [];


            function saveToLocal() {

                localStorage.setItem(
                    'selectedQuestions',
                    JSON.stringify(selectedQuestions)
                );

            }



            function updateSelectedCount() {

                $('#selectedCount').text(selectedQuestions.length);
                $('#selectedCountSide').text(selectedQuestions.length);

            }



            function renderSelectedQuestions() {

                let container = $('#selectedQuestionsList');

                container.empty();

                if (selectedQuestions.length === 0) {

                    container.html(
                        '<p class="text-muted">No questions selected</p>'
                    );

                    return;
                }


                selectedQuestions.forEach(function(id, index) {

                    container.append(

                        `<div class="selected-question-item d-flex justify-content-between">

                    <span>
                        ${index+1}. Question : ${id}
                    </span>

                    <button
                        class="btn btn-sm btn-danger removeSelected"
                        data-id="${id}"
                    >
                        Remove
                    </button>

                </div>`

                    );

                });

            }



            // Restore checkbox state
            $('.select-question-checkbox').each(function() {

                let id = $(this).val().toString();

                if (selectedQuestions.includes(id)) {

                    $(this).prop('checked', true);

                }

            });



            updateSelectedCount();
            renderSelectedQuestions();



            // Checkbox change
            $(document).on('change', '.select-question-checkbox', function() {

                let id = $(this).val().toString();

                if ($(this).is(':checked')) {

                    if (!selectedQuestions.includes(id)) {

                        selectedQuestions.push(id);

                    }

                } else {

                    selectedQuestions =
                        selectedQuestions.filter(q => q !== id);

                }


                saveToLocal();

                updateSelectedCount();

                renderSelectedQuestions();

            });



            // Select all current page
            $('#selectAllBtn').click(function() {

                $('.select-question-checkbox').each(function() {

                    let id = $(this).val().toString();

                    $(this).prop('checked', true);

                    if (!selectedQuestions.includes(id)) {

                        selectedQuestions.push(id);

                    }

                });


                saveToLocal();

                updateSelectedCount();

                renderSelectedQuestions();

            });



            // Deselect all current page
            $('#deselectAllBtn').click(function() {

                $('.select-question-checkbox').each(function() {

                    let id = $(this).val().toString();

                    $(this).prop('checked', false);

                    selectedQuestions =
                        selectedQuestions.filter(q => q !== id);

                });


                saveToLocal();

                updateSelectedCount();

                renderSelectedQuestions();

            });



            // Remove from sidebar
            $(document).on('click', '.removeSelected', function() {

                let id = $(this).data('id').toString();

                selectedQuestions =
                    selectedQuestions.filter(q => q !== id);

                saveToLocal();

                $('#q' + id).prop('checked', false);

                updateSelectedCount();

                renderSelectedQuestions();

            });



            // Submit
            $('#submitSelectedQuestions').click(function() {

                if (selectedQuestions.length === 0) {

                    alert('No question selected');

                    return;

                }

                alert(
                    "Selected Question IDs:\n\n" +
                    selectedQuestions.join(',')
                );

                console.log(selectedQuestions);

            });

        });
    </script>
@endsection
