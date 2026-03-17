@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">পরীক্ষার ফর্ম </h2>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('questionBuilderIndex') }}">তালিকা দেখুন </a>
        </div>
        <div class="card-body">
            <form action="{{ route('questionBuilderExamSave', $exam->id ?? null) }}" method="post">
                @csrf

                <div class="row">

                    <!-- Title -->
                    <div class="col-lg-6 form-group">
                        <label>পরীক্ষার টাইটেল</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $exam->title ?? '') }}" required>
                    </div>

                    <!-- Total Question -->
                    <div class="col-lg-6 form-group">
                        <label>মোট কতটি প্রশ্ন যুক্ত করতে চান ?</label>
                        <input type="number" name="totalQuestion" id="totalQuestion" class="form-control"
                            value="{{ old('totalQuestion', $exam->number_of_question_want_to_add ?? '') }}" required>
                    </div>

                    <!-- Marks Per Question -->
                    <div class="col-lg-6 form-group">
                        <label>প্রতি প্রশ্নের মান</label>
                        <input type="number" name="marks_per_question" id="marks_per_question" value="{{ $exam->marks_per_question ?? 1 }}" class="form-control"
                            value="{{ old('marks_per_question', $exam->marks_per_question ?? '') }}" required>
                    </div>

                    <!-- Total Mark (Disabled) -->
                    <div class="col-lg-6 form-group">
                        <label>মোট নাম্বার</label>
                        <input type="number" step="0.01" id="total_mark" class="form-control"
                            value="{{ old('total_mark', $exam->total_mark ?? '') }}" disabled>
                    </div>

                    <!-- Negative Mark -->
                    <div class="col-lg-6 form-group">
                        <label>নেগেটিভ মার্ক</label>
                        <select name="negative_mark_id" class="form-control select2">
                            <option value="">নির্বাচন করুন</option>
                            @foreach ($neagativemarks as $mark)
                                <option value="{{ $mark->id }}"
                                    {{ old('negative_mark_id', $exam->negative_mark_id ?? '') == $mark->id ? 'selected' : '' }}>
                                    {{ $mark->marks }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Duration -->
                    <div class="col-lg-6 form-group">
                        <label>সময়কাল (মিনিট)</label>
                        <input type="number" name="duration" class="form-control"
                            value="{{ old('duration', $exam->duration ?? '') }}">
                    </div>

                    <!-- Watermark -->
                    <div class="col-lg-6 form-group">
                        <label>জলছাপ</label>
                        <input type="text" name="watermark_text" class="form-control"
                            value="{{ old('watermark_text', $exam->watermark_text ?? '') }}">
                    </div>

                    <!-- Status -->
                    <div class="col-lg-6 form-group">
                        <label>স্ট্যাটাস</label>
                        <select name="status" class="form-control">
                            <option value="draft" {{ old('status', $exam->status ?? '') == 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>
                            <option value="published"
                                {{ old('status', $exam->status ?? '') == 'published' ? 'selected' : '' }}>
                                Published
                            </option>
                            <option value="archived"
                                {{ old('status', $exam->status ?? '') == 'archived' ? 'selected' : '' }}>
                                Archived
                            </option>
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                    </div>

                </div>
            </form>
        </div>


    </div>
@endsection

@section('teacher_custom_js')
    <script>
        $('.select2').select2({
            placeholder: "নির্বাচন করুন",
            width: '100%'
        });
    </script>
    <!-- jQuery for auto-calculate total_mark -->
    <script>
        $(document).ready(function() {
            function calculateTotalMark() {
                var totalQ = parseFloat($('#totalQuestion').val()) || 0;
                var marks = parseFloat($('#marks_per_question').val()) || 0;
                $('#total_mark').val(totalQ * marks);
            }

            $('#totalQuestion, #marks_per_question').on('input', calculateTotalMark);

            // Calculate on page load
            calculateTotalMark();
        });
    </script>
@endsection
