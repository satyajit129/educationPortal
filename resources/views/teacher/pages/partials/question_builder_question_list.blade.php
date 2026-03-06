@if ($type == 'year')
    <form action="{{ route('loadQuestions') }}" method="post">
        @csrf
        <input type="text" name="type" id="type" value="{{ $type }}" hidden>
        <div class="form-group">
            <label for="yearSelect">বছর নির্বাচন করুন:</label>
            <select class="form-control select2" id="yearSelect" name="year_id[]" multiple>
                <option value="">-- বছর নির্বাচন করুন --</option>
                @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->title }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">প্রশ্ন লোড করুন</button>
    </form>

@endif


@if ($type == 'job_solution')
 <input type="text" name="type" id="type" value="{{ $type }}" hidden>
    <form action="{{ route('loadQuestions') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="categorySelect">ক্যাটাগরি নির্বাচন করুন:</label>
            <select class="form-control select2" id="categorySelect" name="category_id[]" multiple>
                <option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">প্রশ্ন লোড করুন</button>
    </form>
@endif


@if ($type == 'subjectWise')
    <form action="">
        @csrf

        <div class="row">
            <div class="col-lg-5">
                <div class="form-group">
                    <label class="form-label">বিষয় নির্বাচন করুন</label>
                    <select name="subject_ids[]" multiple class="form-control select2" id="subject_ids">
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="form-group">
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
            </div>

            <input type="hidden" name="child_chapter_ids" id="selectedChapters">
        </div>
        <button type="submit" class="btn btn-primary">প্রশ্ন লোড করুন</button>
    </form>
@endif

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

        if (allIds.length) {
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

        if (allIds.length) {
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
