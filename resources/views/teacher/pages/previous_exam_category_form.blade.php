@extends('teacher.global.master')

@section('teacher_content')
<div class="card card-default">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="card-title mb-0">{{ isset($category) ? 'পূর্ববর্তী পরীক্ষার সম্পাদনা' : 'পূর্ববর্তী পরীক্ষা যুক্ত করুন' }}</h2>
        <a href="{{ route('previousExamCategoryList') }}" class="btn btn-secondary btn-sm">ফিরে যান</a>
    </div>
    <div class="card-body">

        <form action="{{ route('previousExamCategorySave') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ old('id', $category->id ?? '') }}">

            <div class="form-group">
                <label>নাম</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label>অবস্থা</label>
                <select name="status" class="form-control" required>
                    <option value="1" {{ old('status', $category->status ?? '1') === '1' ? 'selected' : '' }}>সক্রিয়</option>
                    <option value="0" {{ old('status', $category->status ?? '0') === '0' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ isset($category) ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
