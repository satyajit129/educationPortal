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
                    <a class="nav-link tab-link" data-type="year">
                        <i class="mdi mdi-calendar"></i> সাল ভিত্তিক
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link tab-link" data-type="job_solution">
                        <i class="mdi mdi-briefcase-outline"></i> জবসলুশন ভিত্তিক
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link tab-link" data-type="subjectWise">
                        <i class="mdi mdi-book-open-page-variant"></i> বিষয় ভিত্তিক
                    </a>
                </li>

            </ul>

            <div id="tabContentArea">
                {{-- ajax content load here --}}
            </div>
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


    <script>
    $(document).ready(function() {

        function loadTab(type) {
            $.ajax({
                url: "{{ route('builderQuestionType') }}",
                type: "GET",
                data: { type: type },
                beforeSend: function() {
                    // Show preloader while loading
                    $("#tabContentArea").html(`
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                        </div>
                    `);
                },
                success: function(response) {
                    $("#tabContentArea").html(response);

                    // Re-init select2 if needed
                    $('.select2').select2({
                        placeholder: "বিষয় নির্বাচন করুন",
                        width: '100%'
                    });
                },
                error: function() {
                    $("#tabContentArea").html('<div class="text-center text-danger py-4">Content could not be loaded.</div>');
                }
            });
        }

        // First load
        const params = new URLSearchParams(window.location.search);
        let type = params.get('type') ?? 'year';

        loadTab(type);
        $('.tab-link[data-type="' + type + '"]').addClass('active');

        // Click event
        $('.tab-link').click(function() {

            $('.tab-link').removeClass('active');
            $(this).addClass('active');

            let type = $(this).data('type');

            // URL change
            const newUrl = window.location.pathname + '?type=' + type;
            window.history.pushState(null, '', newUrl);

            loadTab(type);
        });

    });
</script>
@endsection
