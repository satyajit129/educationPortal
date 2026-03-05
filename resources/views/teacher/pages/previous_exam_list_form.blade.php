@extends('teacher.global.master')

@section('teacher_content')
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0">পূর্ববর্তী পরীক্ষা</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('previousExamListSave') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $exam->id ?? null }}">
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                <div class="form-group">
                    <label for="name">পরীক্ষার নাম</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $exam->name ?? '' }}" placeholder="পরীক্ষার নাম লিখুন" required>
                </div>
                <div class="form-group">
                    <label for="year_id">বছর</label>
                    <select name="year_id" id="year_id" class="form-control select2" required>
                        <option value="">বছর নির্বাচন করুন</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->id }}" {{ old('year_id', $exam->year_id ?? '') == $year->id ? 'selected' : '' }}>
                                {{ $year->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
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
@endsection
