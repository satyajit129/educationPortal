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
            <form action="{{ route('questionUploadExcel') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>ক্যাটাগরি</label>
                    <select name="category_id" class="form-control select2" required>
                        <option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>এক্সেল ফাইল</label>
                    <input type="file" name="excel_file" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">আপলোড করুন</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('teacher_custom_js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "প্রশ্ন লিখুন ",
            });
        });
    </script>
@endsection
