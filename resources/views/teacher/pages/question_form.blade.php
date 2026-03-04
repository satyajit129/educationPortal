@extends('teacher.global.master')

@section('teacher_custom_style')
    <!-- Select2 CSS -->

    <style>
        .cke_chrome {
            width: 100% !important;
        }
    </style>
@endsection

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">{{ isset($question) ? 'প্রশ্ন সম্পাদনা করুন' : 'নতুন প্রশ্ন যোগ করুন' }}</h2>
            <a href="{{ route('questionList') }}" class="btn btn-primary btn-sm">ফিরে যান</a>
        </div>
        <div class="card-body">
            <form action="{{ isset($question) ? route('questionSave', $question->id) : route('questionSave') }}"
                method="POST">
                @csrf

                <!-- Question Category -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">ক্যাটাগরি</label>
                    <select name="category_id" class="form-control select2" required>
                        <option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $question->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Question Text -->
                <div class="mb-3">
                    <label for="question_text" class="form-label">প্রশ্ন</label>
                    <textarea name="question_text" id="questionText" class="form-control" rows="3" required>
                    {{ old('question_text', $question->question_text ?? '') }}
                </textarea>
                </div>

                <!-- Options -->
                <!-- Options -->
                <div class="mb-3">
                    <label class="form-label">উত্তরের বিকল্পসমূহ (৪টি)</label>
                    @for ($i = 0; $i < 4; $i++)
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                <input type="radio" name="correct_option" value="{{ $i }}"
                                    {{ isset($question->options[$i]) && $question->options[$i]->is_correct ? 'checked' : '' }}>
                            </span>
                            <textarea name="options[]" id="optionTextarea{{ $i }}" class="form-control" rows="2" required>{{ old('options.' . $i, $question->options[$i]->option_text ?? '') }}</textarea>
                        </div>
                    @endfor
                    <small class="text-muted">সঠিক উত্তর নির্বাচন করতে রেডিও বাটন ব্যবহার করুন</small>
                </div>


                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">বর্ণনা (ঐচ্ছিক)</label>
                    <textarea name="description" id="questionDescription" class="form-control" rows="3">
                    {{ old('description', $question->description->description ?? '') }}
                </textarea>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">অবস্থা</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $question->status ?? '') == 'active' ? 'selected' : '' }}>
                            সক্রিয়
                        </option>
                        <option value="inactive"
                            {{ old('status', $question->status ?? '') == 'inactive' ? 'selected' : '' }}>
                            নিষ্ক্রিয়
                        </option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit"
                        class="btn btn-success">{{ isset($question) ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "প্রশ্ন লিখুন ",
            });
        });
    </script>

    <script>
        CKEDITOR.replace('questionText');
        CKEDITOR.replace('questionDescription');

        $(document).ready(function() {
            for (let i = 0; i < 4; i++) {
                CKEDITOR.replace(`optionTextarea${i}`);
            }
        });
    </script>
@endsection
