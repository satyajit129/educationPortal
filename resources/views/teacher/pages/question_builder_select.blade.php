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
    </style>
@endsection

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">প্রশ্ন বিল্ডার</h2>
        </div>
        <div class="card-body">
            <form id="questionBuilderForm" method="POST" action="{{ route('loadQuestions') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-5">
                        <label class="form-label">বিষয় নির্বাচন করুন</label>
                        <select name="subject_ids[]" multiple class="form-control select2" id="subject_ids">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-7">
                        <label>অধ্যায় নির্বাচন করুন</label>
                        <div class="tree-select-wrapper">
                            <div class="tree-select-input" id="treeSelectToggle">
                                <span id="treeSelectText">Select Chapters</span>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="tree-select-dropdown" id="treeSelectDropdown">
                                <input type="text" id="treeSearch" class="form-control tree-search"
                                    placeholder="Search...">
                                <div id="chapterTree"></div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="child_chapter_ids" id="selectedChapters">
                </div>

                <button type="submit" class="btn btn-primary mt-3">সাবমিট করুন </button>
            </form>
        </div>
    </div>
    <!-- ================= QUESTIONS ================= -->
    <div class="card card-default mt-4">
        <div class="card-header">
            <h2 class="card-title mb-0">প্রশ্নসমূহ</h2>
            <div class="d-flex justify-content-end mb-2 gap-2">
                <button type="button" class="btn btn-success" id="selectAllQuestions">সব নির্বাচন (এই পৃষ্ঠা )</button>
                <button type="button" class="btn btn-danger" id="unselectAllQuestions">সব মুছুন (এই পৃষ্ঠা )</button>
            </div>
        </div>
        <div class="card-body" id="questionListContainer">
            <div class="text-muted text-center">
                বিষয় ও অধ্যায় নির্বাচন করুন
            </div>
        </div>
        <!-- Floating Create Question Button -->
        <div id="floatingCreateBtn" class="d-flex align-items-center justify-content-between p-2 shadow rounded"
            style="position: fixed; bottom: 40px; right: 20px; background: #007bff; color: #fff; z-index: 1000; cursor: pointer; min-width: 220px;">

            <span id="selectedCount">{{ count(session('selected_questions', [])) }} টি প্রশ্ন নির্বাচিত</span>
                <form action="{{ route('createExam') }}" method="post">
                    @csrf
                    <button id="createQuestionsBtn" class="btn btn-light btn-sm ms-2">
                        প্রশ্ন তৈরি
                    </button>
                </form>
            
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    <!-- JSTree JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>

    <!-- Select2 JS -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            // Select2 init
            $('.select2').select2({
                placeholder: "বিষয় নির্বাচন করুন",
                width: '100%'
            });

            // Toggle dropdown
            $('#treeSelectToggle').on('click', function() {
                $('#treeSelectDropdown').toggle();
            });
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.tree-select-wrapper').length) {
                    $('#treeSelectDropdown').hide();
                }
            });

            // Load JSTree JSON
            function loadTree(subjectIDs) {
                if (!subjectIDs || !subjectIDs.length) {
                    $('#chapterTree').jstree('destroy').empty();
                    return;
                }

                $.ajax({
                    url: "{{ route('loadChapters') }}",
                    type: "GET",
                    data: {
                        subject_ids: subjectIDs
                    },
                    success: function(data) {
                        $('#chapterTree').jstree('destroy'); // destroy old tree
                        $('#chapterTree').jstree({
                            'core': {
                                'themes': {
                                    'icons': false
                                },
                                'data': data
                            },
                            'plugins': ['checkbox', 'search', 'wholerow'],
                            'checkbox': {
                                'three_state': true,
                                'cascade': 'up+down'
                            }
                        });
                        // <-- Open all nodes initially
                        $('#chapterTree').on('ready.jstree', function() {
                            $(this).jstree('open_all');
                        });
                    },
                    error: function(xhr) {
                        console.error('AJAX error', xhr);
                    }
                });
            }

            loadTree($('#subject_ids').val());
            $('#subject_ids').on('change', function() {
                loadTree($(this).val());
            });

            // Search
            var to = false;
            $('#treeSearch').on('keyup', function() {
                if (to) clearTimeout(to);
                to = setTimeout(function() {
                    $('#chapterTree').jstree(true)?.search($('#treeSearch').val());
                }, 250);
            });

            // Submit selected chapters
            $('form').on('submit', function() {

                if (!$('#chapterTree').jstree(true)) {
                    $('#selectedChapters').val('');
                    return true;
                }

                var nodes = $('#chapterTree').jstree(true).get_checked(true);

                var ids = nodes.map(function(node) {
                    return node.id.replace('chapter_', '');
                });

                $('#selectedChapters').val(ids.join(','));
            });
        });
    </script>

    <script>
        /* ================= SUBMIT FORM ================= */
        $('#questionBuilderForm').on('submit', function(e) {
            e.preventDefault();

            let tree = $('#chapterTree').jstree(true);
            if (!tree) {
                alert('অধ্যায় নির্বাচন করুন');
                return;
            }

            let ids = tree.get_checked(true).map(n => n.id.replace('chapter_', ''));
            $('#selectedChapters').val(ids.join(','));

            loadQuestions("{{ route('loadQuestions') }}", $(this).serialize());
        });

        /* ================= PAGINATION ================= */
        $(document).on('click', '#questionListContainer .pagination a', function(e) {
            e.preventDefault();
            loadQuestions($(this).attr('href'), $('#questionBuilderForm').serialize());
        });

        /* ================= LOAD QUESTIONS ================= */
        function loadQuestions(url, data) {
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                beforeSend() {
                    $('#questionListContainer').html(
                        '<div class="text-center text-muted py-4">Loading questions...</div>'
                    );
                },
                success(html) {
                    $('#questionListContainer').html(html);
                },
                error() {
                    alert('প্রশ্ন লোড করা যায়নি');
                }
            });
        }

        /* ================= CHECKBOX CHANGE ================= */
        $(document).on('change', '.select-question-checkbox', function() {
            let checkbox = $(this);

            $.post("{{ route('toggleQuestion') }}", {
                _token: "{{ csrf_token() }}",
                question_id: checkbox.val(),
                checked: checkbox.is(':checked')
            }, function(res) {
                updateFloatingCount(res.count);
            });
        });


        /* ================= SELECT ALL (THIS PAGE) ================= */
/* ================= SELECT ALL ================= */
$('#selectAllQuestions').on('click', function() {
    let allIds = [];
    $('.select-question-checkbox').each(function() {
        if (!this.checked) {
            this.checked = true; // check it visually
            allIds.push($(this).val());
        }
    });

    if(allIds.length){
        $.post("{{ route('toggleQuestion') }}", {
            _token: "{{ csrf_token() }}",
            question_ids: allIds, // send array
            checked: true
        }, function(res) {
            updateFloatingCount(res.count);
        });
    }
});

/* ================= UNSELECT ALL ================= */
$('#unselectAllQuestions').on('click', function() {
    let allIds = [];
    $('.select-question-checkbox').each(function() {
        if (this.checked) {
            this.checked = false; // uncheck visually
            allIds.push($(this).val());
        }
    });

    if(allIds.length){
        $.post("{{ route('toggleQuestion') }}", {
            _token: "{{ csrf_token() }}",
            question_ids: allIds,
            checked: false
        }, function(res) {
            updateFloatingCount(res.count);
        });
    }
});
    </script>
    <script>
        function updateFloatingCount(count) {
            $('#selectedCount').text(count + ' টি প্রশ্ন নির্বাচিত');
        }
    </script>
@endsection
