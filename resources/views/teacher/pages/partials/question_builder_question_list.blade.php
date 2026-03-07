@php
    $selectedCategoryIds = request()->category_id ?? [];
    $selectedExamIds = request()->exam_id ?? [];
    $selectedSubjectIds = request()->subject_id ?? [];
@endphp

<form action="{{ route('selectExamQuestion') }}" method="get">
    <!-- Category -->
    <div class="form-group">
        <label>পূর্ববর্তী পরীক্ষা ক্যাটাগরি নির্বাচন করুন:</label>
        <select class="form-control select2" id="categorySelect" name="category_id[]" multiple>
            <option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ in_array($cat->id, $selectedCategoryIds) ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- AJAX Loaded Exams -->
    <div class="form-group mt-3">
        <label>পরীক্ষা নির্বাচন করুন:</label>
        <select class="form-control select2" id="examSelect" name="exam_id[]" multiple>
            <option value="">-- আগে ক্যাটাগরি নির্বাচন করুন --</option>
        </select>
    </div>

    <!-- Subjects -->
    <div class="form-group">
        <label for="subjectSelect">বিষয় নির্বাচন করুন:</label>
        <select class="form-control select2" id="subjectSelect" name="subject_id[]" multiple>
            <option value="">-- বিষয় নির্বাচন করুন --</option>
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}"
                    {{ in_array($subject->id, $selectedSubjectIds) ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>অধ্যায় নির্বাচন করুন</label>
        <div class="tree-select-wrapper">
            <div class="tree-select-input" id="treeSelectToggle">
                <span id="treeSelectText">Select Chapters</span>
                <span class="arrow">▼</span>
            </div>
            <div class="tree-select-dropdown" id="treeSelectDropdown">
                {{-- <input type="text" id="treeSearch" class="form-control tree-search" placeholder="Search..."> --}}
                <div id="chapterTree"></div>
            </div>
        </div>
    </div>

    <input type="hidden" name="child_chapter_ids" id="selectedChapters" value="{{ isset($selectedChapters) && is_array($selectedChapters) ? implode(',', $selectedChapters) : '' }}">

    <div class="form-group">
        <label for="yearSelect">বছর নির্বাচন করুন: <span class="text-danger">**</span> </label>
        <select class="form-control select2" id="yearSelect" name="year_id[]" multiple>
            <option value="">-- বছর নির্বাচন করুন --</option>
            @foreach ($years as $year)
                <option value="{{ $year->id }}"
                    {{ in_array($year->id, request()->year_id ?? []) ? 'selected' : '' }}>
                    {{ $year->title }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">প্রশ্ন লোড করুন</button>
        <a href="{{ route('selectExamQuestion') }}" class="btn btn-secondary">রিসেট করুন</a>
    </div>
    
</form>
